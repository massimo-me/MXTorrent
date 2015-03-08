<?php

namespace MXT\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class TorrentController extends Controller
{
    /**
     * @Route("/save/{hash}", name="save_torrent")
     *
     * @param $hash
     */
    public function saveAction(Request $request, $hash)
    {
        $torrent =  $this->container->get('doctrine_mongodb')->getRepository('MXTCoreBundle:Torrent')->findOneBy([
            'hash' => $hash
        ]);

        if (!$torrent or $torrent->isSaved()) {
            throw $this->createNotFoundException();
        }

        $file = $this->container->get('mxt_core.download.torCache')->download(
            $torrent->getDownloadLink(),
            $torrent->getTitle()
        );

        if (!$file->getPath()) {
            return $this->redirectAndSetFlash(
                'danger',
                'torrent.saved.error',
                $request
            );
        }

        $torrent->setSaved(true);

        $torrentUtils = $this->container->get('mxt_core.torrent_utils');
        $torrentUtils->grabInfo($torrent);

        $files = $this->container->get('mxt_transmission.show')
            ->with($file->getPath())
            ->getFiles();

        $torrentUtils->saveFiles($files, $torrent);

        return $this->redirectAndSetFlash(
            'success',
            'torrent.saved.success',
            $request
        );
    }

    private function redirectAndSetFlash($type, $message, Request $request)
    {
        $this->addFlash($type, $this->container->get('translator')->trans(
            $message, [], 'MXTCoreBundle'
        ));

        return $this->redirect(
            $request->headers->get('referer')
        );
    }
}