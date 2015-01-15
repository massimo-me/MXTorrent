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

class RemoteUploadCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('mxt:torrent:remote-upload')
            ->setDescription('Upload torrent from remote server');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        #@WIP
    }
}