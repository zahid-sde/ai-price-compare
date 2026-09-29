<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - AI Price Compare</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css'])
</head>
<body style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background: var(--bg-dark);">

    <div class="glass-card" style="width: 100%; max-width: 400px; padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <a href="{{ route('home') }}" class="nav-brand" style="justify-content: center; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.6rem; text-decoration: none;">
                <img src="{{ asset('images/logo.jpg') }}" alt="AI Price Compare Logo" style="width: 32px; height: 32px; border-radius: 8px; object-fit: cover; border: 1px solid rgba(56, 189, 248, 0.4);">
                <span>AI Price Admin</span>
            </a>
            <p style="font-size: 0.85rem; color: var(--text-muted);">Sign in to manage AI products & pricing data</p>
        </div>

        @if($errors->any())
            <div style="margin-bottom: 1.5rem; padding: 0.75rem; background: rgba(248,113,113,0.15); border: 1px solid var(--danger); color: var(--danger); border-radius: var(--radius-sm); font-size: 0.85rem;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1.25rem;">
                <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Admin Email</label>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email', 'admin@aipricecompare.com') }}" 
                    class="country-select" 
                    style="width: 100%;" 
                    required 
                    autofocus
                >
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.35rem;">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    value="password" 
                    class="country-select" 
                    style="width: 100%;" 
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Login to Dashboard
            </button>
        </form>

        <p style="text-align: center; margin-top: 1.5rem; font-size: 0.8rem; color: var(--text-dim);">
            Default Admin Credentials: admin@aipricecompare.com / password
        </p>
    </div>

</body>
</html>
