<?php

namespace MXT\CoreBundle\Subscriber;

use MXT\CoreBundle\Event\FilterTorrentEvent;
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
        return array(
            CoreEvents::TORRENT_STORE => 'onTorrentStore'
        );
    }

    public function onTorrentStore(FilterTorrentEvent $event)
    {
        $torrent = $event->getTorrent();

        $dm = $this->container->get('doctrine_mongodb')->getManager();
        $dm->persist($torrent);
        $dm->flush();

        $this->container->get('event_dispatcher')->dispatch(CoreEvents::TORRENT_CREATED, new FilterTorrentEvent($torrent));
        return $event;
    }

}