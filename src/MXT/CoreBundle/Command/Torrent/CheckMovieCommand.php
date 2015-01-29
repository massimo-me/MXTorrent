<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\CoreBundle\Command\Torrent\CheckMovieCommand
 *
 */
namespace MXT\CoreBundle\Command\Torrent;

use MXT\CoreBundle\Document\File;
use MXT\CoreBundle\Document\Torrent;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckMovieCommand extends ContainerAwareCommand
{
    private $dm;

    protected function configure()
    {
        $this->setName('mxt:torrent:check-movie')
            ->setDescription('Check movie in your db');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->dm = $this->getContainer()->get('doctrine_mongodb');
        $torrents = $this->dm->getRepository('MXTCoreBundle:Torrent')->findAll();

        foreach($torrents as $torrent) {
            $output->writeln(sprintf('<comment>Check: </comment>%s', $torrent->getTitle()));
            if (!$torrent->hasFiles()) {
                $output->write("<error>This torrent does not have file</error> \n\n");
                continue;
            }

            $this->checkTorrent($torrent, $output);
            $output->writeln('');
        }
    }

    private function checkTorrent(Torrent $torrent, OutputInterface $output)
    {
        $validExtensions = $this->getValidExtensions();

        foreach ($torrent->getFiles() as $file) {
            $fileExtension = pathinfo($file->getName(), PATHINFO_EXTENSION);
            if (!array_key_exists($fileExtension, $validExtensions)) {
                $output->write('<error>x</error>');
            }else {
                $this->setValidMovie($file);
                $output->write('<info>.</info>');
            }
        }
    }

    private function getValidExtensions()
    {
        $extensions = $this->getContainer()->getParameter('mxt_core.movie_type');

        return array_filter($extensions, function($value) {
            return $value;
        });
    }

    private function setValidMovie(File $file)
    {
        $file->setMovie(true);
        $this->dm->getManager()->persist($file);
        $this->dm->getManager()->flush();
    }
}