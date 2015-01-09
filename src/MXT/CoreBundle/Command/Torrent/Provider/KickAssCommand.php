<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\Commands\KickAssCommand
 *
 */

namespace MXT\CoreBundle\Command\Torrent\Provider;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class KickAssCommand extends ContainerAwareCommand
{
    /**
     * @var null|Client
     */
    private $client = null;

    protected function configure()
    {
        $this->setName('mxt:torrent:kickass')
            ->addArgument(
                'query',
                InputArgument::REQUIRED
            )
            ->addArgument(
                'order',
                InputArgument::REQUIRED
            )
            ->addOption(
                'page',
                'p',
                InputOption::VALUE_OPTIONAL,
                0
            )
            ->addOption(
                'download',
                'd',
                InputOption::VALUE_NONE
            );
        $this->client = new \GuzzleHttp\Client();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $query = urlencode($input->getArgument('query'));

        $configOrder = $this->getContainer()->getParameter('kickass.search.fields');
        $order = $input->getArgument('order');
        if (!array_key_exists($order, $configOrder)) {
            throw new \Exception(sprintf(
                'Invalid order (%s). Select one of %s',
                $order,
                implode(',', array_keys($configOrder))
            ));
        }

        $page = $input->getOption('page');

        $response = null;
        try {
            $response = $this->client->get(sprintf(
                "https://kickass.so/json.php?q=%s&field=%s&page=%d",
                $query,
                $order,
                $page
            ), [
                'connect_timeout' => 5,
                'timeout' => 5
            ]);
        } catch(ClientException $e) {
            throw new \Exception("Invalid response from Kickass.so");
        }

        foreach($response->json()['list'] as $result) {
           var_dump($result);
        }
    }
}