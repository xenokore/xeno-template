<?php

namespace Xenokore\Template;

use Xenokore\Template\Exception\InvalidTwigConfigException;

use Xenokore\Utility\Helper\ClassHelper;
use Xenokore\Utility\Helper\DirectoryHelper;
use Xenokore\Utility\Exception\DirectoryNotAccessibleException;

class TwigFactory
{
    public static function create(array $config): \Twig\Environment
    {
        $caching = $config['debug'] || $config['compile_cache'];
        $twig_view_loaders = [new \Twig\Loader\ArrayLoader((array)$config['views_array'])];

        if (is_string($config['views_dir'])) {
            if (DirectoryHelper::isAccessible($config['views_dir'])) {
                $twig_view_loaders[] = new \Twig\Loader\FilesystemLoader([$config['views_dir']]);
            }
        }

        if ($caching) {
            if (!is_string($config['cache_dir'])) {
                throw new InvalidTwigConfigException('when compiling the cache; `cache_dir` must be set');
            }

            // Also creates the directory if it does not exist yet
            if (!DirectoryHelper::isAccessible($config['cache_dir'], true)) {
                throw new DirectoryNotAccessibleException('twig cache directory not accessible: \'' . $config['cache_dir'] . '\'');
            }
        }

        // TODO: add paths: $twig_filesystem->addPath($config['views_dir'] . '/theme', 'theme');
        // Allows to render views as: @theme/login.twig

        // Load Twig environment
        $twig = new \Twig\Environment(new \Twig\Loader\ChainLoader($twig_view_loaders), [
            'debug'            => (bool) $config['debug'],
            'cache'            => ($caching) ? $config['cache_dir'] : false,
            'charset'          => (string) $config['charset'],
            'strict_variables' => (bool) $config['strict_variables'],
        ]);

        if ($config['debug']) {
            $twig->addExtension(new \Twig\Extension\Debug());
        }

        foreach (glob(__DIR__ . '/Extension/*TwigExtension.php') as $file_path) {
            $full_class = ClassHelper::getClassAndNamespace($file_path);
            $twig->addExtension(new $full_class());
        }

        return $twig;
    }
}
