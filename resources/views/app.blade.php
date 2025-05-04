<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ config('app.description', 'En iyi tur deneyimleri ve geziler için tur sitemizi keşfedin. Özel turlar, grup gezileri ve daha fazlası.') }}">
    <meta name="keywords" content="tur, gezi, seyahat, tatil, rezervasyon, kapadokya, pamukkale, efes">
    <meta name="author" content="{{ config('app.name', 'Tur Projesi') }}">
    <meta name="robots" content="index, follow">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ config('app.name', 'Tur Projesi') }}">
    <meta property="og:description" content="{{ config('app.description', 'En iyi tur deneyimleri ve geziler için tur sitemizi keşfedin. Özel turlar, grup gezileri ve daha fazlası.') }}">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ config('app.name', 'Tur Projesi') }}">
    <meta property="twitter:description" content="{{ config('app.description', 'En iyi tur deneyimleri ve geziler için tur sitemizi keşfedin. Özel turlar, grup gezileri ve daha fazlası.') }}">
    <meta property="twitter:image" content="{{ asset('images/og-image.jpg') }}">
    
    <title>{{ config('app.name', 'Tur Projesi') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="preload" href="{{ asset('favicon.ico') }}" as="image" type="image/x-icon">
    
    <!-- Fonts -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" as="style">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Structured Data / Schema.org -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "TravelAgency",
        "name": "{{ config('app.name', 'Tur Projesi') }}",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('logo.png') }}",
        "description": "{{ config('app.description', 'En iyi tur deneyimleri ve geziler için tur sitemizi keşfedin. Özel turlar, grup gezileri ve daha fazlası.') }}",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "İstanbul",
            "addressRegion": "İstanbul",
            "addressCountry": "TR"
        },
        "telephone": "+90-123-456-7890"
    }
    </script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app"></div>
</body>
</html> 