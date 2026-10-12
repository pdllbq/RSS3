{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
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
                <link>{{ route('outbound.go', ['id' => $item->id]) }}</link>
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
                <enclosure url="{{ $item->rss_image_url }}" length="0" type="{{ $item->rss_image_mime_type }}" />
                <media:content url="{{ $item->rss_image_url }}" medium="image" type="{{ $item->rss_image_mime_type }}" />
            </item>
        @endforeach
    </channel>
</rss>
