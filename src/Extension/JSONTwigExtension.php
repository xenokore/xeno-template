<?php

namespace Xenokore\Template\Extension;

class JSONTwigExtension extends \Twig_Extension
{
    public function getName(): string
    {
        return 'json_extension';
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction(
                'json',
                [$this, 'jsonEncode'],
                ['is_safe' => ['html']] // Allow raw HTML output
            ),
        ];
    }

    public function jsonEncode($data): string
    {
        // return json_encode($data, JSON_FORCE_OBJECT);
        return json_encode($data);
    }
}
