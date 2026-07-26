<?php

namespace App\Domain\Feed\Actions;

use DOMDocument;

class GetFeedItemImageUrlAction
{
    private const MEDIA_RSS_NAMESPACES = [
        'http://search.yahoo.com/mrss/',
        'http://search.yahoo.com/mrss',
    ];

    private const ITUNES_NAMESPACES = [
        'http://www.itunes.com/dtds/podcast-1.0.dtd',
    ];

    public function execute($feedItem): ?string
    {
        return $this->firstUrl([
            ...$this->itemThumbnailUrls($feedItem),
            ...$this->mediaThumbnailUrls($feedItem),
            ...$this->enclosureThumbnailUrls($feedItem),
            ...$this->imageEnclosureUrls($feedItem),
            ...$this->mediaContentUrls($feedItem),
            ...$this->itunesImageUrls($feedItem),
            $this->firstImageFromHtml($this->call($feedItem, 'get_content')),
            $this->firstImageFromHtml($this->call($feedItem, 'get_description')),
        ], $this->baseUrl($feedItem));
    }

    private function itemThumbnailUrls($feedItem): array
    {
        $thumbnail = $this->call($feedItem, 'get_thumbnail');

        if (is_array($thumbnail)) {
            return [$thumbnail['url'] ?? null];
        }

        return [$thumbnail];
    }

    private function mediaThumbnailUrls($feedItem): array
    {
        return $this->tagAttributeUrls($feedItem, self::MEDIA_RSS_NAMESPACES, 'thumbnail', ['url']);
    }

    private function mediaContentUrls($feedItem): array
    {
        $urls = [];

        foreach ($this->itemTags($feedItem, self::MEDIA_RSS_NAMESPACES, 'content') as $tag) {
            $attributes = $this->attributes($tag);
            $medium = strtolower((string) ($attributes['medium'] ?? ''));
            $type = strtolower((string) ($attributes['type'] ?? ''));

            if ($medium !== 'image' && ! str_starts_with($type, 'image/')) {
                continue;
            }

            $urls[] = $attributes['url'] ?? null;
        }

        return $urls;
    }

    private function itunesImageUrls($feedItem): array
    {
        return $this->tagAttributeUrls($feedItem, self::ITUNES_NAMESPACES, 'image', ['href', 'url']);
    }

    private function enclosureThumbnailUrls($feedItem): array
    {
        $urls = [];

        foreach ($this->enclosures($feedItem) as $enclosure) {
            $urls[] = $this->call($enclosure, 'get_thumbnail');

            $thumbnails = $this->call($enclosure, 'get_thumbnails');

            if (! is_array($thumbnails)) {
                continue;
            }

            foreach ($thumbnails as $thumbnail) {
                $urls[] = is_string($thumbnail)
                    ? $thumbnail
                    : $this->call($thumbnail, 'get_link');
            }
        }

        return $urls;
    }

    private function imageEnclosureUrls($feedItem): array
    {
        $urls = [];

        foreach ($this->enclosures($feedItem) as $enclosure) {
            $medium = strtolower((string) $this->call($enclosure, 'get_medium'));
            $type = strtolower((string) ($this->call($enclosure, 'get_type') ?: $this->call($enclosure, 'get_real_type')));

            if ($medium !== 'image' && ! str_starts_with($type, 'image/')) {
                continue;
            }

            $urls[] = $this->call($enclosure, 'get_link');
        }

        return $urls;
    }

    private function tagAttributeUrls($feedItem, array $namespaces, string $tagName, array $attributeNames): array
    {
        $urls = [];

        foreach ($this->itemTags($feedItem, $namespaces, $tagName) as $tag) {
            $attributes = $this->attributes($tag);

            foreach ($attributeNames as $attributeName) {
                $urls[] = $attributes[$attributeName] ?? null;
            }
        }

        return $urls;
    }

    private function itemTags($feedItem, array $namespaces, string $tagName): array
    {
        $tags = [];

        if (! is_callable([$feedItem, 'get_item_tags'])) {
            return $tags;
        }

        foreach ($namespaces as $namespace) {
            $namespaceTags = $feedItem->get_item_tags($namespace, $tagName);

            if (is_array($namespaceTags)) {
                $tags = array_merge($tags, $namespaceTags);
            }
        }

        return $tags;
    }

