<?php

namespace App;

class YamlConfigurationProvider
{
    /**
     * @return string
     * @throws \Exception
     */
    public static function getFilePath()
    {
        $dir = __DIR__.'/../';

        $localCfg = $dir.'config.local.yml';
        if(is_readable($localCfg)) {
            return $localCfg;
        }

        $defaultCfg = $dir.'config.yml';
        if(is_readable($defaultCfg)) {
            return $defaultCfg;
        }

        throw new \Exception('Cannot read main configuration file in: '.$localCfg.' and '.$defaultCfg);
    }
}