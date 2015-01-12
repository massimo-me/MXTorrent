<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\CoreBundle\Test\Client\Torrent\KickAss.php
 *
 */

namespace MXT\CoreBundle\Test\Services\Client\Torrent;

class KickAss
{
    public function request()
    {
        $response = json_decode(
            file_get_contents(sprintf('%s/../../../Response/Client/Torrent/kickAss.so.json', (__DIR__))),
            true
        );

        return $response['list'];
    }
}