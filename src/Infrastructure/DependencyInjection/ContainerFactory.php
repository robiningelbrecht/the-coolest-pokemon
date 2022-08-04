<?php

namespace App\Infrastructure\DependencyInjection;

use App\Infrastructure\Environment\Environment;
use App\Infrastructure\Environment\Settings;
use Dotenv\Dotenv;
use Psr\Container\ContainerInterface;

class ContainerFactory
{
    public static function create(): ContainerInterface
    {
        $appRoot = Settings::getAppRoot();

        $dotenv = Dotenv::createImmutable($appRoot);
        $dotenv->load();

        // At this point the container has not been built. We need to load the settings manually.
        $settings = Settings::load();
        $containerBuilder = ContainerBuilder::create();

        if (Environment::PRODUCTION === Environment::from($_ENV['ENVIRONMENT'])) {
            // Compile and cache container.
            $containerBuilder->enableCompilation($settings->get('slim.cache_dir') . '/container');
            $containerBuilder->enableClassAttributeCache($settings->get('slim.cache_dir') . '/class-attributes');
        }
        $containerBuilder->addDefinitions($appRoot . '/config/container.php');
        $containerBuilder->addCompilerPasses(...require $appRoot . '/config/compiler-passes.php');
        return $containerBuilder->build();
    }
}