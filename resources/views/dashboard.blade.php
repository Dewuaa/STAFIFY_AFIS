@extends('layouts.app')

@section('title', 'Dashboard')
@section('description', 'Manage your CRM dashboard with real-time insights into deals, leads, contacts, and sales performance.')

@push('styles')
    {{-- Tom Select --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.default.min.css" rel="stylesheet">
    
    <style>
        /* --- 1. Root Variables --- */
        :root {
            --primary-blue: #3B82F6;
            --primary-green: #10b981;
            --primary-red: #ef4444;
            --text-dark: #111827;
            --text-light: #6b7280;
            --bg-light: #f7f8fc;
            --border-color: #eef0f2;
            --card-shadow: 0 2px 8px rgba(0,0,0,0.04); /* Slightly softer shadow */
            --card-radius: 12px;
        }
        body {
            background: var(--bg-light);
        }

        /* --- 2. Main Layout --- */
        .dashboard-content {
            width: 100%;
            box-sizing: border-box;
            /* padding: 20px; Removed padding here, usually handled by parent container, but added checks below */
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            padding-top: 24px; 
        }

        /* --- 3. Header / Filters --- */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            padding: 20px;
            background: #ffffff;
            border-radius: var(--card-radius);
            border: 1px solid var(--border-color);
        }
        .filter-section { 
            display: flex; 
            flex-wrap: wrap; 
            gap: 12px; 
            align-items: flex-end; /* Aligns inputs and buttons nicely */
        }
        .export-bar { 
            display: flex; 
            gap: 8px; 
            flex-wrap: wrap; 
        }

        /* --- 4. Base Card --- */
        .dashboard-card {
            background: #ffffff;
            border-radius: var(--card-radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* --- 5. Grid Spans (Desktop Default) --- */
        .grid-col-span-1 { grid-column: span 1 / span 1; }
        .grid-col-span-2 { grid-column: span 2 / span 2; }
        .grid-col-span-4 { grid-column: span 4 / span 4; }

        /* --- 6. Card Specifics --- */
        .stat-card {
            justify-content: flex-start; /* Align items to top */
            flex-direction: row; /* Icon next to text */
            align-items: flex-start;
        }
        .stat-card-content {
            flex: 1;
            min-width: 0; /* Allows text truncation if needed */
        }
        .stat-card-content h4 {
            margin: 0 0 4px 0;
            font-size: 0.75rem;
            color: var(--text-light);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-card-content .value {
            font-size: 1.75rem; /* Fluid typography adjusted later */
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
            line-height: 1.2;
            word-wrap: break-word;
        }
        .stat-card-content .change {
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .change.positive { color: var(--primary-green); }
        .change.negative { color: var(--primary-red); }

        .stat-card-icon {
            font-size: 1.25rem;
            width: 48px;
            height: 48px;  
            border-radius: 50px; /* Softer square */
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0; /* Prevent squishing */
            margin-left: 12px;
        }
        .stat-card-icon.blue { background-color: #eff6ff; color: #3b82f6; }
        .stat-card-icon.green { background-color: #ecfdf5; color: #10b981; }
        .stat-card-icon.yellow { background-color: #fffbeb; color: #f59e0b; }
        .stat-card-icon.red { background-color: #fef2f2; color: #ef4444; }

        .insights-card h3, .chart-card h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0 0 16px 0;
        }
        .insights-content { display: flex; flex-direction: column; gap: 12px; }
        .insight-item {
            background: var(--bg-light);
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }
        .insight-item h4 { margin: 0 0 4px 0; font-size: 0.9rem; font-weight: 600; }
        .insight-item p { margin: 0; color: var(--text-light); font-size: 0.85rem; line-height: 1.4; }

        /* --- Clocks --- */
        .clock-card {
            align-items: center;
            justify-content: center;
            position: relative;
            min-height: 140px;
        }
        .clock-header h3 {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-light);
            margin: 0;
            text-transform: uppercase;
        }
        .clock-card .time {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 8px 0;
            font-variant-numeric: tabular-nums; /* Monospace numbers prevent jumping */
        }
        .clock-timezone-label {
            font-size: 0.75rem;
            color: var(--text-light);
            font-weight: 500;
            background: #f3f4f6;
            padding: 2px 8px;
            border-radius: 12px;
        }
        .clock-settings-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            background: transparent;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 4px;
        }
        .clock-settings-btn:hover { color: var(--text-dark); }

        /* --- Buttons & Inputs --- */
        .export-btn { 
            padding: 8px 14px; 
            border: 1px solid #d1d5db;
            background: #ffffff;
            border-radius: 6px; 
            font-weight: 500; 
            font-size: 0.85rem; 
            cursor: pointer; 
            display: inline-flex; 
            align-items: center; 
            gap: 6px; 
            color: #374151;
            transition: all 0.15s;
        }
        .export-btn:hover { background: #f9fafb; border-color: #9ca3af; }
        
        /* Specific Colors */
        .export-btn.pdf { color: white; border-color: #dbeafe; background: var(--primary-blue); }
        .export-btn.pdf:hover { background: #2563eb; }
        
        .export-btn.excel {color: white; border-color: #dbeafe; background: var(--primary-blue); }
        .export-btn.excel:hover {background: #2563eb; }
        
        .export-btn.csv { color: white; border-color: #dbeafe; background: var(--primary-blue); }
        .export-btn.csv:hover { background: #2563eb; }

        .export-btn.apply-filter-btn { 
            height: 40px;
            background: var(--primary-blue); 
            color: white; 
            border-color: var(--primary-blue); 
        }
        .export-btn.apply-filter-btn:hover { background: #2563eb; }

        .date-picker, .filter-dropdown {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .date-picker label, .filter-dropdown label { 
            font-weight: 600; 
            color: #374151;
            font-size: 0.75rem;
        }
        .date-picker input, .filter-dropdown select { 
            padding: 8px 10px; 
            border: 1px solid #d1d5db; 
            border-radius: 6px; 
            font-size: 0.875rem; 
            background: white;
        }

        /* --- Tom Select Override --- */
        .ts-wrapper .ts-control {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
        }

        /* --- Modal Styling --- */
        .clock-modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(2px);
        }
        .clock-modal-content {
            background: #fff;
            padding: 24px;
            border-radius: 16px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .clock-modal-header h4 { margin: 0 0 16px 0; font-size: 1.1rem; }
        .clock-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        .clock-modal-cancel-btn {
            padding: 8px 16px;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            cursor: pointer;
        }

        /* --- RESPONSIVE QUERIES --- */

        /* Laptop / Tablet Landscape (1024px - 1280px) */
        @media (max-width: 1280px) {
            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .grid-col-span-1 { grid-column: span 1; }
            .grid-col-span-2, .grid-col-span-4 { grid-column: span 2; }
        }

        /* Tablet Portrait (768px - 1024px) - Sidebar is visible here! */
        @media (max-width: 1024px) {
            .dashboard-header {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-section {
                width: 100%;
                justify-content: space-between;
            }
            .export-bar {
                width: 100%;
                justify-content: flex-start;
                border-top: 1px solid #f3f4f6;
                padding-top: 16px;
            }
            
            /* Force 1 column grid if sidebar is taking up space */
            .dashboard-grid {
                grid-template-columns: 1fr; 
            }
            .grid-col-span-1, .grid-col-span-2, .grid-col-span-4 {
                grid-column: span 1;
            }
        }

        /* Mobile (Up to 767px) - Sidebar is hidden (Hamburger) */
        @media (max-width: 767px) {
            /* 1. Single Column Grid */
            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 16px; /* Reduced gap */
                padding-top: 16px;
            }
            
            /* 2. Reset all spans to 1 column */
            .grid-col-span-1, 
            .grid-col-span-2, 
            .grid-col-span-4 {
                grid-column: span 1; 
            }

            /* 3. Compact Card Padding */
            .dashboard-card {
                padding: 16px; 
            }

            /* 4. Better Date Picker Layout (Side by Side) */
            .date-picker {
                width: 48%; /* Fits two on one line */
                flex: 0 0 48%;
            }
            .filter-dropdown {
                width: 100%;
            }
            .export-btn.apply-filter-btn {
                width: 100%;
                justify-content: center;
                margin-top: 8px;
            }

            /* 5. Full Width Export Buttons */
            .export-bar {
                display: grid;
                grid-template-columns: repeat(3, 1fr); /* 3 buttons side by side */
                gap: 8px;
            }
            .export-btn {
                width: 100%;
                justify-content: center;
                padding: 10px;
            }
            .export-btn span { display: none; } /* Hide text "PDF", just show Icon if very small */
            
            /* 6. Stat Card Typography Scaling */
            .stat-card-content .value {
                font-size: 1.5rem; 
            }
            .stat-card-icon {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
        }

        /* Very Small Screens (Up to 480px) */
        @media (max-width: 480px) {
            .dashboard-header {
                padding: 16px;
            }
            .filter-section {
                gap: 8px;
            }
            .export-btn i { margin-right: 0; }
            
            /* Stack clocks if needed or shrink font further */
            .clock-card .time {
                font-size: 1.75rem;
            }
            
            /* Ensure charts don't cause horizontal scroll */
            .chart-card {
                overflow: hidden;
            }
        }
    </style>
@endpush

@section('content')
<div class="dashboard-content"> 
    
    <div class="dashboard-header">
        <div class="filter-section">
            <div class="date-picker">
                <label for="start-date">From</label>
                <input type="date" id="start-date" name="start-date">
            </div>
            <div class="date-picker">
                <label for="end-date">To</label>
                <input type="date" id="end-date" name="end-date">
            </div>
            
            <div class="filter-dropdown">
                <label for="filter-category">Category</label>
                <select id="filter-category" name="filter-category">
                    <option value="all">All Categories</option>
                    @foreach(($categories ?? []) as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <button class="export-btn apply-filter-btn" onclick="applyDateFilter()">
                <i class="fas fa-filter"></i> <span>Filter</span>
            </button>
        </div>
        
        <div class="export-bar">
            <button class="export-btn pdf" onclick="exportToPDF()">
                <i class="fas fa-file-pdf"></i> <span>PDF</span>
            </button>
            <button class="export-btn excel" onclick="exportToExcel()">
                <i class="fas fa-file-excel"></i> <span>Excel</span>
            </button>
            <button class="export-btn csv" onclick="exportToCSV()">
                <i class="fas fa-file-csv"></i> <span>CSV</span>
            </button>
        </div>
    </div>

    <div class="dashboard-grid">

        <div class="dashboard-card stat-card grid-col-span-1">
            <div class="stat-card-content">
                <h4>Accounts Payable</h4>
                <div class="value" id="metric-ap">${{ number_format(($metrics['accounts_payable'] ?? 0), 2) }}</div>
                @php $apChange = $metrics['ap_change'] ?? 0; @endphp
                <div class="change {{ $apChange < 0 ? 'negative' : 'positive' }}">
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
                <div class="change {{ $arChange < 0 ? 'negative' : 'positive' }}">
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
                <div class="change {{ $invChange < 0 ? 'negative' : 'positive' }}">
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
                <div class="change {{ $stockChange < 0 ? 'negative' : 'positive' }}">
                    <i class="fas fa-{{ $stockChange < 0 ? 'arrow-down' : 'arrow-up' }}"></i>
                    <span>{{ number_format(abs($stockChange), 1) }}%</span>
                </div>
            </div>
            <div class="stat-card-icon yellow">
                <i class="fas fa-boxes-stacked"></i>
            </div>
        </div>

        <div class="dashboard-card insights-card grid-col-span-2">
            <h3><i class="fas fa-lightbulb text-yellow-500 mr-2"></i>Predictive Insights</h3>
            <div class="insights-content">
                <div class="insight-item">
                    <h4>Cash Flow Forecast</h4>
                    <p>Based on current trends, expected cash flow for next month: <strong>+8.3%</strong></p>
                </div>
                <div class="insight-item">
                    <h4>Inventory Optimization</h4>
                    <p>Consider reducing stock levels for category "Electronics" by 15%.</p>
                </div>
                <div class="insight-item">
                    <h4>Payment Trends</h4>
                    <p>Accounts receivable aging is improving. 30-day collection rate increased by 12%.</p>
                </div>
            </div>
        </div>
        
        <div class="dashboard-card clock-card grid-col-span-1">
            <button id="ph-tz-settings-btn" class="clock-settings-btn" aria-label="Settings">
                <i class="fas fa-cog"></i>
            </button>
            <div class="clock-header">
                <h3>PH Time</h3>
            </div>
            <div id="clock-ph" class="time">--:--:--</div>
            <div id="clock-ph-tz" class="clock-timezone-label"></div>
        </div>
        
        <div class="dashboard-card clock-card grid-col-span-1">
            <button id="us-tz-settings-btn" class="clock-settings-btn" aria-label="Settings">
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
            <div id="myChart1" style="width: 100%;"></div>
        </div>
        <div class="dashboard-card chart-card grid-col-span-2">
            <h3>Value Distribution</h3>
            <div id="myChart2" style="width: 100%;"></div>
        </div>

    </div>
</div>

<div id="ph-tz-modal" class="clock-modal-overlay">
    <div class="clock-modal-content">
        <div class="clock-modal-header">
            <h4>Select Primary Timezone</h4>
        </div>
        <div class="clock-modal-body">
            <label for="ph-tz-select" style="display:block; margin-bottom: 8px; font-weight: 600; color: #374151;">Timezone</label>
            <select id="ph-tz-select" name="ph-tz-select" placeholder="Search for a timezone..."></select>
            <div class="clock-modal-footer">
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
        </div>
        <div class="clock-modal-body">
            <label for="us-tz-select" style="display:block; margin-bottom: 8px; font-weight: 600; color: #374151;">Timezone</label>
            <select id="us-tz-select" name="us-tz-select" placeholder="Search for a timezone..."></select>
            <div class="clock-modal-footer">
                <button id="us-tz-modal-cancel" class="clock-modal-cancel-btn">Cancel</button>
                <button id="us-tz-modal-save" class="export-btn apply-filter-btn">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const chartLabels = @json(($categories ?? []));
    const chartData = @json(($totals ?? []));
    
    // Common Chart Options
    const commonChartOptions = {
        chart: { 
            type: 'donut', 
            height: '320', 
            fontFamily: 'Inter, sans-serif',
            toolbar: { show: false },
            animations: { enabled: true }
        },
        labels: chartLabels,
        dataLabels: { enabled: false }, // Cleaner look on mobile
        legend: { position: 'bottom', fontSize: '13px', markers: { width: 10, height: 10, radius: 10 } },
        plotOptions: { pie: { donut: { size: '50%' } } },
        stroke: { show: false }
    };

    // Chart 1
    var chart1 = new ApexCharts(document.querySelector("#myChart1"), {
        ...commonChartOptions,
        series: chartData,
        colors: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899'],
    });
    chart1.render();

    // Chart 2 (Mock Data Logic Preserved)
    var chart2 = new ApexCharts(document.querySelector("#myChart2"), {
        ...commonChartOptions,
        series: chartData.map(val => val * (Math.floor(Math.random() * 100) + 50)),
        colors: ['#06B6D4', '#6366F1', '#84CC16', '#D946EF', '#F97316', '#64748B'],
    });
    chart2.render();

    // --- Timezone & Other Logic Preserved from your original code ---
    const allTimezones = Intl.supportedValuesOf('timeZone');
    const timezoneOptions = allTimezones.map(tz => ({ value: tz, text: tz.replace(/_/g, ' ') }));
    const tomSelectSettings = { options: timezoneOptions, create: false, sortField: { field: "text", direction: "asc" } };

    const PH_TZ_KEY = 'dashboard_ph_timezone';
    const US_TZ_KEY = 'dashboard_us_timezone';
    const phClockLabel = document.getElementById('clock-ph-tz');
    const usClockLabel = document.getElementById('clock-us-tz');

    let selectedPhTimezone = localStorage.getItem(PH_TZ_KEY) || 'Asia/Manila';
    let selectedUsTimezone = localStorage.getItem(US_TZ_KEY) || 'America/New_York';

    // US Modal
    const usModal = document.getElementById('us-tz-modal');
    const usOpenBtn = document.getElementById('us-tz-settings-btn');
    const usSaveBtn = document.getElementById('us-tz-modal-save');
    const usCancelBtn = document.getElementById('us-tz-modal-cancel');
    const usTomSelect = new TomSelect('#us-tz-select', tomSelectSettings);
    usTomSelect.setValue(selectedUsTimezone);

    usOpenBtn.addEventListener('click', () => usModal.style.display = 'flex');
    usSaveBtn.addEventListener('click', () => {
        selectedUsTimezone = usTomSelect.getValue();
        localStorage.setItem(US_TZ_KEY, selectedUsTimezone);
        usModal.style.display = 'none';
        updateClocks();
    });
    usCancelBtn.addEventListener('click', () => {
        usTomSelect.setValue(localStorage.getItem(US_TZ_KEY) || 'America/New_York');
        usModal.style.display = 'none';
    });
    usModal.addEventListener('click', (e) => { if (e.target === usModal) usModal.style.display = 'none'; });

    // PH Modal
    const phModal = document.getElementById('ph-tz-modal');
    const phOpenBtn = document.getElementById('ph-tz-settings-btn');
    const phSaveBtn = document.getElementById('ph-tz-modal-save');
    const phCancelBtn = document.getElementById('ph-tz-modal-cancel');
    const phTomSelect = new TomSelect('#ph-tz-select', tomSelectSettings);
    phTomSelect.setValue(selectedPhTimezone);

    phOpenBtn.addEventListener('click', () => phModal.style.display = 'flex');
    phSaveBtn.addEventListener('click', () => {
        selectedPhTimezone = phTomSelect.getValue();
        localStorage.setItem(PH_TZ_KEY, selectedPhTimezone);
        phModal.style.display = 'none';
        updateClocks();
    });
    phCancelBtn.addEventListener('click', () => {
        phTomSelect.setValue(localStorage.getItem(PH_TZ_KEY) || 'Asia/Manila');
        phModal.style.display = 'none';
    });
    phModal.addEventListener('click', (e) => { if (e.target === phModal) phModal.style.display = 'none'; });

    function updateClocks() {
        const now = new Date();
        const timeFormatOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };

        const phTime = new Date(now.toLocaleString('en-US', { timeZone: selectedPhTimezone }));
        document.getElementById('clock-ph').textContent = phTime.toLocaleTimeString('en-US', timeFormatOptions);
        phClockLabel.textContent = selectedPhTimezone.replace(/_/g, ' ');

        const usTime = new Date(now.toLocaleString('en-US', { timeZone: selectedUsTimezone }));
        document.getElementById('clock-us').textContent = usTime.toLocaleTimeString('en-US', timeFormatOptions);
        usClockLabel.textContent = selectedUsTimezone.replace(/_/g, ' ');
    }
    setInterval(updateClocks, 1000);
    updateClocks();

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