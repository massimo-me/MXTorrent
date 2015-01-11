<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\CoreBundle\Command\Torrent\Provide\KickAssCommand
 *
 */

namespace MXT\CoreBundle\Command\Torrent\Provider;

use MXT\CoreBundle\Document\Torrent;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class KickAssCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('mxt:torrent:kickass')
            ->setDescription('Search and download .torrent from https://kickass.so/')
            ->addArgument(
                'searchQuery',
                InputArgument::REQUIRED,
                'Search query'
            )
            ->addArgument(
                'order',
                InputArgument::REQUIRED,
                'Order search'
            )
            ->addOption(
                'page',
                'p',
                InputOption::VALUE_OPTIONAL,
                0
            )
            ->addOption(
                'mongoStore',
                'm',
                InputOption::VALUE_NONE,
                'Save result'
            )
            ->addOption(
                'download',
                'd',
                InputOption::VALUE_NONE,
                'Download .torrent'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $searchQuery = $input->getArgument('searchQuery');
        $order = $input->getArgument('order');
        $page = $input->getOption('page');

        $this->checkOrder($order);

        $kickAssClient = $this->getContainer()->get('mxt_core.torrent.client.kickAss');

        $torrentList = $kickAssClient->request([
            $searchQuery,
            $order,
            $page
        ]);

        if (!$torrentList) {
            $output->writeln('<error>Nothing found!</error>');
            return ;
        }

        foreach($torrentList as $torrent) {
            $this->printResult($output, $torrent);

            if ($input->getOption('download')) {
                $this->downloadResult($torrent);
            }

            if ($input->getOption('mongoStore')) {
                $this->saveResult($torrent);
            }
        }
    }

    /**
     * @param $order
     * @throws \Exception
     */
    private function checkOrder($order)
    {
        $configOrder = $this->getContainer()->getParameter('kickAss.search.fields');
        if (!array_key_exists($order, $configOrder)) {
            throw new \Exception(sprintf(
                'Invalid order (%s). Select one of %s',
                $order,
                implode(',', array_keys($configOrder))
            ));
        }
    }

    private function printResult(OutputInterface $output, array $torrent)
    {
        $output->writeln(sprintf('Title: <info>%s</info>', $torrent['title']));

        $verified = ($torrent['verified']) ? '<info>Ok</info>' : '<error>False</error>' ;
        $output->writeln(sprintf('Verified: %s', $verified));

        $mbSize = round($torrent['size'] / 1024 / 1024, 2);
        $output->writeln(sprintf('Size: <info>%s MB</info>', $mbSize));
        $output->writeln("\n");
    }

    private function downloadResult(array $torrent)
    {
        $this->getContainer()->get('mxt_core.download.torCache')->download(
            $torrent['torrentLink'],
            $torrent['title']
        );
    }

    private function saveResult(array $torrent)
    {
        $dm = $this->getContainer()->get('doctrine_mongodb')->getManager();

        $checkTorrent = $dm->getRepository('MXTCoreBundle:Torrent')->findOneBy([
            'hash' => $torrent['hash']
        ]);

        if ($checkTorrent) {
            return ;
        }

        $torrentDocument = new Torrent();

        $torrentDocument->setTitle($torrent['title']);
        $torrentDocument->setDate(new \DateTime($torrent['pubDate']));
        $torrentDocument->setFiles($torrent['files']);
        $torrentDocument->setHash($torrent['hash']);
        $torrentDocument->setSize($torrent['size']);
        $torrentDocument->setTorrentLink($torrent['torrentLink']);
        $torrentDocument->setVerified($torrent['verified']);

        $dm->persist($torrentDocument);
        $dm->flush();
    }
}