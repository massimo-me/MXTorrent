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

use MXT\CoreBundle\Document\Torrent;
use MXT\CoreBundle\Document\File;
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
        $this->dm = $this->getContainer()->get('doctrine_mongodb')->getManager();
        $torrentPath = sprintf('%s/%s.torrent', $this->getContainer()->getParameter('mxt_core.torrent.folder'), getenv('TR_TORRENT_NAME'));
        $torrentShow = $this->getContainer()->get('mxt_transmission.show')->with($torrentPath);
        $torrent = $this->dm->getRepository('MXTCoreBundle:Torrent')->findOneBy([
            'hash' => strtoupper(getenv('TR_TORRENT_HASH'))
        ]);

        if (!$torrent) {
            return ;
        }

        $this->saveFiles($torrentShow->getFiles(), $torrent);
    }

    private function saveFiles(array $files, Torrent $torrent)
    {
        foreach($files as $file) {
            $torrentFile = new File();
            $torrentFile->setName($file);
            $torrentFile->setSize(filesize(sprintf('%s/%s', getenv('TR_TORRENT_DIR'), $file)));

            $torrent->addFile($torrentFile);
        }

        $this->dm->persist($torrent);
        $this->dm->flush();
    }
}