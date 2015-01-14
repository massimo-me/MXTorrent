<?php

namespace MXT\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class MXTBaseExtension extends Extension
{
    /**
     * @var Loader\YamlFileLoader
     */
    protected $loader;

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->addConfiguration($configs);
    }

    private function addConfiguration(array $configs)
    {
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);
    }

    protected function loadConfiguration(ContainerBuilder $container, $currentDirectory)
    {
        $this->loader = new Loader\YamlFileLoader($container, new FileLocator(
            sprintf('%s/../Resources/config', $currentDirectory)
        ));
    }
}
