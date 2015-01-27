<?php

namespace MXT\NotificationBundle\Subscriber;

use MXT\CoreBundle\Document\Torrent;
use MXT\CoreBundle\Event\FilterTorrentEvent;
use MXT\TransmissionBundle\TransmissionEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NotificationSubscriber implements EventSubscriberInterface
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return [
            TransmissionEvent::TORRENT_DOWNLOAD_COMPLETED => 'onTorrentDownloadCompleted'
        ];
    }

    public function onTorrentDownloadCompleted(FilterTorrentEvent $event)
    {
        if (!$this->container->getParameter('mxt_notification.push.pushover.enabled')) {
            return $event;
        }

        return $this->createNotification($event->getTorrent());
    }

    private function createNotification(Torrent $torrent)
    {
        $pushOver = $this->container->get('mxt_notification.push.pushover');
        $pushOver->getPush()->setTitle($this->container->get('translator')->trans('torrent.download.completed'));
        $pushOver->getPush()->setMessage($torrent->getTitle());

        return $pushOver->send();
    }
}