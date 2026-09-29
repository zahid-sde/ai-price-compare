{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemap.org/schemas/sitemap/0.9">
    @foreach($staticUrls as $url)
        <url>
            <loc>{{ $url }}</loc>
            <changefreq>daily</changefreq>
            <priority>1.0</priority>
        </url>
    @endforeach

    @foreach($productUrls as $url)
        <url>
            <loc>{{ $url }}</loc>
            <changefreq>daily</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach

    @foreach($categoryUrls as $url)
        <url>
            <loc>{{ $url }}</loc>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach

    @foreach($compareUrls as $url)
        <url>
            <loc>{{ $url }}</loc>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
</urlset>
