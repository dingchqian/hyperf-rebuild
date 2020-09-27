<?php
/**
 * Created by PhpStorm.
 * Script Name: ConfigFactory.php
 * Create: 9:12 下午
 * Description:
 * Author: Jason<dcq@kuryun.cn>
 */

namespace Rebuild\Config;


use Symfony\Component\Finder\Finder;

class ConfigFactory
{
    public function __invoke()
    {
        // TODO: Implement __invoke() method.
        $basePath = BASE_PATH . '/config/';
        $configFile = $this->readConfig($basePath . '/config.php');
        $autoloadConfig = $this->readPath([$basePath . '/autoload']);
        $configs = array_merge_recursive($configFile, $autoloadConfig);

        return new Config($configs);
    }

    protected function readConfig(string $string): array {
        $config = require $string;
        if(! is_array($config)) {
            return [];
        }

        return $config;
    }

    protected function readPath(array $dirs) {
        $config = [];
        $finder = new Finder();
        $finder->files()->in($dirs)->name('*.php');
        foreach ($finder as $fileInfo) {
            $key = $fileInfo->getBasename('.php');
            $value = require $fileInfo->getRealPath();
            $config[$key] = $value;
        }

        return $config;
    }
}