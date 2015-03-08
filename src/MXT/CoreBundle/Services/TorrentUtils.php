<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\CoreBundle\Controller\SearchController
 *
 */

namespace MXT\CoreBundle\Services;

use Doctrine\ODM\MongoDB\DocumentManager;
use MXT\CoreBundle\CoreEvents;
use MXT\CoreBundle\Document\Torrent;
use Goutte\Client;
use MXT\CoreBundle\Event\FilterTorrentEvent;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;

class TorrentUtils
{
    /*
     * @var DocumentManager $dm
     */
    private $dm;

    /*
     * @var EventDispatcher
     */
    private $dispatcher;

    public function __construct(DocumentManager $dm, TraceableEventDispatcher $dispatcher)
    {
        $this->dm = $dm;
        $this->dispatcher = $dispatcher;
    }

    public function create(array $torrentInfo)
    {
        $torrent =  $this->dm->getRepository('MXTCoreBundle:Torrent')->findOneBy([
            'hash' => $torrentInfo['hash']
        ]);

        if ($torrent) {
            return $torrent;
        }

        $torrent = new Torrent();

        $torrent->setTitle($torrentInfo['title']);
        $torrent->setDate(new \DateTime($torrentInfo['pubDate']));
        $torrent->setHash($torrentInfo['hash']);
        $torrent->setSize($torrentInfo['size']);
        $torrent->setTorrentLink($torrentInfo['torrentLink']);
        $torrent->setVerified($torrentInfo['verified']);
        $torrent->setLink($torrentInfo['link']);

        $this->dispatcher->dispatch(
            CoreEvents::TORRENT_INITIALIZE,
            new FilterTorrentEvent($torrent)
        );

        return $torrent;
    }

    public function grabInfo(Torrent $torrent)
    {
        $client = new Client();

        try {
            $crawler = $client->request('GET', $torrent->getLink());
            $torrent->setImage($crawler->filter('.movieCover img')->attr('src'));
            $torrent->setFullTitle($crawler->filter('.dataList ul li a span')->text());
        } catch(\Exception $e) {
            return $torrent;
        }

        $this->dispatcher->dispatch(
            CoreEvents::TORRENT_UPDATED,
            new FilterTorrentEvent($torrent)
        );

        return $torrent;
    }
}