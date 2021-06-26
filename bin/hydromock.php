<?php

require __DIR__.'/../vendor/autoload.php';

$containerBuilder = new \Symfony\Component\DependencyInjection\ContainerBuilder();
$loader = new \Symfony\Component\DependencyInjection\Loader\YamlFileLoader($containerBuilder, new \Symfony\Component\Config\FileLocator(__DIR__));
$loader->load(\App\YamlConfigurationProvider::getFilePath());

\App\TCPController\GPIO::$isDevModeEnabled = true;

$containerBuilder->get('server_wrapper')->run();