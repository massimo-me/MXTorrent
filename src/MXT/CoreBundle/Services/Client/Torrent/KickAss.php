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

    /**
     * @param array $filters
     * @return array|null
     */
    public function request(array $filters)
    {
        $kickAssApi = $this->container->getParameter('kickass.api');
        $kickAssTimeOut = $this->container->getParameter('kickass.timeout');

        $response = null;
        try {
            $response = $this->client->get(
                sprintf('%s?%s', $kickAssApi['url'], http_build_query(
                    array_combine($kickAssApi['filters'], $filters)
                )),
                [
                    'connect_timeout' => $kickAssTimeOut['connection'],
                    'timeout'         => $kickAssTimeOut['response']
                ]
            );
        } catch(\Exception $e) {
            return null;
        }

        if (!array_key_exists('list', $response->json())) {
            return null;
        }

        return $response->json()['list'];
    }
}