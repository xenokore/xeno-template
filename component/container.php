<?php

use function DI\create;
use function DI\get;
use function DI\autowire;
use function DI\factory;

use Xenokore\Template\TwigFactory;

return [
    \Twig\Environment::class => function ($container) {

        // Load default config
        $config = require_once __DIR__ . '/../config/twig.conf.default.php';
        $container_config = null;

        // Load config from container
        if ($container->has('config')) {
            $container_config = $container->get('config');

            if (is_a($container_config, '\\Xenokore\\Config\\Config')) {
                foreach ($config as $key => $value) {
                    $value = $container_config->get('twig.' . $key);
                    if (!is_null($value)) {
                        $config[$key] = $value;
                    }
                }
            } elseif (is_array($container_config)) {
                $config = ArrayHelper::mergeRecursiveDistinct($config, $container_config);
            }
        }

        $twig = TwigFactory::create($config);

        // Load App specific Twig extensions
        if ($container->has('twig.extensions')) {
            foreach ($container->get('twig.extensions') as $class) {
                // By loading the extensions using the container we can autowire any dependencies
                if ($container->has($class)) {
                    $twig->addExtension($container->get($class));
                }
            }
        }

        // Load App specific globals
        if ($container->has('twig.globals')) {
            foreach ($container->get('twig.globals') as $variable => $value) {
                $twig->addGlobal($variable, $value);
            }
        }

        return $twig;
    },

    \Twig_Environment::class => function ($container) {
        return $container->get(\Twig\Environment::class);
    },

    'twig' => function ($container) {
        return $container->get(\Twig\Environment::class);
    }
];
