<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\CoreBundle\Services\Download\TorCache.php
 *
 * Torcache.net is a free service for caching torrent files online.
 */
namespace MXT\CoreBundle\Services\Download;

use MXT\CoreBundle\Services\MXTClient;

class TorCache
{
    /**
     * @var MXTClient
     */
    private $client;

    /**
     * @var String
     */
    private $folder;

    public function __construct(MXTClient $client, $folder)
    {
        $this->client = $client;
        $this->folder = $folder;
    }

    public function download($torrentLink, $fileName)
    {
        $this->client->get(
            $torrentLink,
            [
                'save_to' => sprintf('%s/%s.torrent', $this->folder, $fileName),
            ]);
    }
}