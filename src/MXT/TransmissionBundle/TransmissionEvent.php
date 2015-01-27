<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\TransmissionBundle\TransmissionEvent
 *
 */
namespace MXT\TransmissionBundle;

final class TransmissionEvent
{
    const TORRENT_DOWNLOAD_STARTED = 'mxt_transmission.torrent.download.started';
    const TORRENT_DOWNLOAD_COMPLETED   = 'mxt_transmission.torrent.download.completed';
}