    private function attributes(array $tag): array
    {
        $attributes = [];

        foreach (($tag['attribs'] ?? []) as $namespaceAttributes) {
            if (is_array($namespaceAttributes)) {
                $attributes = array_merge($attributes, $namespaceAttributes);
            }
        }

        return $attributes;
    }

    private function enclosures($feedItem): array
    {
        $enclosures = $this->call($feedItem, 'get_enclosures');

        if (is_array($enclosures) && $enclosures !== []) {
            return $enclosures;
        }

        $enclosure = $this->call($feedItem, 'get_enclosure');

        return $enclosure ? [$enclosure] : [];
    }

    private function firstImageFromHtml($html): ?string
    {
        if (! is_string($html) || trim($html) === '' || ! class_exists(DOMDocument::class)) {
            return null;
        }

        $previous = libxml_use_internal_errors(true);
        $document = new DOMDocument;
        $document->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'.$html);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        foreach ($document->getElementsByTagName('img') as $image) {
            foreach (['src', 'data-src', 'data-original', 'data-lazy-src'] as $attribute) {
                $src = $image->getAttribute($attribute);

                if ($src !== '') {
                    return $src;
                }
            }

            $srcset = $image->getAttribute('srcset') ?: $image->getAttribute('data-srcset');

            if ($srcset !== '') {
                return $this->firstSrcsetUrl($srcset);
            }
        }

        return null;
    }

    private function firstSrcsetUrl(string $srcset): ?string
    {
        foreach (explode(',', $srcset) as $candidate) {
            $url = trim(explode(' ', trim($candidate))[0] ?? '');

            if ($url !== '') {
                return $url;
            }
        }

        return null;
    }

    private function firstUrl(array $candidates, ?string $baseUrl): ?string
    {
        foreach ($candidates as $candidate) {
            $url = $this->normalizeUrl($candidate, $baseUrl);

            if ($url !== null) {
                return $url;
            }
        }

        return null;
    }

    private function normalizeUrl($url, ?string $baseUrl): ?string
    {
        if (! is_string($url) || trim($url) === '') {
            return null;
        }

        $url = html_entity_decode(trim($url), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        if (str_starts_with($url, '//')) {
            $scheme = parse_url((string) $baseUrl, PHP_URL_SCHEME) ?: 'https';
            $url = $scheme.':'.$url;
        } elseif (! parse_url($url, PHP_URL_SCHEME)) {
            $url = $this->resolveRelativeUrl($url, $baseUrl);
        }

        if (! is_string($url) || ! preg_match('/^https?:\/\//i', $url)) {
            return null;
        }

        return filter_var($url, FILTER_VALIDATE_URL) ? $url : null;
    }

    private function resolveRelativeUrl(string $url, ?string $baseUrl): ?string
    {
        if (! $baseUrl || ! filter_var($baseUrl, FILTER_VALIDATE_URL)) {
            return null;
        }

        $base = parse_url($baseUrl);

        if (! isset($base['scheme'], $base['host'])) {
            return null;
        }

        $authority = $base['scheme'].'://'.$base['host'].(isset($base['port']) ? ':'.$base['port'] : '');

        if (str_starts_with($url, '/')) {
            return $authority.$url;
        }

        $path = $base['path'] ?? '/';
        $directory = preg_replace('#/[^/]*$#', '/', $path) ?: '/';

        return $authority.$this->normalizePath($directory.$url);
    }

    private function normalizePath(string $path): string
    {
        $segments = [];

        foreach (explode('/', $path) as $segment) {
            if ($segment === '' || $segment === '.') {
                continue;
            }

            if ($segment === '..') {
                array_pop($segments);

                continue;
            }

            $segments[] = $segment;
        }

        return '/'.implode('/', $segments);
    }

    private function baseUrl($feedItem): ?string
    {
        return $this->call($feedItem, 'get_link') ?: $this->call($feedItem, 'get_permalink');
    }

    private function call($object, string $method)
    {
        return is_object($object) && is_callable([$object, $method])
            ? $object->{$method}()
            : null;
    }
}
