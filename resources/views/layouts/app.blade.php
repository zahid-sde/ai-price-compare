<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AI Price Compare - Subscription Pricing & Feature Comparison')</title>
    <meta name="description" content="@yield('meta_description', 'Compare subscription pricing, features, free plans, and verified official prices for ChatGPT, Claude, Gemini, Grok, Perplexity, and Copilot in USA, Australia, India, and UK.')">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('title', 'AI Price Compare - Subscription Pricing & Feature Comparison')">
    <meta property="og:description" content="@yield('meta_description', 'Compare AI subscription prices and features transparently from verified official sources.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/logo.jpg') }}">

    <!-- Styles -->
    @vite(['resources/css/app.css'])
</head>
<body>

    <!-- Header Navigation -->
    <nav class="navbar">
        <a href="{{ route('home') }}" class="nav-brand" style="display: flex; align-items: center; gap: 0.6rem; text-decoration: none;">
            <img src="{{ asset('images/logo.jpg') }}" alt="AI Price Compare Logo" style="width: 32px; height: 32px; border-radius: 8px; object-fit: cover; border: 1px solid rgba(56, 189, 248, 0.4);">
            <span>AI Price Compare</span>
            <span class="brand-badge">MVP</span>
        </a>

        <ul class="nav-links">
            <li>
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    Home
                </a>
            </li>
            <li>
                <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    AI Tools
                </a>
            </li>
            <li>
                <a href="{{ route('compare.index') }}" class="nav-link {{ request()->routeIs('compare.*') ? 'active' : '' }}">
                    Compare Matrix
                </a>
            </li>
            <li>
                <a href="{{ route('finder.index') }}" class="nav-link {{ request()->routeIs('finder.*') ? 'active' : '' }}">
                    🤖 AI Finder
                </a>
            </li>
            <li>
                <a href="{{ route('calculator.index') }}" class="nav-link {{ request()->routeIs('calculator.*') ? 'active' : '' }}">
                    🧮 ROI Calculator
                </a>
            </li>
            <li>
                <a href="{{ route('deals.index') }}" class="nav-link {{ request()->routeIs('deals.*') ? 'active' : '' }}">
                    🎓 Student Deals
                </a>
            </li>
            <li>
                <a href="{{ route('image_to_video.index') }}" class="nav-link {{ request()->routeIs('image_to_video.*') ? 'active' : '' }}">
                    🎥 Image to Video
                </a>
            </li>
            <li>
                <a href="{{ route('image_to_image.index') }}" class="nav-link {{ request()->routeIs('image_to_image.*') ? 'active' : '' }}">
                    🖼️ Image to Image
                </a>
            </li>
        </ul>

        <!-- Country Selector -->
        <div style="display: flex; align-items: center; gap: 1rem;">
            @php
                $countries = \App\Models\Country::where('is_active', true)->get();
                $currentCountryId = session('user_country_id', \App\Models\Country::defaultCountry()->id);
            @endphp
            <form action="{{ route('country.switch') }}" method="POST" class="country-form">
                @csrf
                <select name="country_id" onchange="this.form.submit()" class="country-select">
                    @foreach($countries as $c)
                        @php
                            $flag = match($c->code) {
                                'USA' => '🇺🇸',
                                'AU' => '🇦🇺',
                                'IN' => '🇮🇳',
                                'UK' => '🇬🇧',
                                default => '🌍',
                            };
                        @endphp
                        <option value="{{ $c->id }}" {{ $c->id == $currentCountryId ? 'selected' : '' }}>
                            {{ $flag }} {{ $c->name }} ({{ $c->currency_symbol }})
                        </option>
                    @endforeach
                </select>
            </form>

            <a href="{{ route('admin.login') }}" style="font-size: 0.8rem; color: var(--text-dim);" title="Admin Portal">
                🔒 Admin
            </a>
        </div>
    </nav>

    <!-- Flash Alerts -->
    @if(session('success'))
        <div style="max-width: 1280px; margin: 1rem auto 0; padding: 0.75rem 1.5rem; background: rgba(52, 211, 153, 0.15); border: 1px solid var(--success); color: var(--success); border-radius: var(--radius-sm); font-size: 0.9rem;">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="max-width: 1280px; margin: 1rem auto 0; padding: 0.75rem 1.5rem; background: rgba(248, 113, 113, 0.15); border: 1px solid var(--danger); color: var(--danger); border-radius: var(--radius-sm); font-size: 0.9rem;">
            ⚠ {{ session('error') }}
        </div>
    @endif

    <!-- Main View Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p style="margin-bottom: 0.5rem; font-weight: 500;">
            AI Price Compare &copy; {{ date('Y') }} — Verified Subscription Intelligence
        </p>
        <p style="font-size: 0.8rem; color: var(--text-dim); max-width: 600px; margin: 0 auto;">
            Pricing and feature data are periodically verified against official product pricing pages. We never invent or estimate pricing.
        </p>
    </footer>

</body>
</html>
