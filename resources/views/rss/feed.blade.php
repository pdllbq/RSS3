{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ $title }}</title>
        <link>{{ $link }}</link>
        <atom:link href="{{ $selfLink }}" rel="self" type="application/rss+xml" />
        <description>{{ $description }}</description>
        <language>{{ $language }}</language>
        <lastBuildDate>{{ $lastBuildDate->toRssString() }}</lastBuildDate>

        @foreach ($items as $item)
            <item>
                <title>{{ $item->title }}</title>
                <link>{{ $item->url }}</link>
                <guid>{{ $item->guid ?: $item->url }}</guid>
                @if ($item->published_at)
                    <pubDate>{{ $item->published_at->toRssString() }}</pubDate>
                @endif
                @if ($item->description)
                    <description>{{ $item->description }}</description>
                @endif
                @if ($item->feedSource)
                    <source>{{ $item->feedSource->name() }}</source>
                @endif
            </item>
        @endforeach
    </channel>
</rss>
