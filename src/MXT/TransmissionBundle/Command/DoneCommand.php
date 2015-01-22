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

use R24\CoreBundle\Document\File;
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
        $dm = $this->getContainer()->get('doctrine_mongodb')->getManager();

        $torrentPath = sprintf('%s/%s.torrent', getenv('TR_TORRENT_DIR'), getenv('TR_TORRENT_NAME'));
        $torrentShow = $this->getContainer()->get('mxt_transmission.show')->with($torrentPath);

        $torrent = $dm->getRepository('MXTCoreBundle:Torrent')->findOneBy([
            'hash' => getenv('TR_TORRENT_HASH')
        ]);

        foreach($torrentShow->getFiles() as $torrentFile) {
            $file = new File();
            $file->setName($torrentFile);
            $file->setSize(filesize(sprintf('%s/%s', getenv('TR_TORRENT_DIR'), $torrentFile)));

            $torrent->addFile($file);
        }

        $dm->persist($torrent);
        $dm->flush();
    }
}