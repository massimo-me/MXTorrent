<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\CoreBundle\CoreEvent
 *
 */
namespace MXT\CoreBundle;

final class CoreEvents
{
    const TORRENT_INITIALIZE   = 'mxt_core.torrent.initialize';
    const TORRENT_CREATED = 'mxt_core.torrent.created';
    const TORRENT_UPDATED = 'mxt_core.torrent.updated';
}