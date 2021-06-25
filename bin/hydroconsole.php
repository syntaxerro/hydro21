<?php

require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;

$containerBuilder = new \Symfony\Component\DependencyInjection\ContainerBuilder();
$loader = new \Symfony\Component\DependencyInjection\Loader\YamlFileLoader($containerBuilder, new \Symfony\Component\Config\FileLocator(__DIR__));
$loader->load(__DIR__.'/../config.yml');

$app = new Application();
$app->setName('Hydroserver console by Marcin Łacina 2k21');

$commands = new \DirectoryIterator(__DIR__.'/../src/Command');
foreach($commands as $command) {
    if($command->isDot() || $command->isDir()) {
        continue;
    }

    $className = 'App\\Command\\'.$command->getBasename('.php');
    /** @var \App\AbstractCommand $commandInstance */
    $commandInstance = new $className();
    $commandInstance->setContainer($containerBuilder);
    $app->add($commandInstance);
}

$app->run();