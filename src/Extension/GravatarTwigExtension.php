<?php

namespace Xenokore\Template\Extension;

class GravatarTwigExtension extends \Twig_Extension
{

    public function getName(): string
    {
        return 'gravatar_extension';
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('gravatar', [$this, 'getGravatarURL']),
        ];
    }

    /**
     * Render all flash messages which have not been displayed yet
     *
     * @return string
     */
    public function getGravatarURL(?string $email, int $size = 128, bool $https = true): string
    {
        $email_hash = md5(strtolower(trim((string)$email)));
        return 'http' . ($https ? 's' : '') . '://www.gravatar.com/avatar/' . $email_hash . '?d=mp&s=' . $size;
    }
}
