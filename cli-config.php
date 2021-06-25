<?php

require __DIR__.'/vendor/autoload.php';

$containerBuilder = new \Symfony\Component\DependencyInjection\ContainerBuilder();
$loader = new \Symfony\Component\DependencyInjection\Loader\YamlFileLoader($containerBuilder, new \Symfony\Component\Config\FileLocator(__DIR__));
$loader->load(__DIR__.'/config.yml');

/** @var \Doctrine\ORM\EntityManagerInterface $em */
$em = $containerBuilder->get('db.em');

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($em);