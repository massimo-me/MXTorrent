<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\CoreBundle\Services\Client\Torrent\KickAss.php
 *
 */

namespace MXT\CoreBundle\Services\Client\Torrent;

use MXT\CoreBundle\Services\MXTClient;
use Symfony\Component\DependencyInjection\ContainerInterface;

class KickAss
{
    protected $container;
    protected $client;

    public function __construct(ContainerInterface $container, MXTClient $client)
    {
        $this->container = $container;
        $this->client = $client;
    }

}