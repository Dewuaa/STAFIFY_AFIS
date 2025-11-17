@extends('layouts.app')

@section('title', 'Dashboard')
@section('description', 'Manage your CRM dashboard with real-time insights into deals, leads, contacts, and sales performance. Track your business metrics and activities.')

@push('styles')
    {{-- Added Tom Select for searchable dropdowns --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.default.min.css" rel="stylesheet">
    
    <style>
        /* --- 1. Root & Body --- */
        :root {
            --primary-blue: #3B82F6;
            --primary-green: #10b981;
            --primary-red: #ef4444;
            --text-dark: #111827;
            --text-light: #6b7280;
            --bg-light: #f7f8fc;
            --border-color: #eef0f2;
            --card-shadow: 0 4px 12px rgba(0,0,0,0.04);
            --card-radius: 12px;
        }
        body {
            background: var(--bg-light);
        }

        /* --- 2. Main Layout --- */
        .dashboard-content {
            box-sizing: border-box;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 4-column master grid */
            gap: 24px;
            padding-top: 24px; 
        }

        /* --- 3. Header / Filters --- */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            padding: 24px;
            background: #ffffff;
            border-radius: var(--card-radius);
            border: 1px solid var(--border-color);
        }
        .filter-section { 
            display: flex; 
            flex-wrap: wrap; 
            gap: 12px; 
            align-items: center; 
        }
        .export-bar { 
            display: flex; 
            gap: 12px; 
            flex-wrap: wrap; 
        }

        /* --- 4. Base Card --- */
        .dashboard-card {
            background: #ffffff;
            border-radius: var(--card-radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            padding: 24px;
        }

        /* --- 5. Grid Item Spans --- */
        .grid-col-span-1 { grid-column: span 1 / span 1; }
        .grid-col-span-2 { grid-column: span 2 / span 2; }
        .grid-col-span-4 { grid-column: span 4 / span 4; }

        /* Make cards in the same "row" stretch to the same height */
        .insights-card, .clock-card {
             height: 100%;
        }


        /* --- 6. Card-specific styles (moved from old sections) --- */
        .stat-card {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .stat-card-content h4 {
            margin: 0 0 8px 0;
            font-size: 0.8rem;
            color: var(--text-light);
            font-weight: 600;
            text-transform: uppercase;
        }
        .stat-card-content .value {
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
            line-height: 1.1;
        }
        .stat-card-content .change {
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .stat-card-content .change.positive { color: var(--primary-green); }
        .stat-card-content .change.negative { color: var(--primary-red); }
        .stat-card-icon {
            font-size: 1.5rem;
            width: 56px;
            height: 56px;  
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .stat-card-icon.blue { background-color: #ebf5ff; color: #3b82f6; }
        .stat-card-icon.green { background-color: #e6f8f1; color: #10b981; }
        .stat-card-icon.yellow { background-color: #fffbeb; color: #f59e0b; }
        .stat-card-icon.red { background-color: #fff1f2; color: #ef4444; }

        .insights-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0 0 20px 0;
        }
        .insights-content { display: flex; flex-direction: column; gap: 16px; }
        .insight-item {
            background: var(--bg-light);
            padding: 16px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }
        .insight-item h4 {
            margin: 0 0 4px 0;
            font-size: 0.9rem;
            color: var(--text-dark);
            font-weight: 600;
        }
        .insight-item p { margin: 0; color: var(--text-light); font-size: 0.875rem; }

        .clock-card {
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative; /* For positioning the gear icon */
        }
        .clock-card h3 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-light);
            margin: 0 0 16px 0;
            text-transform: uppercase;
        }
        .clock-card .time {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1;
        }
        .chart-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0 0 24px 0;
        }
        .chart-card canvas {
            max-width: 100%;
        }

        /* --- 8. Filter/Export Buttons --- */
        .export-btn { 
            padding: 10px 16px; 
            border: 1px solid #e5e7eb;
            background: #3B82F6;
            border-radius: 8px; 
            font-weight: 500; 
            font-size: 0.875rem; 
            cursor: pointer; 
            display: flex; 
            align-items: center; 
            gap: 6px; 
            transition: all 0.2s ease; 
            color: #374151;
        }
        .export-btn:hover { background: #2563eb; border-color: #2563eb; }
        .export-btn.pdf { color: white; }
        .export-btn.excel { color: white; }
        .export-btn.csv { color: white; }
        .export-btn.apply-filter-btn { 
            color: #ffffff; 
            background: #3B82F6; 
            border-color: #3B82F6;
        }
        .export-btn.apply-filter-btn:hover { background: #2563eb; border-color: #2563eb; }
        
        .date-picker label, .filter-dropdown label { 
            font-weight: 500; 
            color: #374151;
            font-size: 0.875rem;
            margin-right: 4px;
        }
        .date-picker input[type="date"], .filter-dropdown select { 
            padding: 8px 12px; 
            border: 1px solid #e5e7eb; 
            border-radius: 8px; 
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }
        .date-picker input[type="date"]:focus, .filter-dropdown select:focus {
            border-color: #3B82F6;
            outline: none;
        }

        /* --- 9. Responsive --- */
        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr); /* 2 columns on medium */
            }
            .grid-col-span-1 {
                grid-column: span 1 / span 1;
            }
            .grid-col-span-2 {
                grid-column: span 2 / span 2; /* Spans 2 on medium */
            }
            .grid-col-span-4 {
                grid-column: span 2 / span 2; /* Full-width rows span 2 */
            }
        }
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr; /* 1 column on small */
                padding: 16px; /* Less padding on mobile */
                gap: 16px;
            }
            .grid-col-span-1,
            .grid-col-span-2,
            .grid-col-span-4 {
                grid-column: span 1 / span 1; /* All cards are 1 col wide */
            }
            .dashboard-header {
                flex-direction: column;
                align-items: stretch;
                margin: 16px 16px 0 16px; /* Match mobile padding */
            }
            .filter-section, .export-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .date-picker { justify-content: space-between; }
        }
        
        /* --- 10. Clock Modal & Settings (Updated) --- */
        .clock-header {
            width: 100%;
            text-align: center;
        }
        .clock-header h3 {
            margin-bottom: 0 !important; /* Override clock-card h3 margin */
            display: inline-block;
        }
        .clock-settings-btn {
            position: absolute;
            right: 16px; /* Moved to top-right */
            top: 16px;   /* Moved to top-right */
            background: none;
            border: none;
            color: var(--text-light);
            cursor: pointer;
            font-size: 1.1rem; /* Slightly larger */
            padding: 5px;
            border-radius: 50%;
            transition: all 0.2s ease;
            line-height: 1;
        }
        .clock-settings-btn:hover {
            color: var(--text-dark);
            background-color: #f0f0f0;
        }
        .clock-timezone-label {
            font-size: 0.8rem;
            color: var(--text-light);
            margin-top: 8px;
            font-weight: 500;
            word-break: break-all; /* Ensure long timezone names wrap */
            padding: 0 4px;
        }

        .clock-modal-overlay {
            display: none; /* Hidden by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .clock-modal-content {
            background: #fff;
            padding: 24px;
            border-radius: var(--card-radius);
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            width: 90%;
            max-width: 400px;
        }
        .clock-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 16px;
            margin-bottom: 16px;
        }
        .clock-modal-header h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
            color: var(--text-dark);
        }
        .clock-modal-body .filter-dropdown select {
            width: 100%; /* Make select box full-width */
        }

        /* Styles for the new Cancel button */
        .clock-modal-footer {
            display: flex;
            justify-content: flex-end; /* Align buttons to the right */
            gap: 10px; /* Space between buttons */
            margin-top: 20px;
        }

        .clock-modal-cancel-btn {
            padding: 10px 16px; 
            border: 1px solid #e5e7eb;
            background: #e74c3c; /* Light grey background */
            color: white;
            border-radius: 8px; 
            font-weight: 500; 
            font-size: 0.875rem; 
            cursor: pointer; 
            transition: all 0.2s ease; 
        }

        .clock-modal-cancel-btn:hover {
            background: red; /* Darker grey on hover */
            border-color: white;
        }


        /* --- 11. Tom Select Override --- */
        /* This makes Tom Select fit your existing style */
        .ts-wrapper .ts-control {
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }
        .ts-wrapper.focus .ts-control {
            border-color: #3B82F6;
            outline: none;
            box-shadow: none;
        }
    </style>
@endpush

@section('content')
<div class="dashboard-content"> 
    
    <div class="dashboard-header">
        <div class="filter-section">
            <div class="date-picker">
                <label for="start-date">From:</label>
                <input type="date" id="start-date" name="start-date">
            </div>
            <div class="date-picker">
                <label for="end-date">To:</label>
                <input type="date" id="end-date" name="end-date">
            </div>
            <div class="filter-dropdown">
                <label for="filter-category">Category:</label>
                <select id="filter-category" name="filter-category">
                    <option value="all">All Categories</option>
                    @foreach(($categories ?? []) as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <button class="export-btn apply-filter-btn" onclick="applyDateFilter()">
                <i class="fas fa-filter"></i> Apply Filter
            </button>
        </div>
        
        <div class="export-bar">
            <button class="export-btn pdf" onclick="exportToPDF()">
                <i class="fas fa-file-pdf"></i> PDF
            </button>
            <button class="export-btn excel" onclick="exportToExcel()">
                <i class="fas fa-file-excel"></i> Excel
            </button>
            <button class="export-btn csv" onclick="exportToCSV()">
                <i class="fas fa-file-csv"></i> CSV
            </button>
        </div>
    </div>

    <div class="dashboard-grid">

        <div class="dashboard-card stat-card grid-col-span-1">
            <div class="stat-card-content">
                <h4>Accounts Payable</h4>
                <div class="value" id="metric-ap">${{ number_format(($metrics['accounts_payable'] ?? 0), 2) }}</div>
                @php $apChange = $metrics['ap_change'] ?? 0; @endphp
                <div class="change {{ $apChange < 0 ? 'negative' : 'positive' }}" id="metric-ap-change">
                    <i class="fas fa-{{ $apChange < 0 ? 'arrow-down' : 'arrow-up' }}"></i>
                    <span>{{ number_format(abs($apChange), 1) }}%</span>
                </div>
            </div>
            <div class="stat-card-icon red">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
        </div>
        <div class="dashboard-card stat-card grid-col-span-1">
            <div class="stat-card-content">
                <h4>Accounts Receivable</h4>
                <div class="value" id="metric-ar">${{ number_format(($metrics['accounts_receivable'] ?? 0), 2) }}</div>
                @php $arChange = $metrics['ar_change'] ?? 0; @endphp
                <div class="change {{ $arChange < 0 ? 'negative' : 'positive' }}" id="metric-ar-change">
                    <i class="fas fa-{{ $arChange < 0 ? 'arrow-down' : 'arrow-up' }}"></i>
                    <span>{{ number_format(abs($arChange), 1) }}%</span>
                </div>
            </div>
            <div class="stat-card-icon green">
                <i class="fas fa-hand-holding-dollar"></i>
            </div>
        </div>
        <div class="dashboard-card stat-card grid-col-span-1">
            <div class="stat-card-content">
                <h4>Invoice Worth</h4>
                <div class="value" id="metric-invoice">${{ number_format(($metrics['invoice_worth'] ?? 0), 2) }}</div>
                @php $invChange = $metrics['invoice_change'] ?? 0; @endphp
                <div class="change {{ $invChange < 0 ? 'negative' : 'positive' }}" id="metric-invoice-change">
                    <i class="fas fa-{{ $invChange < 0 ? 'arrow-down' : 'arrow-up' }}"></i>
                    <span>{{ number_format(abs($invChange), 1) }}%</span>
                </div>
            </div>
            <div class="stat-card-icon blue">
                <i class="fas fa-receipt"></i>
            </div>
        </div>
        <div class="dashboard-card stat-card grid-col-span-1">
            <div class="stat-card-content">
                <h4>Inventory Value</h4>
                <div class="value" id="metric-inventory">${{ number_format(($metrics['inventory_value'] ?? 0), 2) }}</div>
                @php $stockChange = $metrics['inventory_change'] ?? 0; @endphp
                <div class="change {{ $stockChange < 0 ? 'negative' : 'positive' }}" id="metric-inventory-change">
                    <i class="fas fa-{{ $stockChange < 0 ? 'arrow-down' : 'arrow-up' }}"></i>
                    <span>{{ number_format(abs($stockChange), 1) }}%</span>
                </div>
            </div>
            <div class="stat-card-icon yellow">
                <i class="fas fa-boxes-stacked"></i>
            </div>
        </div>

        <div class="dashboard-card insights-card grid-col-span-2">
            <h3>Predictive Insights</h3>
            <div class="insights-content">
                <div class="insight-item">
                    <h4>Cash Flow Forecast</h4>
                    <p>Based on current trends, expected cash flow for next month: <strong>+8.3%</strong></p>
                </div>
                <div class="insight-item">
                    <h4>Inventory Optimization</h4>
                    <p>Consider reducing stock levels for category "Electronics" by 15% to optimize holding costs.</p>
                </div>
                <div class="insight-item">
                    <h4>Payment Trends</h4>
                    <p>Accounts receivable aging is improving. 30-day collection rate increased by 12% this quarter.</p>
                </div>
            </div>
        </div>
        
        <div class="dashboard-card clock-card grid-col-span-1">
            <button id="ph-tz-settings-btn" class="clock-settings-btn">
                <i class="fas fa-cog"></i>
            </button>
            <div class="clock-header">
                <h3>PH Time</h3>
            </div>
            <div id="clock-ph" class="time">--:--:--</div>
            <div id="clock-ph-tz" class="clock-timezone-label"></div>
        </div>
        
        <div class="dashboard-card clock-card grid-col-span-1">
            <button id="us-tz-settings-btn" class="clock-settings-btn">
                <i class="fas fa-cog"></i>
            </button>
            <div class="clock-header">
                <h3>US Time</h3>
            </div>
            <div id="clock-us" class="time">--:--:--</div>
            <div id="clock-us-tz" class="clock-timezone-label"></div>
        </div>

        <div class="dashboard-card chart-card grid-col-span-2">
            <h3>Product Distribution</h3>
            <div id="myChart1" style="width: 100%; min-height: 350px;"></div>
        </div>
        <div class="dashboard-card chart-card grid-col-span-2">
            <h3>Value Distribution</h3>
            <div id="myChart2" style="width: 100%; min-height: 350px;"></div>
        </div>

    </div>
</div>

<div id="ph-tz-modal" class="clock-modal-overlay">
    <div class="clock-modal-content">
        <div class="clock-modal-header">
            <h4>Select Primary Timezone</h4>
            {{-- Removed the 'x' close button --}}
        </div>
        <div class="clock-modal-body">
            <label for="ph-tz-select" style="display:block; margin-bottom: 8px; font-weight: 500; color: #374151;">Timezone:</label>
            <div class="filter-dropdown">
                <select id="ph-tz-select" name="ph-tz-select" placeholder="Search for a timezone..."></select>
            </div>
            <div class="clock-modal-footer"> {{-- Added a footer for buttons --}}
                <button id="ph-tz-modal-cancel" class="clock-modal-cancel-btn">Cancel</button>
                <button id="ph-tz-modal-save" class="export-btn apply-filter-btn">Save</button>
            </div>
        </div>
    </div>
</div>

<div id="us-tz-modal" class="clock-modal-overlay">
    <div class="clock-modal-content">
        <div class="clock-modal-header">
            <h4>Select US Timezone</h4>
            {{-- Removed the 'x' close button --}}
        </div>
        <div class="clock-modal-body">
            <label for="us-tz-select" style="display:block; margin-bottom: 8px; font-weight: 500; color: #374151;">Timezone:</label>
            <div class="filter-dropdown">
                <select id="us-tz-select" name="us-tz-select" placeholder="Search for a timezone..."></select>
            </div>
            <div class="clock-modal-footer"> {{-- Added a footer for buttons --}}
                <button id="us-tz-modal-cancel" class="clock-modal-cancel-btn">Cancel</button>
                <button id="us-tz-modal-save" class="export-btn apply-filter-btn">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Added FontAwesome and Tom Select --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const chartLabels = @json(($categories ?? []));
    const chartData = @json(($totals ?? []));
    
    // --- 1. APEX CHARTS (No changes) ---
    var chart1Options = {
        series: chartData,
        chart: { type: 'donut', height: 350, fontFamily: 'Segoe UI, system-ui, sans-serif', toolbar: { show: true }, animations: { enabled: true } },
        labels: chartLabels,
        colors: ['#2ecc71', '#e67e22', '#3498db', '#9b59b6', '#f1c40f', '#1abc9c'],
        dataLabels: { enabled: true, style: { fontSize: '14px', fontWeight: 600 } },
        legend: { position: 'bottom', fontSize: '14px', markers: { width: 12, height: 12, radius: 12 } },
        plotOptions: { pie: { donut: { size: '50%', labels: { show: true, total: { show: true, label: 'Total', fontSize: '16px', fontWeight: 600 } } } } },
        responsive: [{ breakpoint: 480, options: { chart: { height: 300 }, legend: { position: 'bottom' } } }]
    };
    var chart1 = new ApexCharts(document.querySelector("#myChart1"), chart1Options);
    chart1.render();

    var chart2Options = {
        series: chartData.map(val => val * (Math.floor(Math.random() * 100) + 50)), // Your random value logic
        chart: { type: 'donut', height: 350, fontFamily: 'Segoe UI, system-ui, sans-serif', toolbar: { show: true }, animations: { enabled: true } },
        labels: chartLabels,
        colors: ['#1abc9c', '#d35400', '#2980b9', '#8e44ad', '#f39c12', '#27ae60'],
        dataLabels: { enabled: true, style: { fontSize: '14px', fontWeight: 600 } },
        legend: { position: 'bottom', fontSize: '14px', markers: { width: 12, height: 12, radius: 12 } },
        plotOptions: { pie: { donut: { size: '50%', labels: { show: true, total: { show: true, label: 'Total Value', fontSize: '16px', fontWeight: 600 } } } } },
        responsive: [{ breakpoint: 480, options: { chart: { height: 300 }, legend: { position: 'bottom' } } }]
    };
    var chart2 = new ApexCharts(document.querySelector("#myChart2"), chart2Options);
    chart2.render();

    // --- 2. TIMEZONE MODAL LOGIC (New Refactored Version) ---

    // Get all IANA timezones from the browser
    const allTimezones = Intl.supportedValuesOf('timeZone');
    
    // Format them for Tom Select (e.g., "Asia/Manila" -> {value: "Asia/Manila", text: "Asia/Manila"})
    const timezoneOptions = allTimezones.map(tz => {
        return { value: tz, text: tz.replace(/_/g, ' ') }; // Replace underscores for readability
    });

    const tomSelectSettings = {
        options: timezoneOptions,
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        }
    };

    // --- Timezone Keys & Labels ---
    const PH_TZ_KEY = 'dashboard_ph_timezone';
    const US_TZ_KEY = 'dashboard_us_timezone';
    const phClockLabel = document.getElementById('clock-ph-tz');
    const usClockLabel = document.getElementById('clock-us-tz');

    // --- Get stored values or set defaults ---
    let selectedPhTimezone = localStorage.getItem(PH_TZ_KEY) || 'Asia/Manila';
    let selectedUsTimezone = localStorage.getItem(US_TZ_KEY) || 'America/New_York';

    // --- US Timezone Modal ---
    const usModal = document.getElementById('us-tz-modal');
    const usOpenBtn = document.getElementById('us-tz-settings-btn');
    const usSaveBtn = document.getElementById('us-tz-modal-save');
    const usCancelBtn = document.getElementById('us-tz-modal-cancel'); // New Cancel button
    // Initialize US Tom Select
    const usTomSelect = new TomSelect('#us-tz-select', tomSelectSettings);
    usTomSelect.setValue(selectedUsTimezone); // Set to stored value

    // --- US Modal Event Listeners ---
    usOpenBtn.addEventListener('click', () => usModal.style.display = 'flex');
    usSaveBtn.addEventListener('click', () => {
        selectedUsTimezone = usTomSelect.getValue(); // Get value from Tom Select
        localStorage.setItem(US_TZ_KEY, selectedUsTimezone); // Save to storage
        usModal.style.display = 'none'; // Close modal
        updateClocks(); // Update immediately
    });
    usCancelBtn.addEventListener('click', () => { // New Cancel listener
        usTomSelect.setValue(localStorage.getItem(US_TZ_KEY) || 'America/New_York'); // Reset dropdown to saved value
        usModal.style.display = 'none'; // Close modal
    });
    usModal.addEventListener('click', (e) => {
        if (e.target === usModal) {
            usTomSelect.setValue(localStorage.getItem(US_TZ_KEY) || 'America/New_York'); // Reset if clicking outside
            usModal.style.display = 'none';
        }
    });

    // --- PH Timezone Modal (New) ---
    const phModal = document.getElementById('ph-tz-modal');
    const phOpenBtn = document.getElementById('ph-tz-settings-btn');
    const phSaveBtn = document.getElementById('ph-tz-modal-save');
    const phCancelBtn = document.getElementById('ph-tz-modal-cancel'); // New Cancel button
    // Initialize PH Tom Select
    const phTomSelect = new TomSelect('#ph-tz-select', tomSelectSettings);
    phTomSelect.setValue(selectedPhTimezone); // Set to stored value

    // --- PH Modal Event Listeners ---
    phOpenBtn.addEventListener('click', () => phModal.style.display = 'flex');
    phSaveBtn.addEventListener('click', () => {
        selectedPhTimezone = phTomSelect.getValue(); // Get value from Tom Select
        localStorage.setItem(PH_TZ_KEY, selectedPhTimezone); // Save to storage
        phModal.style.display = 'none'; // Close modal
        updateClocks(); // Update immediately
    });
    phCancelBtn.addEventListener('click', () => { // New Cancel listener
        phTomSelect.setValue(localStorage.getItem(PH_TZ_KEY) || 'Asia/Manila'); // Reset dropdown to saved value
        phModal.style.display = 'none'; // Close modal
    });
    phModal.addEventListener('click', (e) => {
        if (e.target === phModal) {
            phTomSelect.setValue(localStorage.getItem(PH_TZ_KEY) || 'Asia/Manila'); // Reset if clicking outside
            phModal.style.display = 'none';
        }
    });


    // --- 3. CLOCK SCRIPT (Updated for both clocks) ---
    function updateClocks() {
        const now = new Date();
        const timeFormatOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };

        // PH Clock (Now uses selectedPhTimezone)
        const phTime = new Date(now.toLocaleString('en-US', { timeZone: selectedPhTimezone }));
        document.getElementById('clock-ph').textContent = phTime.toLocaleTimeString('en-US', timeFormatOptions);
        phClockLabel.textContent = selectedPhTimezone.replace(/_/g, ' '); // Use the IANA string

        // US Clock (Now uses selectedUsTimezone)
        const usTime = new Date(now.toLocaleString('en-US', { timeZone: selectedUsTimezone }));
        document.getElementById('clock-us').textContent = usTime.toLocaleTimeString('en-US', timeFormatOptions);
        usClockLabel.textContent = selectedUsTimezone.replace(/_/g, ' '); // Use the IANA string
    }
    setInterval(updateClocks, 1000);
    updateClocks(); // Initial call

    // --- 4. FILTER/EXPORT FUNCTIONS (Unchanged) ---
    window.exportToPDF = function() { alert('Exporting dashboard to PDF...'); };
    window.exportToExcel = function() { alert('Exporting data to Excel...'); };
    window.exportToCSV = function() { alert('Exporting data to CSV...'); };
    
    window.applyDateFilter = function() {
        const startDate = document.getElementById('start-date').value;
        const endDate = document.getElementById('end-date').value;
        const category = document.getElementById('filter-category').value;
        if(!startDate || !endDate) { alert('Please select both start and end dates'); return; }
        alert(`Applying filter for date range: ${startDate} to ${endDate} and category: ${category}`);
    };
});
</script>
@endpush