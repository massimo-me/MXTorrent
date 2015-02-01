<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\CoreBundle\Command\Torrent\Provide\KickAssCommand
 *
 */
namespace MXT\CoreBundle\Command\Torrent\Provider;

use Goutte\Client;
use MXT\CoreBundle\Document\Torrent;
use MXT\CoreBundle\Event\FilterTorrentEvent;
use MXT\CoreBundle\CoreEvents;
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
            )
            ->addOption(
                'transmissionInteract',
                't',
                InputOption::VALUE_NONE,
                'Upload .torrent on Transmission Server'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $searchQuery = $input->getArgument('searchQuery');
        $order = $input->getArgument('order');
        $page = $input->getOption('page');

        $kickAssClient = $this->getContainer()->get('mxt_core.torrent.client.kickAss');

        $torrentList = $kickAssClient->request([
            $searchQuery,
            $this->getOrderValue($order),
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

            if ($input->getOption('transmissionInteract')) {
               $this->uploadTorrent($output, $torrent);
            }
        }
    }

    /**
     * @param $order
     * @throws \Exception
     */
    private function getOrderValue($order)
    {
        $configOrder = $this->getContainer()->getParameter('kickAss.search.fields');
        if (!array_key_exists($order, $configOrder)) {
            throw new \Exception(sprintf(
                'Invalid order (%s). Select one of %s',
                $order,
                implode(',', array_keys($configOrder))
            ));
        }

        return $configOrder[$order];
    }

    private function printResult(OutputInterface $output, array $torrent)
    {
        $output->writeln(sprintf('Title: <info>%s</info>', $torrent['title']));

        $verified = ($torrent['verified']) ? '<info>Ok</info>' : '<error>False</error>' ;
        $output->writeln(sprintf('Verified: %s', $verified));

        $mbSize = round($torrent['size'] / 1024 / 1024, 2);
        $output->writeln(sprintf('Size: <info>%s MB</info>', $mbSize));
        $output->writeln('');
    }

    private function downloadResult(array $torrent)
    {
        return $this->getContainer()->get('mxt_core.download.torCache')->download(
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
        $torrentDocument->setHash($torrent['hash']);
        $torrentDocument->setSize($torrent['size']);
        $torrentDocument->setTorrentLink($torrent['torrentLink']);
        $torrentDocument->setVerified($torrent['verified']);
        $torrentDocument->setLink($torrent['link']);

        if ($grab = $this->grab($torrent['link'])) {
            $torrentDocument->setFullTitle($grab['title']);
            $torrentDocument->setImage($grab['image']);
        }

        $event = new FilterTorrentEvent($torrentDocument);
        $this->getContainer()->get('event_dispatcher')->dispatch(CoreEvents::TORRENT_STORE, $event);
    }

    private function uploadTorrent(OutputInterface $output, array $torrent)
    {
        $result = $this->askToUploadTorrent($output);

        if (!$result) {
            $output->writeln(sprintf('Torrent <question>%s</question> not uploaded on Transmission Server', $torrent['title']));
            $output->writeln('');
            return ;
        }

        $torrentFile = $this->downloadResult($torrent)->getPath();

        try {
            $this->transmissionUpload($torrentFile);
            $this->saveResult($torrent);

            $output->writeln(sprintf('Torrent <info>%s</info> was uploaded', $torrent['title']));
        } catch (\Exception $e) {
            $output->writeln(sprintf('Transmission error: <error>%s</error>', $e->getMessage()));
        }

        $output->writeln('');
    }

    private function transmissionUpload($torrentFile)
    {
        $this->getContainer()->get('mxt_transmission.transmission')->add(
            base64_encode(
                file_get_contents($torrentFile)
            ),
            true
        );
    }

    private function askToUploadTorrent(OutputInterface $output)
    {
        $dialog = $this->getHelper('dialog');

        return $dialog->askAndValidate(
            $output,
            '<comment>[enter] or write "yes" to insert this .torrent on Transmission: </comment>',
            function ($response) {
                if (empty($response)) return true;

                return (bool) preg_match('/^(?:yes)/i', $response);
            }
        );
    }

    private function grab($link)
    {
        $client = new Client();

        $info = [];

        try {
            $crawler = $client->request('GET', $link);
            $info['image'] = $crawler->filter('.movieCover img')->attr('src');
            $info['title'] = $crawler->filter('.dataList ul li a span')->text()
;        } catch(\Exception $e) {
            return $info;
        }

        return $info;
    }
}