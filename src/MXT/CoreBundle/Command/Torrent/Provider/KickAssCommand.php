<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\CoreBundle\Command\Torrent\Provide\KickAssCommand
 *
 */

namespace MXT\CoreBundle\Command\Torrent\Provider;

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
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('mxt_core.torrent.client.kickass')->request(
            ['test', 'age', '0']
        );
    }
}