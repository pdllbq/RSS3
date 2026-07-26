<?php

namespace Tests\Unit;

use App\Domain\Feed\Actions\GetFeedItemImageUrlAction;
use PHPUnit\Framework\TestCase;

class GetFeedItemImageUrlTest extends TestCase
{
    public function test_it_returns_media_thumbnail_url_first(): void
    {
        $item = new FakeFeedItem(
            thumbnail: ['url' => 'https://example.com/simplepie-thumbnail.jpg'],
            tags: [
                'http://search.yahoo.com/mrss/|thumbnail' => [
                    ['attribs' => ['' => ['url' => 'https://example.com/thumb.jpg']]],
                ],
            ],
            content: '<p><img src="https://example.com/content.jpg"></p>',
        );

        $this->assertSame(
            'https://example.com/simplepie-thumbnail.jpg',
            (new GetFeedItemImageUrlAction)->execute($item),
        );
    }

    public function test_it_returns_image_enclosure_url(): void
    {
        $item = new FakeFeedItem(enclosures: [
            new FakeEnclosure(link: 'https://example.com/audio.mp3', type: 'audio/mpeg'),
            new FakeEnclosure(link: 'https://example.com/photo.webp', type: 'image/webp'),
        ]);

        $this->assertSame(
            'https://example.com/photo.webp',
            (new GetFeedItemImageUrlAction)->execute($item),
        );
    }

    public function test_it_resolves_relative_image_from_content(): void
    {
        $item = new FakeFeedItem(
            link: 'https://example.com/news/posts/article.html',
            content: '<p><img src="../images/preview.jpg"></p>',
        );

        $this->assertSame(
            'https://example.com/news/images/preview.jpg',
            (new GetFeedItemImageUrlAction)->execute($item),
        );
    }

    public function test_it_returns_lazy_loaded_image_from_content(): void
    {
        $item = new FakeFeedItem(
            link: 'https://example.com/posts/article.html',
            content: '<p><img data-src="/images/lazy.jpg"></p>',
        );

        $this->assertSame(
            'https://example.com/images/lazy.jpg',
            (new GetFeedItemImageUrlAction)->execute($item),
        );
    }

    public function test_it_returns_first_srcset_image_from_content(): void
    {
        $item = new FakeFeedItem(
            link: 'https://example.com/posts/article.html',
            content: '<p><img srcset="/images/small.jpg 480w, /images/large.jpg 960w"></p>',
        );

        $this->assertSame(
            'https://example.com/images/small.jpg',
            (new GetFeedItemImageUrlAction)->execute($item),
        );
    }

    public function test_it_returns_null_without_supported_image_url(): void
    {
        $item = new FakeFeedItem(
            content: '<p><img src="data:image/png;base64,abc"></p>',
            enclosures: [new FakeEnclosure(link: 'https://example.com/audio.mp3', type: 'audio/mpeg')],
        );

        $this->assertNull((new GetFeedItemImageUrlAction)->execute($item));
    }
}

class FakeFeedItem
{
    public function __construct(
        private array $tags = [],
        private array $enclosures = [],
        private ?FakeEnclosure $enclosure = null,
        private ?string $content = null,
        private ?string $description = null,
        private ?string $link = null,
        private ?string $permalink = null,
        private array|string|null $thumbnail = null,
    ) {}

    public function get_item_tags(string $namespace, string $tag): ?array
    {
        return $this->tags[$namespace.'|'.$tag] ?? null;
    }

    public function get_enclosures(): array
    {
        return $this->enclosures;
    }

    public function get_enclosure(): ?FakeEnclosure
    {
        return $this->enclosure;
    }

    public function get_content(): ?string
    {
        return $this->content;
    }

    public function get_description(): ?string
    {
        return $this->description;
    }

    public function get_link(): ?string
    {
        return $this->link;
    }

    public function get_permalink(): ?string
    {
        return $this->permalink;
    }

    public function get_thumbnail(): array|string|null
    {
        return $this->thumbnail;
    }
}

class FakeEnclosure
{
    public function __construct(
        private ?string $link = null,
        private ?string $type = null,
        private ?string $medium = null,
        private ?string $thumbnail = null,
        private array $thumbnails = [],
    ) {}

    public function get_link(): ?string
    {
        return $this->link;
    }

    public function get_type(): ?string
    {
        return $this->type;
    }

    public function get_real_type(): ?string
    {
        return $this->type;
    }

    public function get_medium(): ?string
    {
        return $this->medium;
    }

    public function get_thumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function get_thumbnails(): array
    {
        return $this->thumbnails;
    }
}
