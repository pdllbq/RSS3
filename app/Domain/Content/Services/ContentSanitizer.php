<?php

namespace App\Domain\Content\Services;

use HTMLPurifier;
use HTMLPurifier_Config;

class ContentSanitizer
{
    public function plainText($content): string
    {
        if (! is_string($content) || $content === '') {
            return '';
        }

        return trim(strip_tags($content));
    }

    public function saveHtml($content)
    {
        if (! is_string($content) || $content === '') {
            return '';
        }

        $config = HTMLPurifier_Config::createDefault();
        $config->set(
            'HTML.Allowed',
            'p,br,a[href|title],img[src|alt|title|width|height],strong,em,b,i,ul,ol,li,blockquote'
        );
        $config->set('URI.AllowedSchemes', [
            'http' => true,
            'https' => true,
            'mailto' => true,
        ]);
        $config->set('HTML.Nofollow', true);
        $config->set('HTML.TargetBlank', true);

        $purifier = new HTMLPurifier($config);

        return $purifier->purify($content);
    }
}
