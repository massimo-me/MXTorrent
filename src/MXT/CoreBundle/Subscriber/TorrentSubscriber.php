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
            CoreEvents::TORRENT_INITIALIZE => 'onTorrentInitialize',
            TransmissionEvent::TORRENT_DOWNLOAD_COMPLETED => 'onTorrentDownloadCompleted'
        ];
    }

    public function onTorrentInitialize(FilterTorrentEvent $event)
    {
        return $this->saveTorrent($event, CoreEvents::TORRENT_CREATED);
    }

    public function onTorrentDownloadCompleted(FilterTorrentEvent $event)
    {
        return $this->saveTorrent($event, CoreEvents::TORRENT_UPDATED);
    }

    private function saveTorrent(FilterTorrentEvent $event, $eventName)
    {
        $dm = $this->container->get('doctrine_mongodb')->getManager();
        $dm->persist($event->getTorrent());
        $dm->flush();

        $this->container->get('event_dispatcher')->dispatch(
            $eventName,
            new FilterTorrentEvent($event->getTorrent())
        );
        return $event;
    }

}