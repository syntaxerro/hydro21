<?php
require __DIR__.'/../vendor/autoload.php';

$containerBuilder = new \Symfony\Component\DependencyInjection\ContainerBuilder();
$loader = new \Symfony\Component\DependencyInjection\Loader\YamlFileLoader($containerBuilder, new \Symfony\Component\Config\FileLocator(__DIR__));
$loader->load(__DIR__.'/../config.yml');

$controllerClass = 'App\\Controller\\'.$_GET['ctrl'];
$controllerMethod = $_GET['action'];

$ctrlInstance = new $controllerClass($containerBuilder);
echo $ctrlInstance->{$controllerMethod}(json_decode(file_get_contents('php://input'), JSON_OBJECT_AS_ARRAY), $_SERVER);