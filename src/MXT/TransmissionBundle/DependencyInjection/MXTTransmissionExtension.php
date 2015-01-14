<?php

namespace MXT\TransmissionBundle\DependencyInjection;

use MXT\CoreBundle\DependencyInjection\MXTCoreExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

class MXTTransmissionExtension extends MXTCoreExtension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        parent::load($configs, $container);

        $this->loader = new Loader\YamlFileLoader($container, new FileLocator(
            sprintf('%s/../Resources/config', (__DIR__))
        ));
        $this->loader->load('services.yml');
    }
}