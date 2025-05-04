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
    
    <!-- Styles -->
    <link href="{{ asset('build/assets/app-[hash].css') }}" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="{{ asset('build/assets/app-[hash].js') }}" defer></script>
</head>
<body class="font-sans antialiased bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Navbar -->
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="container mx-auto px-4 py-4">
                <div class="flex justify-between items-center">
                    <a href="{{ url('/') }}" class="text-xl font-bold text-primary-600">
                        {{ config('app.name', 'Tur Projesi') }}
                    </a>
                    
                    <nav class="flex items-center space-x-4">
                        <a href="{{ url('/') }}" class="text-gray-600 hover:text-primary-600 dark:text-gray-300 dark:hover:text-primary-500">
                            Ana Sayfa
                        </a>
                        <a href="{{ url('/tours') }}" class="text-gray-600 hover:text-primary-600 dark:text-gray-300 dark:hover:text-primary-500">
                            Turlar
                        </a>
                        @auth
                            <a href="{{ url('/favorites') }}" class="text-gray-600 hover:text-primary-600 dark:text-gray-300 dark:hover:text-primary-500">
                                Favorilerim
                            </a>
                            <div class="relative">
                                <button class="flex items-center text-gray-600 hover:text-primary-600 dark:text-gray-300 dark:hover:text-primary-500">
                                    {{ Auth::user()->name }}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-10 hidden">
                                    <a href="{{ url('/profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                        Profil
                                    </a>
                                    <form method="POST" action="{{ url('/logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                            Çıkış Yap
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ url('/login') }}" class="text-gray-600 hover:text-primary-600 dark:text-gray-300 dark:hover:text-primary-500">
                                Giriş Yap
                            </a>
                            <a href="{{ url('/register') }}" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors">
                                Kayıt Ol
                            </a>
                        @endauth
                    </nav>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-grow">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-100 dark:bg-gray-800 py-8">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row justify-between">
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ config('app.name', 'Tur Projesi') }}</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">
                            En iyi tur deneyimleri ve geziler için tur sitemizi keşfedin.<br>
                            Özel turlar, grup gezileri ve daha fazlası.
                        </p>
                    </div>
                    <div class="flex space-x-8">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Hızlı Linkler</h4>
                            <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                                <li><a href="{{ url('/') }}" class="hover:text-primary-600 dark:hover:text-primary-500">Ana Sayfa</a></li>
                                <li><a href="{{ url('/tours') }}" class="hover:text-primary-600 dark:hover:text-primary-500">Turlar</a></li>
                                <li><a href="{{ url('/about') }}" class="hover:text-primary-600 dark:hover:text-primary-500">Hakkımızda</a></li>
                                <li><a href="{{ url('/contact') }}" class="hover:text-primary-600 dark:hover:text-primary-500">İletişim</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Hesap</h4>
                            <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                                @auth
                                    <li><a href="{{ url('/profile') }}" class="hover:text-primary-600 dark:hover:text-primary-500">Profil</a></li>
                                    <li><a href="{{ url('/favorites') }}" class="hover:text-primary-600 dark:hover:text-primary-500">Favorilerim</a></li>
                                    <li><a href="{{ url('/reservations') }}" class="hover:text-primary-600 dark:hover:text-primary-500">Rezervasyonlarım</a></li>
                                @else
                                    <li><a href="{{ url('/login') }}" class="hover:text-primary-600 dark:hover:text-primary-500">Giriş Yap</a></li>
                                    <li><a href="{{ url('/register') }}" class="hover:text-primary-600 dark:hover:text-primary-500">Kayıt Ol</a></li>
                                @endauth
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700 mt-6 pt-6 flex flex-col md:flex-row justify-between items-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        &copy; {{ date('Y') }} {{ config('app.name', 'Tur Projesi') }}. Tüm hakları saklıdır.
                    </p>
                    <div class="flex space-x-4 mt-4 md:mt-0">
                        <a href="#" class="text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M16.5 0h-9c-4.1 0-7.5 3.4-7.5 7.5v9c0 4.1 3.4 7.5 7.5 7.5h9c4.1 0 7.5-3.4 7.5-7.5v-9c0-4.1-3.4-7.5-7.5-7.5zm5.25 16.5c0 2.9-2.35 5.25-5.25 5.25h-9c-2.9 0-5.25-2.35-5.25-5.25v-9c0-2.9 2.35-5.25 5.25-5.25h9c2.9 0 5.25 2.35 5.25 5.25v9z"/>
                                <path d="M12 5.76c-3.45 0-6.24 2.79-6.24 6.24s2.79 6.24 6.24 6.24 6.24-2.79 6.24-6.24-2.79-6.24-6.24-6.24zm0 10.5c-2.34 0-4.26-1.92-4.26-4.26s1.92-4.26 4.26-4.26 4.26 1.92 4.26 4.26-1.92 4.26-4.26 4.26z"/>
                                <circle cx="18.4" cy="5.6" r="1.2"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html> 