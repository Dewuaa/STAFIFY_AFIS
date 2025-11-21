<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <!-- Primary Meta Tags -->
    <title>@yield('title', 'Dashboard') - Stafify AFIS</title>
    <meta name="title" content="@yield('title', 'Dashboard') - Stafify AFIS">
    <meta name="description" content="@yield('description', 'Stafify AFIS - Professional Customer Relationship Management System')">
    <meta name="keywords" content="@yield('keywords', 'AFIS, customer relationship management, sales, leads, deals, contacts, business management')">
    <meta name="author" content="Stafify">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Dashboard') - Stafify AFIS">
    <meta property="og:description" content="@yield('description', 'Stafify AFIS - Professional Customer Relationship Management System')">
    <meta property="og:site_name" content="Stafify AFIS">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'Dashboard') - Stafify AFIS">
    <meta property="twitter:description" content="@yield('description', 'Stafify AFIS - Professional Customer Relationship Management System')">
    
    <!-- Favicon and other assets -->
    <link rel="shortcut icon" type="image/jpg" href="{{ asset('assets/images/Stafify Favicon.png') }}"/>
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/globals.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @stack('styles')
    
    <!-- Scripts -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        table th, td, p {
            font-family: 'Quicksand', sans-serif !important;
        }
        .material-icons {
            font-family: 'Material Icons' !important;
            font-weight: normal;
            font-style: normal;
            font-size: 24px; /* Default size */
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;
        }
        /* TIMESTAMP STYLES */
        .live-timestamp {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-light, #6b7280);
            margin-top: 0; /* Removed fixed margin, layout handles spacing */
            white-space: nowrap; /* Desktop: Keep on one line */
            transition: all 0.2s ease;
        }

        /* RESPONSIVE OVERRIDES */
        @media (max-width: 768px) {
            .page-title-header {
                margin: 0 16px 16px 16px;
            }

            .live-timestamp {
                white-space: normal; /* Allow text to wrap on mobile */
                font-size: 0.75rem;  /* Smaller font size */
                text-align: left;    /* Align left to match title */
                line-height: 1.3;    /* Better readability if wrapped */
                max-width: 70%;      /* Prevent it from hitting the menu button */
            }
        }
    </style>
</head>
<body class="bg-light">
    @include('layouts.sidebar')
    
    <div class="main-content">
        @include('layouts.mobile-menu')
        @include('layouts.header')
        
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/mobile-menu.js') }}"></script>
    <script src="https://unpkg.com/@material-tailwind/html@latest/scripts/script-name.js"></script>
    
    @stack('scripts')

    <script>
        function updateLiveTimestamp() {
            const timestampEl = document.getElementById('live-timestamp');
            if (!timestampEl) return; 

            const now = new Date();

            const dayName = now.toLocaleDateString('en-US', { weekday: 'long' });
            const month = now.toLocaleDateString('en-US', { month: 'long' });
            const day = now.getDate();
            const year = now.getFullYear();
            const time = now.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit', 
                hour12: true 
            });

            timestampEl.textContent = `${dayName}, ${month} ${day}, ${year} at ${time}`;
        }
        setInterval(updateLiveTimestamp, 1000); 
        updateLiveTimestamp();
    </script>
</body>
</html>
