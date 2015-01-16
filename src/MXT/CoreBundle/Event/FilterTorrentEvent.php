<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\CoreBundle\Event\FilterTorrentEvent
 *
 */
namespace MXT\CoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use MXT\CoreBundle\Document\Torrent;

class FilterTorrentEvent extends Event
{
    protected $torrent;

    public function __construct(Torrent $torrent)
    {
        $this->torrent = $torrent;
    }

    public function getTorrent()
    {
        return $this->torrent;
    }
}
