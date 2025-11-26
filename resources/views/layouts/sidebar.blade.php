@php
    $authUser = Auth::user();
    $user = $authUser ? [
        'profile_picture' => $authUser->profile_picture ?? 'default.png',
        'full_name' => $authUser->full_name ?? 'Unknown User',
        'user_position' => $authUser->user_position ?? '',
        'user_dept' => $authUser->user_dept ?? ''
    ] : [
        'profile_picture' => 'default.png',
        'full_name' => 'Unknown User',
        'user_position' => '',
        'user_dept' => ''
    ];
@endphp


<style> 
    .hqdropdown {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 10px;
        font-size: 14px;
        transition: var(--transition);
        font-family: "Quicksand", sans-serif;
        box-sizing: border-box;
    }   

</style>

<div class="sidebar-container max-[767px]:hidden">
    <div class="sidebar">
        <nav id="sidebarMenu">
            <div class="sidebarLogo">
                <img src="https://www.stafify.com/cdn/shop/files/e50lj9u5c9xat9j7z3ne_752x.png?v=1613708232" class="menu-text site-logo" alt="Stafify Logo">
                <img src="https://res.cloudinary.com/dt1vbprub/image/upload/v1741661073/Stafify_Icon_onet8q.jpg" class="site-icon" alt="Stafify Icon">
            </div>

            <a href="#" class="flex gap-3 items-center card-profile">
                <div class="flex-shrink-0">
                    <img src="{{ asset('uploads/' . $user['profile_picture']) }}" alt="Profile Picture" class="w-9 h-9 rounded-full object-cover border border-gray-300">
                </div>
                <div class="details-profile menu-text">
                    <span class="profile-name">
                        <span id="username-display" class="profile-name">{{ $user['full_name'] }}</span>
                    </span>
                    <p class="profile-dept">
                        <span class="position-display">{{ $user['user_position'] }}</span>
                        <span class="separator"> - </span>
                        <span class="department-display">{{ $user['user_dept'] }}</span>
                    </p>
                </div>
            </a>

            <select class="hqdropdown" id="hq" name="hqselect">
                <option value="" selected disabled>Headquarters</option>
                <option value="Customer">Customer</option>
                <option value="Supplier">Supplier</option>
            </select>

            <ul class="flex flex-col gap-2 sidebarMenuItems">
                <li class="sidebarMenuItem">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-layout-grid"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M14 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M14 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /></svg>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>

                <li class="sidebarMenuItem has-dropdown">
                    <a href="#" class="justify-between nav-link" onclick="toggleDropdown(event, 'attendanceDropdown')">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-spreadsheet"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M8 11h8v7h-8z" /><path d="M8 15h8" /><path d="M11 11v7" /></svg>
                            <span class="menu-text">Accounting</span>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-chevron-down"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 9l6 6l6 -6" /></svg>
                    </a>
                    <ul class="dropdown attendanceDropdown desktop">
                        <li class="sidebarMenuItem">
                            <a href="{{ route('business.settings') }}" class="nav-link {{ request()->routeIs('business.settings') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-tax"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8.487 21h7.026a4 4 0 0 0 3.808 -5.224l-1.706 -5.306a5 5 0 0 0 -4.76 -3.47h-1.71a5 5 0 0 0 -4.76 3.47l-1.706 5.306a4 4 0 0 0 3.808 5.224" /><path d="M15 3q -1 4 -3 4t -3 -4z" /><path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" /><path d="M12 10v1" /><path d="M12 17v1" /></svg>
                                <span class="menu-text">Business Settings</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('capitalization') }}" class="nav-link {{ request()->routeIs('capitalization') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-database-dollar"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6c0 1.657 3.582 3 8 3s8 -1.343 8 -3s-3.582 -3 -8 -3s-8 1.343 -8 3" /><path d="M4 6v6c0 1.657 3.582 3 8 3c.415 0 .822 -.012 1.22 -.035" /><path d="M20 10v-4" /><path d="M4 12v6c0 1.657 3.582 3 8 3c.352 0 .698 -.009 1.037 -.025" /><path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" /><path d="M19 21v1m0 -8v1" /></svg>
                                <span class="menu-text">Capitalization</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('chart.of.accounts') }}" class="nav-link {{ request()->routeIs('chart.of.accounts') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chart-histogram"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3v18h18" /><path d="M20 18v3" /><path d="M16 16v5" /><path d="M12 13v8" /><path d="M8 16v5" /><path d="M3 11c6 0 5 -5 9 -5s3 5 9 5" /></svg>
                                <span class="menu-text">Chart of Accounts</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebarMenuItem has-dropdown">
                    <a href="#" class="justify-between nav-link" onclick="toggleDropdown(event, 'attendanceDropdown2')">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-right-left"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M21 7l-18 0" /><path d="M18 10l3 -3l-3 -3" /><path d="M6 20l-3 -3l3 -3" /><path d="M3 17l18 0" /></svg>
                            <span class="menu-text">Transactions</span>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icon-tabler-chevron-down"><path stroke="none" d="M0 0h24v24H0z"/><path d="M6 9l6 6l6 -6" /></svg>
                    </a>
                    <ul class="dropdown attendanceDropdown2 desktop" id="attendanceDropdown2">
                        <li class="sidebarMenuItem">
                            <a href="{{ route('revenue.logsheet') }}" class="nav-link {{ request()->routeIs('revenue.logsheet') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-book-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19 4v16h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12z" /><path d="M19 16h-12a2 2 0 0 0 -2 2" /><path d="M9 8h6" /></svg>
                                <span class="menu-text">Revenue Logsheet</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('expense.logsheet') }}" class="nav-link {{ request()->routeIs('expense.logsheet') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-dollar"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13 21h-7a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v3" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h12.5" /><path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" /><path d="M19 21v1m0 -8v1" /></svg>
                                <span class="menu-text">Expense Logsheet</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('journal.logsheet') }}" class="nav-link {{ request()->routeIs('journal.logsheet') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-report-money"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" /><path d="M12 17v1m0 -8v1" /></svg>
                                <span class="menu-text">Journal Logsheet</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('inventory.logsheet') }}" class="nav-link {{ request()->routeIs('inventory.logsheet') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-report-money"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" /><path d="M12 17v1m0 -8v1" /></svg>
                                <span class="menu-text">Inventory Logsheet</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <ul class="flex flex-col gap-2 sidebarMenuItems">
                    <li class="sidebarMenuItem">
                        <a href="{{ route('books.accounts') }}" class="nav-link {{ request()->routeIs('books.accounts') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-books"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" /><path d="M9 4m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" /><path d="M5 8h4" /><path d="M9 16h4" /><path d="M13.803 4.56l2.184 -.53c.562 -.135 1.133 .19 1.282 .732l3.695 13.418a1.02 1.02 0 0 1 -.634 1.219l-.133 .041l-2.184 .53c-.562 .135 -1.133 -.19 -1.282 -.732l-3.695 -13.418a1.02 1.02 0 0 1 .634 -1.219l.133 -.041z" /><path d="M14 9l4 -1" /><path d="M16 16l3.923 -.98" /></svg>
                            <span class="menu-text">Books of Accounts</span>
                        </a>
                    </li>

                    <li class="sidebarMenuItem has-dropdown">
                        <a href="#" class="justify-between nav-link" onclick="toggleDropdown(event, 'attendanceDropdown5')">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-cash-register"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M21 15h-2.5c-.398 0 -.779 .158 -1.061 .439c-.281 .281 -.439 .663 -.439 1.061c0 .398 .158 .779 .439 1.061c.281 .281 .663 .439 1.061 .439h1c.398 0 .779 .158 1.061 .439c.281 .281 .439 .663 .439 1.061c0 .398 -.158 .779 -.439 1.061c-.281 .281 -.663 .439 -1.061 .439h-2.5" /><path d="M19 21v1m0 -8v1" /><path d="M13 21h-7c-.53 0 -1.039 -.211 -1.414 -.586c-.375 -.375 -.586 -.884 -.586 -1.414v-10c0 -.53 .211 -1.039 .586 -1.414c.375 -.375 .884 -.586 1.414 -.586h2m12 3.12v-1.12c0 -.53 -.211 -1.039 -.586 -1.414c-.375 -.375 -.884 -.586 -1.414 -.586h-2" /><path d="M16 10v-6c0 -.53 -.211 -1.039 -.586 -1.414c-.375 -.375 -.884 -.586 -1.414 -.586h-4c-.53 0 -1.039 .211 -1.414 .586c-.375 .375 -.586 .884 -.586 1.414v6m8 0h-8m8 0h1m-9 0h-1" /><path d="M8 14v.01" /><path d="M8 17v.01" /><path d="M12 13.99v.01" /><path d="M12 17v.01" /></svg>
                                <span class="menu-text">Cash Management</span>
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-chevron-down"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 9l6 6l6 -6" /></svg>
                        </a>
                        <ul class="dropdown attendanceDropdown5 desktop">
                            <li class="sidebarMenuItem">
                                <a href="{{ route('bank.enrollment') }}" class="nav-link {{ request()->routeIs('bank.enrollment') ? 'active' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mail-down"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 19h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v5.5" /><path d="M19 16v6" /><path d="M22 19l-3 3l-3 -3" /><path d="M3 7l9 6l9 -6" /></svg>
                                    <span class="menu-text">Bank Enrollment</span>
                                </a>
                            </li>
                            <li class="sidebarMenuItem">
                                <a href="{{ route('bank.reconciliation') }}" class="nav-link {{ request()->routeIs('bank.reconciliation') ? 'active' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-pig-money"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 11v.01" /><path d="M5.173 8.378a3 3 0 1 1 4.656 -1.377" /><path d="M16 4v3.803a6.019 6.019 0 0 1 2.658 3.197h1.341a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-1.342c-.336 .95 -.907 1.8 -1.658 2.473v2.027a1.5 1.5 0 0 1 -3 0v-.583a6.04 6.04 0 0 1 -1 .083h-4a6.04 6.04 0 0 1 -1 -.083v.583a1.5 1.5 0 0 1 -3 0v-2l0 -.027a6 6 0 0 1 4 -10.473h2.5l4.5 -3h0z" /></svg>
                                    <span class="menu-text">Bank Reconciliation</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebarMenuItem has-dropdown">
                        <a href="#" class="justify-between nav-link" onclick="toggleDropdown(event, 'attendanceDropdown4')">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-credit-card-pay"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 19h-6a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v4.5" /><path d="M3 10h18" /><path d="M16 19h6" /><path d="M19 16l3 3l-3 3" /><path d="M7.005 15h.005" /><path d="M11 15h2" /></svg>
                                <span class="menu-text">Billing & Payments</span>
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-chevron-down"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 9l6 6l6 -6" /></svg>
                        </a>
                        <ul class="dropdown attendanceDropdown4 desktop">
                            <li class="sidebarMenuItem">
                                <a href="{{ route('billing.invoice') }}" class="nav-link {{ request()->routeIs('billing.invoice') ? 'active' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-speakerphone"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 8a3 3 0 0 1 0 6" /><path d="M10 8v11a1 1 0 0 1 -1 1h-1a1 1 0 0 1 -1 -1v-5" /><path d="M12 8h0l4.524 -3.77a.9 .9 0 0 1 1.476 .692v12.156a.9 .9 0 0 1 -1.476 .692l-4.524 -3.77h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1h8" /></svg>
                                    <span class="menu-text">E-Invoice</span>
                                </a>
                            </li>
                            <li class="sidebarMenuItem">
                                <a href="{{ route('billing.ack.receipt') }}" class="nav-link {{ request()->routeIs('billing.ack.receipt') ? 'active' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-receipt"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2m4 -14h6m-6 4h6m-2 4h2" /></svg>
                                    <span class="menu-text">Ack Receipt</span>
                                </a>
                            </li>
                            <li class="sidebarMenuItem">
                                <a href="{{ route('billing.petty.cash') }}" class="nav-link {{ request()->routeIs('billing.petty.cash') ? 'active' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-credit-card-refund"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 19h-6a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v4.5" /><path d="M3 10h18" /><path d="M7 15h.01" /><path d="M11 15h2" /><path d="M16 19h6" /><path d="M19 16l-3 3l3 3" /></svg>
                                    <span class="menu-text">Petty Cash Voucher</span>
                                </a>
                            </li>
                            <!-- <li class="sidebarMenuItem">
                                <a href="{{ route('tax.settings') }}"  class="nav-link {{ request()->routeIs('tax.settings') ? 'active' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 21h-6a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4.5" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M19 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M22 22a2 2 0 0 0 -2 -2h-2a2 2 0 0 0 -2 2" /></svg>
                                    <span class="menu-text">Tax Settings</span>
                                </a>
                            </li> -->
                        </ul>
                    </li>

                    <li class="sidebarMenuItem">
                        <a href="{{ route('ecommerce') }}" class="nav-link {{ request()->routeIs('ecommerce') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-basket"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M5.001 8h13.999a2 2 0 0 1 1.977 2.304l-1.255 7.152a3 3 0 0 1 -2.966 2.544h-9.512a3 3 0 0 1 -2.965 -2.544l-1.255 -7.152a2 2 0 0 1 1.977 -2.304z" /><path d="M17 10l-2 -6" /><path d="M7 10l2 -6" /></svg>
                            <span class="menu-text"> E-Commerce</span>
                        </a>
                    </li>

                    <li class="sidebarMenuItem">
                        <a href="{{ route('email.notification') }}" class="nav-link {{ request()->routeIs('email.notification') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mail-opened"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 9l9 6l9 -6l-9 -6l-9 6" /><path d="M21 9v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10" /><path d="M3 19l6 -6" /><path d="M15 13l6 6" /></svg>
                            <span class="menu-text">Email Notification</span>
                        </a>
                    </li>

                    <li class="sidebarMenuItem">
                        <button class="toggle-btn" onclick="toggleSidebar()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-layout-sidebar-left-collapse"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M9 4v16" /><path d="M15 10l-2 2l2 2" /></svg>
                            <span class="menu-text">Collapse Sidebar</span>
                        </button>
                    </li>
                </ul>
            </ul>

        </nav>

        <div class="flex max-[767px]:hidden flex-col items-start gap-3 poweredBy">
            <p>Powered By:</p>
            <img src="https://www.stafify.com/cdn/shop/files/e50lj9u5c9xat9j7z3ne_752x.png?v=1613708232" class="site-logo" alt="Stafify Logo">
        </div>
    </div>
</div>

<div class="sidebar-overlay-mobile"></div>

<div class="sidebar-container-mobile">
    <div class="sidebar-mobile">
        <div class="sidebarMenu-inner">
            <button class="sidebar-close-btn" style="color: #333">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
            </button>
            <nav id="sidebarMenu">
                <div class="sidebarLogo">
                    <img src="https://www.stafify.com/cdn/shop/files/e50lj9u5c9xat9j7z3ne_752x.png?v=1613708232" class="site-logo" alt="Stafify Logo">
                </div>
                <a href="#" class="flex gap-10 items-center card-profile">
                    <div class="image-profile">
                        <img src="{{ asset('uploads/' . $user['profile_picture']) }}" />
                    </div>
                    <div class="details-profile">
                        <span class="profile-name">{{ $user['full_name'] }}</span>
                        <p class="profile-dept">
                            {{ $user['user_dept'] }} <span class="separator">-</span> {{ $user['user_position'] }}
                        </p>
                    </div>
                </a>
                <ul class="flex flex-col gap-2 sidebarMenuItems">
                <li class="sidebarMenuItem">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-layout-grid"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M14 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M14 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /></svg>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>

                <li class="sidebarMenuItem has-dropdown">
                    <a href="#" class="justify-between nav-link" onclick="toggleDropdown(event, 'attendanceDropdownMobile')">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-spreadsheet"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M8 11h8v7h-8z" /><path d="M8 15h8" /><path d="M11 11v7" /></svg>
                            <span class="menu-text">Accounting</span>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-chevron-down"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 9l6 6l6 -6" /></svg>
                    </a>
                    <ul class="dropdown attendanceDropdownMobile mobile">
                        <li class="sidebarMenuItem">
                            <a href="{{ route('business.settings') }}" class="nav-link {{ request()->routeIs('business.settings') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-tax"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8.487 21h7.026a4 4 0 0 0 3.808 -5.224l-1.706 -5.306a5 5 0 0 0 -4.76 -3.47h-1.71a5 5 0 0 0 -4.76 3.47l-1.706 5.306a4 4 0 0 0 3.808 5.224" /><path d="M15 3q -1 4 -3 4t -3 -4z" /><path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" /><path d="M12 10v1" /><path d="M12 17v1" /></svg>
                                <span class="menu-text">Business Settings</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('capitalization') }}" class="nav-link {{ request()->routeIs('capitalization') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-database-dollar"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6c0 1.657 3.582 3 8 3s8 -1.343 8 -3s-3.582 -3 -8 -3s-8 1.343 -8 3" /><path d="M4 6v6c0 1.657 3.582 3 8 3c.415 0 .822 -.012 1.22 -.035" /><path d="M20 10v-4" /><path d="M4 12v6c0 1.657 3.582 3 8 3c.352 0 .698 -.009 1.037 -.025" /><path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" /><path d="M19 21v1m0 -8v1" /></svg>
                                <span class="menu-text">Capitalization</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('chart.of.accounts') }}" class="nav-link {{ request()->routeIs('chart.of.accounts') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chart-histogram"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3v18h18" /><path d="M20 18v3" /><path d="M16 16v5" /><path d="M12 13v8" /><path d="M8 16v5" /><path d="M3 11c6 0 5 -5 9 -5s3 5 9 5" /></svg>
                                <span class="menu-text">Chart of Accounts</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebarMenuItem has-dropdown">
                    <a href="#" class="justify-between nav-link" onclick="toggleDropdown(event, 'attendanceDropdown2Mobile')">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-right-left"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M21 7l-18 0" /><path d="M18 10l3 -3l-3 -3" /><path d="M6 20l-3 -3l3 -3" /><path d="M3 17l18 0" /></svg>
                            <span class="menu-text">Transactions</span>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icon-tabler-chevron-down"><path stroke="none" d="M0 0h24v24H0z"/><path d="M6 9l6 6l6 -6" /></svg>
                    </a>
                    <ul class="dropdown attendanceDropdown2Mobile mobile" id="attendanceDropdown2Mobile">
                        <li class="sidebarMenuItem">
                            <a href="{{ route('revenue.logsheet') }}" class="nav-link {{ request()->routeIs('revenue.logsheet') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-book-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19 4v16h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12z" /><path d="M19 16h-12a2 2 0 0 0 -2 2" /><path d="M9 8h6" /></svg>
                                <span class="menu-text">Revenue Logsheet</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('expense.logsheet') }}" class="nav-link {{ request()->routeIs('expense.logsheet') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-dollar"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13 21h-7a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v3" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h12.5" /><path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" /><path d="M19 21v1m0 -8v1" /></svg>
                                <span class="menu-text">Expense Logsheet</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('journal.logsheet') }}" class="nav-link {{ request()->routeIs('journal.logsheet') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-report-money"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" /><path d="M12 17v1m0 -8v1" /></svg>
                                <span class="menu-text">Journal Logsheet</span>
                            </a>
                        </li>
                        <li class="sidebarMenuItem">
                            <a href="{{ route('inventory.logsheet') }}" class="nav-link {{ request()->routeIs('inventory.logsheet') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-report-money"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" /><path d="M12 17v1m0 -8v1" /></svg>
                                <span class="menu-text">Inventory Logsheet</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <ul class="flex flex-col gap-2 sidebarMenuItems">
                    <li class="sidebarMenuItem">
                        <a href="{{ route('books.accounts') }}" class="nav-link {{ request()->routeIs('books.accounts') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-books"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" /><path d="M9 4m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" /><path d="M5 8h4" /><path d="M9 16h4" /><path d="M13.803 4.56l2.184 -.53c.562 -.135 1.133 .19 1.282 .732l3.695 13.418a1.02 1.02 0 0 1 -.634 1.219l-.133 .041l-2.184 .53c-.562 .135 -1.133 -.19 -1.282 -.732l-3.695 -13.418a1.02 1.02 0 0 1 .634 -1.219l.133 -.041z" /><path d="M14 9l4 -1" /><path d="M16 16l3.923 -.98" /></svg>
                            <span class="menu-text">Books of Accounts</span>
                        </a>
                    </li>

                    <li class="sidebarMenuItem has-dropdown">
                        <a href="#" class="justify-between nav-link" onclick="toggleDropdown(event, 'attendanceDropdown5Mobile')">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-cash-register"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M21 15h-2.5c-.398 0 -.779 .158 -1.061 .439c-.281 .281 -.439 .663 -.439 1.061c0 .398 .158 .779 .439 1.061c.281 .281 .663 .439 1.061 .439h1c.398 0 .779 .158 1.061 .439c.281 .281 .439 .663 .439 1.061c0 .398 -.158 .779 -.439 1.061c-.281 .281 -.663 .439 -1.061 .439h-2.5" /><path d="M19 21v1m0 -8v1" /><path d="M13 21h-7c-.53 0 -1.039 -.211 -1.414 -.586c-.375 -.375 -.586 -.884 -.586 -1.414v-10c0 -.53 .211 -1.039 .586 -1.414c.375 -.375 .884 -.586 1.414 -.586h2m12 3.12v-1.12c0 -.53 -.211 -1.039 -.586 -1.414c-.375 -.375 -.884 -.586 -1.414 -.586h-2" /><path d="M16 10v-6c0 -.53 -.211 -1.039 -.586 -1.414c-.375 -.375 -.884 -.586 -1.414 -.586h-4c-.53 0 -1.039 .211 -1.414 .586c-.375 .375 -.586 .884 -.586 1.414v6m8 0h-8m8 0h1m-9 0h-1" /><path d="M8 14v.01" /><path d="M8 17v.01" /><path d="M12 13.99v.01" /><path d="M12 17v.01" /></svg>
                                <span class="menu-text">Cash Management</span>
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-chevron-down"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 9l6 6l6 -6" /></svg>
                        </a>
                        <ul class="dropdown attendanceDropdown5Mobile mobile">
                            <li class="sidebarMenuItem">
                                <a href="{{ route('bank.enrollment') }}" class="nav-link {{ request()->routeIs('bank.enrollment') ? 'active' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mail-down"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 19h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v5.5" /><path d="M19 16v6" /><path d="M22 19l-3 3l-3 -3" /><path d="M3 7l9 6l9 -6" /></svg>
                                    <span class="menu-text">Bank Enrollment</span>
                                </a>
                            </li>
                            <li class="sidebarMenuItem">
                                <a href="{{ route('bank.reconciliation') }}" class="nav-link {{ request()->routeIs('bank.reconciliation') ? 'active' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-pig-money"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 11v.01" /><path d="M5.173 8.378a3 3 0 1 1 4.656 -1.377" /><path d="M16 4v3.803a6.019 6.019 0 0 1 2.658 3.197h1.341a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-1.342c-.336 .95 -.907 1.8 -1.658 2.473v2.027a1.5 1.5 0 0 1 -3 0v-.583a6.04 6.04 0 0 1 -1 .083h-4a6.04 6.04 0 0 1 -1 -.083v.583a1.5 1.5 0 0 1 -3 0v-2l0 -.027a6 6 0 0 1 4 -10.473h2.5l4.5 -3h0z" /></svg>
                                    <span class="menu-text">Bank Reconciliation</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebarMenuItem has-dropdown">
                        <a href="#" class="justify-between nav-link" onclick="toggleDropdown(event, 'attendanceDropdown4Mobile')">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-credit-card-pay"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 19h-6a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v4.5" /><path d="M3 10h18" /><path d="M16 19h6" /><path d="M19 16l3 3l-3 3" /><path d="M7.005 15h.005" /><path d="M11 15h2" /></svg>
                                <span class="menu-text">Billing & Payments</span>
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon-tabler icons-tabler-outline icon-tabler-chevron-down"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 9l6 6l6 -6" /></svg>
                        </a>
                        <ul class="dropdown attendanceDropdown4Mobile mobile">
                            <li class="sidebarMenuItem">
                                <a href="{{ route('billing.invoice') }}" class="nav-link {{ request()->routeIs('billing.invoice') ? 'active' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-speakerphone"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 8a3 3 0 0 1 0 6" /><path d="M10 8v11a1 1 0 0 1 -1 1h-1a1 1 0 0 1 -1 -1v-5" /><path d="M12 8h0l4.524 -3.77a.9 .9 0 0 1 1.476 .692v12.156a.9 .9 0 0 1 -1.476 .692l-4.524 -3.77h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1h8" /></svg>
                                    <span class="menu-text">E-Invoice</span>
                                </a>
                            </li>
                            <li class="sidebarMenuItem">
                                <a href="{{ route('billing.ack.receipt') }}" class="nav-link {{ request()->routeIs('billing.ack.receipt') ? 'active' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-receipt"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2m4 -14h6m-6 4h6m-2 4h2" /></svg>
                                    <span class="menu-text">Ack Receipt</span>
                                </a>
                            </li>
                            <li class="sidebarMenuItem">
                                <a href="{{ route('billing.petty.cash') }}" class="nav-link {{ request()->routeIs('billing.petty.cash') ? 'active' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-credit-card-refund"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 19h-6a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v4.5" /><path d="M3 10h18" /><path d="M7 15h.01" /><path d="M11 15h2" /><path d="M16 19h6" /><path d="M19 16l-3 3l3 3" /></svg>
                                    <span class="menu-text">Petty Cash Voucher</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebarMenuItem">
                        <a href="{{ route('ecommerce') }}" class="nav-link {{ request()->routeIs('ecommerce') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-basket"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M5.001 8h13.999a2 2 0 0 1 1.977 2.304l-1.255 7.152a3 3 0 0 1 -2.966 2.544h-9.512a3 3 0 0 1 -2.965 -2.544l-1.255 -7.152a2 2 0 0 1 1.977 -2.304z" /><path d="M17 10l-2 -6" /><path d="M7 10l2 -6" /></svg>
                            <span class="menu-text"> E-Commerce</span>
                        </a>
                    </li>

                    <li class="sidebarMenuItem">
                        <a href="{{ route('email.notification') }}" class="nav-link {{ request()->routeIs('email.notification') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mail-opened"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 9l9 6l9 -6l-9 -6l-9 6" /><path d="M21 9v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10" /><path d="M3 19l6 -6" /><path d="M15 13l6 6" /></svg>
                            <span class="menu-text">Email Notification</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="Nav-foot">
            <span>Powered by:</span>
            <img src="https://www.stafify.com/cdn/shop/files/e50lj9u5c9xat9j7z3ne_180x.png?v=1613708232" alt="" />
        </div>
    </div>
</div>

<script src="https://cdn.tailwindcss.com"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const menuItems = document.querySelectorAll('.sidebarMenuItem li');
        const activeIndex = localStorage.getItem('activeMenuIndex');
        if (activeIndex !== null && menuItems[activeIndex]) {
            menuItems[activeIndex].classList.add('active');
        }
        menuItems.forEach((item, index) => {
            item.addEventListener('click', () => {
                menuItems.forEach(li => li.classList.remove('active'));
                item.classList.add('active');
                localStorage.setItem('activeMenuIndex', index);
            });
        });
    });

    function toggleSidebar() {
        if (window.innerWidth <= 767) return; // Disable collapse on mobile

        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        sidebar.classList.toggle('collapsed');
        
        // Use CSS classes instead of inline styles where possible, or ensure inline styles don't conflict
        if (sidebar.classList.contains('collapsed')) {
            if (mainContent) mainContent.style.marginLeft = "70px";
        } else {
            if (mainContent) mainContent.style.marginLeft = "260px";
        }
    }

    // Clean up inline styles on resize
    window.addEventListener('resize', function() {
        const mainContent = document.querySelector('.main-content');
        if (window.innerWidth <= 767) {
            if (mainContent) mainContent.style.marginLeft = ""; // Remove inline style to let CSS take over
        } else {
            // Restore correct margin based on sidebar state if returning to desktop
            const sidebar = document.querySelector('.sidebar');
            if (sidebar && mainContent) {
                 if (sidebar.classList.contains('collapsed')) {
                    mainContent.style.marginLeft = "70px";
                } else {
                    mainContent.style.marginLeft = "260px"; // Or 250px depending on your CSS default
                }
            }
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        if (window.feather && feather.replace) {
            feather.replace();
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const menuButton = document.querySelector(".hamburger-menu");
        const closeButton = document.querySelector(".sidebar-close-btn");
        const sidebar = document.querySelector(".sidebar-container-mobile");
        const overlay = document.querySelector(".sidebar-overlay-mobile");
        function openSidebar() { sidebar.classList.add("active"); overlay.classList.add("active"); }
        function closeSidebar() { sidebar.classList.remove("active"); overlay.classList.remove("active"); }
        if (menuButton) menuButton.addEventListener("click", openSidebar);
        if (closeButton) closeButton.addEventListener("click", closeSidebar);
        if (overlay) overlay.addEventListener("click", closeSidebar);
    });

    function toggleDropdown(event, dropdownClass) {
        event.preventDefault();
        const dropdown = document.querySelector(`.${dropdownClass}`);
        if (!dropdown) return;
        
        const parentLi = dropdown.closest('.sidebarMenuItem.has-dropdown');
        const allDropdowns = document.querySelectorAll('.dropdown');
        
        allDropdowns.forEach(d => {
            if (d !== dropdown && d.classList.contains("open")) {
                d.style.maxHeight = null;
                d.style.opacity = "0";
                d.style.marginBottom = "-5px";
                d.classList.remove("open");
                const parentItem = d.closest('.sidebarMenuItem.has-dropdown');
                if (parentItem) parentItem.classList.remove('dropdown-open');
            }
        });
        
        if (dropdown.classList.contains("open")) {
            dropdown.style.maxHeight = null;
            dropdown.style.opacity = "0";
            dropdown.style.marginBottom = "-5px";
            dropdown.classList.remove("open");
            if (parentLi) parentLi.classList.remove('dropdown-open');
        } else {
            dropdown.style.maxHeight = dropdown.scrollHeight + "px";
            dropdown.style.opacity = "1";
            dropdown.style.marginBottom = "0px";
            dropdown.classList.add("open");
            if (parentLi) parentLi.classList.add('dropdown-open');
        }
    }
</script>
