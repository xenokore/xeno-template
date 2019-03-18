<?php

namespace Xenokore\Template\Tests;

use Xenokore\Template\TwigFactory;

use PHPUnit\Framework\TestCase;
use Xenokore\Utility\Helper\ArrayHelper;

class TemplateTest extends TestCase
{
    private function getConfig()
    {
        return ArrayHelper::mergeRecursiveDistinct(
            require __DIR__ . '/../config/twig.conf.default.php',
            [
                'views_dir' => __DIR__ . '/views'
            ]
        );
    }

    public function testTwigFactory()
    {
        $twig = TwigFactory::create($this->getConfig());

        $this->assertInstanceOf(\Twig\Environment::class, $twig);
    }

    public function testHelloWorldRender()
    {
        $twig = TwigFactory::create($this->getConfig());

        $output = $twig->render('hello_world.twig');

        $this->assertTrue(stripos($output, 'hello world') !== false);
    }
}
