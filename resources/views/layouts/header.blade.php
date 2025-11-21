@php
    $user = Auth::user();
    $user = $user ? [
        'profile_picture' => $user->profile_picture ?? 'default.png',
        'full_name' => $user->full_name ?? 'Unknown User',
        'user_position' => $user->user_position ?? '',
        'user_dept' => $user->user_dept ?? ''
    ] : [
        'profile_picture' => 'default.png',
        'full_name' => 'Unknown User',
        'user_position' => '',
        'user_dept' => ''
    ];
@endphp

<div class="flex flex-col md:flex-row justify-between md:items-center gap-4 md:gap-10 header mb-6">
    
    <div class="flex flex-col gap-1 md:gap-5 greetings">
        <h1 class="page-title font-bold text-2xl text-gray-800">@yield('title', 'Dashboard')</h1>
    </div>
    
    <div class="flex gap-3 md:gap-5 items-center justify-between md:justify-end w-full md:w-auto">
        
        <div class="live-timestamp text-gray-600 text-sm font-medium" id="live-timestamp"></div>
        
        <div class="relative menu-container">
            
            <button onclick="toggleHeaderMenu(event)" class="bento-menu p-2 hover:bg-gray-100 rounded-lg transition-colors focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-700">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M5 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                    <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                    <path d="M19 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                    <path d="M5 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                    <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                    <path d="M19 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                    <path d="M5 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                    <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                    <path d="M19 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                </svg>
            </button>
            
            <div id="header-dropdown" class="hidden absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-xl shadow-lg z-50 overflow-hidden">
                
                <a href="#" class="flex gap-3 items-center px-4 py-3 hover:bg-gray-50 text-sm text-gray-700 transition-colors border-b border-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M4 21v-15c0 -1 1 -2 2 -2h5c1 0 2 1 2 2v15" />
                        <path d="M16 8h2c1 0 2 1 2 2v11" />
                        <path d="M3 21h18" />
                        <path d="M10 12v0" /><path d="M10 16v0" /><path d="M10 8v0" />
                        <path d="M7 12v0" /><path d="M7 16v0" /><path d="M7 8v0" />
                        <path d="M17 12v0" /><path d="M17 16v0" /><path d="M17 8v0" />
                    </svg>
                    Company Settings
                </a>

                <a href="#" class="flex gap-3 items-center px-4 py-3 hover:bg-gray-50 text-sm text-gray-700 transition-colors border-b border-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                    </svg>
                    Profile Settings
                </a>

                <a href="{{ route('logout') }}" class="flex gap-3 items-center px-4 py-3 hover:bg-red-50 text-sm text-red-600 transition-colors" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                        <path d="M9 12h12l-3 -3" />
                        <path d="M18 15l3 -3" />
                    </svg>
                    Logout
                </a>
            </div>
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
    function toggleHeaderMenu(event) {
        // Stop the click from bubbling up to the document immediately
        event.stopPropagation();
        
        const menu = document.getElementById('header-dropdown');
        
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
        } else {
            menu.classList.add('hidden');
        }
    }

    // Close the menu if the user clicks anywhere else on the page
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('header-dropdown');
        const button = document.querySelector('.bento-menu');
        
        // If menu is open AND click is NOT inside menu AND click is NOT on the button
        if (!menu.classList.contains('hidden') && !menu.contains(event.target) && !button.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
</script>