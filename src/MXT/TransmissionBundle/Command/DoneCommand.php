<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\CoreBundle\Command\Torrent\RemoteUploadCommand
 *
 * Transmission ENV
 *
 * - TR_APP_VERSION
 * - TR_TIME_LOCALTIME
 * - TR_TORRENT_DIR
 * - TR_TORRENT_HASH
 * - TR_TORRENT_ID
 * - TR_TORRENT_NAME
 *
 */
namespace MXT\TransmissionBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DoneCommand extends ContainerAwareCommand
{
    private $dm;

    protected function configure()
    {
        $this->setName('mxt:transmission-done')
            ->setDescription('Script to be started at the end of the download');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        echo($this->getContainer()->get('mxt_transmission.show')->with('/Users/Max/Downloads/test.torrent')->getInfo());
    }
}