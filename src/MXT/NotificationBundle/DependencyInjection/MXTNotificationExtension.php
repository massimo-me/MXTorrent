<?php

namespace MXT\NotificationBundle\DependencyInjection;

use MXT\CoreBundle\DependencyInjection\MXTBaseExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

class MXTNotificationExtension extends MXTBaseExtension
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
    }
}
