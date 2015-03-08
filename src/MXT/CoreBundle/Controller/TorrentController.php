<?php

namespace MXT\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class TorrentController extends Controller
{
    /**
     * @Route("/save/{hash}", name="save_torrent")
     *
     * @param $hash
     */
    public function saveAction($hash)
    {
        $torrent =  $this->container->get('doctrine_mongodb')->getRepository('MXTCoreBundle:Torrent')->findOneBy([
            'hash' => $hash
        ]);

        if (!$torrent or !$torrent->isSaved()) {
            throw $this->createNotFoundException();
        }
    }
}