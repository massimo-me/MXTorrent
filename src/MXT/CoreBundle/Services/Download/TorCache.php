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

    /**
     * @var String
     */
    private $path = null;

    public function __construct(MXTClient $client, $folder)
    {
        $this->client = $client;

        if (!is_dir($folder)) {
            throw new \Exception(sprintf('Invalid folder (%s)', $folder));
        }

        $this->folder = $folder;
    }

    public function download($torrentLink, $fileName)
    {
        try {
            $this->path = sprintf('%s/%s.torrent', $this->folder, $fileName);

            $this->client->get(
                $torrentLink,
                [
                    'save_to' => $this->path
                ]);

            return $this;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getPath()
    {
        return $this->path;
    }
}