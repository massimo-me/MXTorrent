<?php

namespace MXT\NotificationBundle\Test\Stub;

use MXT\TransmissionBundle\TransmissionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NotificationSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            TransmissionEvent::TORRENT_DOWNLOAD_COMPLETED => 'onTorrentDownloadCompleted'
        ];
    }

    public function onTorrentDownloadCompleted($event)
    {
        return $event;
    }
}