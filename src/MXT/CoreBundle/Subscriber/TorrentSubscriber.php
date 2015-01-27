<?php

namespace MXT\CoreBundle\Subscriber;

use MXT\CoreBundle\Document\Torrent;
use MXT\CoreBundle\Event\FilterTorrentEvent;
use MXT\TransmissionBundle\TransmissionEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use MXT\CoreBundle\CoreEvents;

class TorrentSubscriber implements EventSubscriberInterface
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return [
            CoreEvents::TORRENT_STORE => 'onTorrentStore',
            TransmissionEvent::TORRENT_DOWNLOAD_COMPLETED => 'onTorrentDownloadCompleted'
        ];
    }

    public function onTorrentStore(FilterTorrentEvent $event)
    {
        $torrent = $event->getTorrent();

        $this->saveTorrent($torrent);

        $this->container->get('event_dispatcher')->dispatch(CoreEvents::TORRENT_CREATED, new FilterTorrentEvent($torrent));
        return $event;
    }

    public function onTorrentDownloadCompleted(FilterTorrentEvent $event)
    {
        $torrent = $event->getTorrent();

        $this->saveTorrent($torrent);

        $this->container->get('event_dispatcher')->dispatch(CoreEvents::TORRENT_UPDATED, new FilterTorrentEvent($torrent));
        return $event;
    }

    private function saveTorrent(Torrent $torrent)
    {
        $dm = $this->container->get('doctrine_mongodb')->getManager();
        $dm->persist($torrent);
        $dm->flush();
    }

}