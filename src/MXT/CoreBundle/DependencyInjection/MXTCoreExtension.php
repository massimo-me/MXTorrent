<?php

namespace MXT\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class MXTCoreExtension extends MXTBaseExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        parent::load($configs, $container);

        $this->loadConfiguration($container, (__DIR__));
        $this->loader->load('services.yml');
        $this->loader->load('subscriber.yml');
        $this->loader->load('config.yml');
        $this->loader->load('Torrent/Providers/kickass.yml');
    }
}
