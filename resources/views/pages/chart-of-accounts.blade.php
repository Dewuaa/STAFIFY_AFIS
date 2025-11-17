@extends('layouts.app')
@section('title', 'Chart of Accounts')

{{-- 
============================================
 STYLES
============================================
--}}
@push('styles')
<style>
    :root {
        --primary: #4361ee;
        --primary-light: #e6ecfe;
        --primary-dark: #3a56d4;
        --secondary: #3f37c9;
        --success: #4cc9f0;
        --success-dark: #3ab5d9;
        --danger: #f72585;
        --warning: #f8961e;
        --info: #4895ef;
        --dark: #212529;
        --darker: #1a1e21;
        --light: #f8f9fa;
        --lighter: #fefefe;
        --gray: #6c757d;
        --gray-light: #e9ecef;
        --border-radius: 8px;
        --border-radius-lg: 12px;
        --box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        --box-shadow-lg: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.2s ease;
        --sidebar-width: 280px;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    /* Use the body font from app.blade.php */
    body {
        font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif;
        background-color: #f5f7fb;
        color: var(--dark);
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 24px;
    }

    .dashboard {
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 20px;
    }

    .card {
        background: white;
        border-radius: var(--border-radius-lg);
        box-shadow: var(--box-shadow);
        padding: 20px;
        transition: var(--transition);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .card:hover {
        box-shadow: var(--box-shadow-lg);
        transform: translateY(-2px);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-bottom: 12px;
        /* border-bottom: 1px solid var(--gray-light); */
    }

    .card-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-title .material-icons {
        font-size: 20px;
        color: var(--primary);
    }

    .account-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 14px;
    }

    .account-table th {
        background-color: #f8f9fa;
        color: var(--text-color-light);
        font-weight: 600;
        text-align: left;
        padding: 10px 16px;
        /* position: sticky; */
        top: 0;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }


    .account-table td {
        padding: 10px 14px;
        border-bottom: 1px solid var(--gray-light);
        vertical-align: middle;
    }

    .account-table tr:last-child td {
        border-bottom: none;
    }

    .account-table tr:hover td {
        background-color: rgba(67, 97, 238, 0.05);
        cursor: pointer;
    }

    .account-number {
        font-family: "SF Mono", monospace;
        color: var(--gray);
        font-size: 13px;
        font-weight: 500;
    }

    .account-group {
        font-weight: 500;
        color: var(--dark);
        font-size: 13px;
    }

    .account-type {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
        background-color: var(--gray-light);
        color: var(--gray);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .account-type.parent {
        background-color: var(--primary-light);
        color: var(--primary);
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
        font-size: 13px;
        color: var(--dark);
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--gray-light);
        border-radius: var(--border-radius);
        font-family: inherit;
        font-size: 14px;
        transition: var(--transition);
        background-color: white;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .form-control::placeholder {
        color: var(--gray-light);
    }

    .flex-group {
        display: flex;
        gap: 12px;
    }

    .flex-group .form-group {
        flex: 1;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
    }

    .checkbox-group input {
        width: 16px;
        height: 16px;
        accent-color: var(--primary);
    }

    .checkbox-group label {
        font-size: 13px;
        font-weight: 500;
        color: var(--dark);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 20px;
        border-radius: var(--border-radius);
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: var(--transition);
        border: none;
        gap: 8px;
    }

    .btn-primary {
        background-color: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
        transform: translateY(-1px);
    }

    .btn-block {
        width: 100%;
    }

    .hidden {
        display: none;
    }

    .account-preview {
        background-color: white;
        border-radius: var(--border-radius-lg);
        padding: 20px;
        min-height: 300px;
        border: 1px solid var(--gray-light);
    }

    .account-hierarchy {
        list-style-type: none;
        padding-left: 24px;
        margin: 0;
    }

    .account-hierarchy li {
        padding: 8px 0;
        position: relative;
    }

    .account-hierarchy li:before {
        content: "";
        position: absolute;
        left: -15px;
        top: 18px;
        width: 10px;
        height: 1px;
        background: var(--gray-light);
    }

    .account-hierarchy li:after {
        content: "";
        position: absolute;
        left: -15px;
        top: 0;
        width: 1px;
        height: 100%;
        background: var(--gray-light);
    }

    .account-hierarchy li:last-child:after {
        height: 18px;
    }

    .account-name {
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .account-icon {
        color: var(--primary);
        width: 18px;
        height: 18px;
    }

    .folder-icon {
        color: var(--warning);
        width: 18px;
        height: 18px;
    }

    .empty-preview {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 200px;
        color: var(--gray);
        text-align: center;
        padding: 20px;
    }

    .empty-preview .material-icons {
        font-size: 48px;
        margin-bottom: 12px;
        opacity: 0.3;
    }

    .empty-preview p {
        font-size: 14px;
        color: var(--gray);
        max-width: 300px;
    }

    .message {
        padding: 10px 14px;
        border-radius: var(--border-radius);
        margin-bottom: 16px;
        font-size: 13px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .success-message {
        background-color: rgba(76, 201, 240, 0.1);
        color: var(--success-dark);
        border: 1px solid rgba(76, 201, 240, 0.2);
    }

    .error-message {
        background-color: rgba(247, 37, 133, 0.1);
        color: var(--danger);
        border: 1px solid rgba(247, 37, 133, 0.2);
    }

    .account-group-title {
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
    }

    .section-title {
        font-weight: 600;
        color: var(--dark);
        margin: 20px 0 12px;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .account-number-input {
        width: 100px !important;
    }

    .account-name-input {
        flex: 1;
    }

    .scroll-container {
        max-height: 600px;
        overflow-y: auto;
        padding-right: 8px;
    }

    /* Custom scrollbar */
    .scroll-container::-webkit-scrollbar {
        width: 6px;
    }

    .scroll-container::-webkit-scrollbar-track {
        background: var(--gray-light);
        border-radius: 10px;
    }

    .scroll-container::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 10px;
    }

    /* Parent account dropdown styling */
    .parent-account {
        font-weight: 600;
        background-color: var(--primary-light) !important;
        color: var(--primary) !important;
    }

    /* Animation for new account addition */
    @keyframes highlight {
        0% {
            background-color: rgba(67, 97, 238, 0.2);
        }
        100% {
            background-color: transparent;
        }
    }

    .highlight {
        animation: highlight 2s ease-out;
    }

    /* Improved select dropdown */
    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 16px;
        padding-right: 32px;
    }

    /* Badges for account groups */
    .account-group-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-right: 8px;
    }

    .assets-badge {
        background-color: rgba(72, 149, 239, 0.1);
        color: #4895ef;
    }

    .liabilities-badge {
        background-color: rgba(248, 150, 30, 0.1);
        color: #f8961e;
    }

    .equity-badge {
        background-color: rgba(76, 201, 240, 0.1);
        color: #4cc9f0;
    }

    .revenue-badge {
        background-color: rgba(67, 97, 238, 0.1);
        color: #4361ee;
    }

    .cost-of-sales-badge {
        background-color: rgba(247, 37, 133, 0.1);
        color: #f72585;
    }

    .operating-expenses-badge {
        background-color: rgba(247, 37, 133, 0.2); 
        color: #f72585;
    }
    
    .other-expenses-badge {
        background-color: rgba(247, 37, 133, 0.3); 
        color: #f72585;
    }

    .other-income-badge {
        background-color: rgba(67, 97, 238, 0.2); 
        color: #4361ee;
    }
    
    .year-end-adjustments-&-closing-entries-badge {
        background-color: rgba(108, 117, 125, 0.1);
        color: #6c757d;
    }

    /* Loading spinner */
    .spinner {
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s ease-in-out infinite;
        display: inline-block;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Tooltip */
    .tooltip {
        position: relative;
        display: inline-block;
    }

    .tooltip .tooltip-text {
        visibility: hidden;
        width: 200px;
        background-color: var(--dark);
        color: white;
        text-align: center;
        border-radius: var(--border-radius);
        padding: 8px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        opacity: 0;
        transition: opacity 0.3s;
        font-size: 12px;
        font-weight: normal;
    }

    .tooltip:hover .tooltip-text {
        visibility: visible;
        opacity: 1;
    }

    /* Status toggle switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 20px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: var(--gray-light);
        transition: var(--transition);
        border-radius: 20px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        transition: var(--transition);
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: var(--primary);
    }

    input:checked + .slider:before {
        transform: translateX(20px);
    }

    /* Card footer */
    .card-footer {
        margin-top: 16px;
        padding-top: 12px;
        border-top: 1px solid var(--gray-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Search input */
    .search-input {
        position: relative;
    }

    .search-input .form-control {
        padding-left: 36px;
    }

    .search-input .material-icons {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray);
        font-size: 18px;
    }

    /* Account group highlighting */
    .account-group-header {
        background-color: #f8f9fa; /* Lighter background */
        font-weight: 700;
        color: var(--dark);
        padding: 10px 16px;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .account-label-header {
        background-color: #f8f9fa; /* A very light gray */
        font-weight: 700;
        color: var(--dark);
    }
    .account-label-header td {
        padding: 10px 16px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--gray-light);
        border-top: 1px solid var(--gray-light);
    }
    .account-label-header:hover td {
        background-color: #f1f3f5; /* Slightly darker on hover */
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }

    .action-btn {
        background: none;
        border: none;
        color: var(--gray);
        cursor: pointer;
        padding: 6px;
        border-radius: 4px;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .action-btn:hover {
        background-color: var(--primary-light);
        color: var(--primary);
    }

    .action-btn.delete:hover {
        background-color: rgba(247, 37, 133, 0.1);
        color: var(--danger);
    }

    .action-btn + .action-btn {
        margin-left: 4px;
    }

    /* Edit form styling */
    .edit-form-container {
        background-color: rgba(67, 97, 238, 0.05);
        border-left: 3px solid var(--primary);
        padding: 12px;
        margin: 8px 0;
    }

    /* Status toggle */
    .status-toggle {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Bulk actions */
    .bulk-actions {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
    }

    /* Export button */
    .export-btn {
        background-color: #3b82f6;
        color: white;
    }

    .export-btn:hover {
        background-color: #1e4b84;
    }

    /* Header actions */
    .header-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 5px;
        margin-left: auto;
    }

    .table-actions {
        display: flex;
        gap: 8px;
    }

    .table-responsive {
        overflow-x: auto;
        max-height: 70vh;
    }

    .account-table th[data-sort] {
        cursor: pointer;
        position: relative;
        padding-right: 24px;
    }

    .account-table th[data-sort] .sort-icon {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0.5;
        transition: var(--transition);
    }

    .account-table th[data-sort]:hover .sort-icon {
        opacity: 1;
    }

    .account-table th.sorted-asc .sort-icon::after {
        content: "↑";
    }

    .account-table th.sorted-desc .sort-icon::after {
        content: "↓";
    }

    /* Card-footer was duplicated, this is the correct one */
    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 16px -20px -20px -20px; /* Extend to card edges */
        padding: 12px 20px;
        background-color: var(--light);
        border-top: 1px solid var(--gray-light);
        border-bottom-left-radius: var(--border-radius-lg);
        border-bottom-right-radius: var(--border-radius-lg);
    }
    
    .card-footer-info {
        font-size: 13px;
        color: var(--gray);
    }

    .pagination {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .page-info {
        padding: 0 8px;
        font-size: 13px;
        color: var(--gray);
    }

    /* Status badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-badge.status-active {
        background-color: rgba(76, 201, 240, 0.1);
        color: var(--success-dark);
    }
    
    .status-badge.status-inactive {
        background-color: rgba(108, 117, 125, 0.1);
        color: var(--gray);
    }

    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .dashboard {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .flex-group {
            flex-direction: column;
        }

        .action-buttons {
            flex-direction: column;
            align-items: stretch; /* Make buttons full-width */
        }
        
        .header-actions {
            width: 100%; /* Make search full width on mobile */
        }
        
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        
        .card-footer {
            flex-direction: column;
            gap: 12px;
        }
    }
</style>
@endpush


{{-- 
============================================
 CONTENT
============================================
--}}
@section('content')
    <div class="main-container">
        
        <div class="dashboard">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <span class="material-icons">list_alt</span>
                        Account Directory
                    </h3>
                    <div class="header-actions">
                        <div class="search-input">
                            <span class="material-icons">search</span>
                            <input type="text" class="form-control" id="accountSearch" placeholder="Search accounts...">
                        </div>
                        <button id="exportBtn" class="btn export-btn">
                            <span class="material-icons">download</span>
                        </button>
                    </div>
                </div>

                <div class="bulk-actions hidden" id="bulkActions">
                    <button class="btn btn-primary" id="bulkEditBtn">
                        <span class="material-icons">edit</span>
                        Edit Selected
                    </button>
                    <button class="btn btn-danger" id="bulkDeleteBtn">
                        <span class="material-icons">delete</span>
                        Delete Selected
                    </button>
                    <button class="btn" id="cancelBulkAction">
                        Cancel
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="account-table" id="defaultAccountsList">
                        <thead>
                            <tr>
                                <th width="40"><input type="checkbox" id="selectAllCheckbox"></th>
                                <th data-sort="number">Number <span class="sort-icon"></span></th>
                                <th data-sort="name">Account Name <span class="sort-icon"></span></th>
                                <th data-sort="type">Type <span class="sort-icon"></span></th>
                                <th data-sort="status">Status <span class="sort-icon"></span></th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <div class="card-footer-info">
                        Total Accounts: <strong id="accountCount">0</strong> |
                        Active: <strong id="activeAccountCount">0</strong>
                    </div>
                    </div>
            </div>

            <div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span class="material-icons" id="formIcon">add_circle</span>
                            <span id="formTitle">Create New Account</span>
                        </h3>
                    </div>
                    <div id="messageContainer"></div>

                    <form id="accountForm">
                        <input type="hidden" id="accountId">

                        <div class="form-group">
                            <label for="accountGroup" class="form-label">Account Category</label>
                            <select id="accountGroup" class="form-control">
                                <option value="" disabled selected>Select Category</option>
                                <option value="Assets">Assets</option>
                                <option value="Liabilities">Liabilities</option>
                                <option value="Equity">Equity</option>
                                <option value="Revenue">Revenue</option>
                                <option value="Cost of Sales">Cost of Sales</option>
                                <option value="Operating Expenses">Operating Expenses</option>
                                <option value="Other Expenses">Other Expenses</option>
                                <option value="Other Income">Other Income</option>
                                <option value="Year-End Adjustments & Closing Entries">Year-End Adjustments</option>
                            </select>
                        </div>

                        <div class="form-group" id="accountTypeGroup">
                            <label for="accountType" class="form-label">Account Type</label>
                            <select id="accountType" class="form-control">
                                <option value="" disabled selected>Select Category first</option>
                            </select>
                            <div id="customAccountTypeFields" class="hidden flex-group" style="margin-top: 8px;">
                                <input type="text" id="customAccountNumber" class="form-control account-number-input" placeholder="Number">
                                <input type="text" id="customAccountName" class="form-control account-name-input" placeholder="Account Name">
                            </div>
                        </div>

                        <div class="flex-group">
                            <div class="form-group">
                                <label for="isParent" class="form-label">Account Role</label>
                                <select id="isParent" class="form-control">
                                    <option value="0">Sub-Account</option>
                                    <option value="1">Parent Account (Group)</option>
                                </select>
                            </div>
                            
                            <div class="form-group" id="parentAccountField">
                                <label for="parentAccount" class="form-label">Parent Account</label>
                                <select id="parentAccount" class="form-control">
                                    <option value="" disabled selected>Select Parent Account</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" class="form-control" rows="2" placeholder="Provide details about this account"></textarea>
                        </div>

                        <div class="action-buttons" id="formActions">
                            <button type="button" id="saveAccountBtn" class="btn btn-primary">
                                <span class="material-icons">save</span>
                                Save Account
                            </button>
                            <button type="button" id="cancelEditBtn" class="btn hidden">
                                Cancel
                            </button>
                            <button type="button" id="deleteAccountBtn" class="btn btn-danger hidden" style="margin-left: auto;">
                                <span class="material-icons">delete</span>
                                Delete
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card" style="margin-top: 20px;">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span class="material-icons">preview</span>
                            Account Preview
                        </h3>
                    </div>
                    <div class="account-preview">
                        <div id="accountPreviewContent" class="empty-preview">
                            <span class="material-icons">description</span>
                            <p>Your account will appear here as you complete the form</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


{{-- 
============================================
 SCRIPTS
============================================
--}}
@push('scripts')
<script>
    const csrfToken = "{{ csrf_token() }}";
    const accountRoutes = {
        index: "{{ route('accounts.index.json') }}",
        store: "{{ route('accounts.store') }}",
        update: "{{ route('accounts.update', ['account' => '__ID__']) }}",
        destroy: "{{ route('accounts.destroy', ['account' => '__ID__']) }}",
        bulkDestroy: "{{ route('accounts.bulkDestroy') }}",
        export: "{{ route('accounts.export') }}",
    };

    // --- Main Application State ---
    const appState = {
        accounts: [], // Flat list of all accounts
        accountMap: new Map(), // For quick lookup by account_number
        accountsTree: [], // Hierarchical tree for rendering
        currentAccount: null,
        editMode: false,
        selectedAccounts: new Set()
    };

    // --- Account Number Ranges ---
    const ACCOUNT_NUMBER_RANGES = {
        'Assets': { min: 100000, max: 199999 },
        'Liabilities': { min: 200000, max: 299999 },
        'Equity': { min: 300000, max: 399999 },
        'Revenue': { min: 400000, max: 499999 },
        'Cost of Sales': { min: 500000, max: 599999 },
        'Operating Expenses': { min: 600000, max: 699999 },
        'Other Expenses': { min: 700000, max: 799999 },
        'Other Income': { min: 800000, max: 899999 },
        'Year-End Adjustments & Closing Entries': { min: 900000, max: 999999 }
    };

    // --- Badge Mapping ---
    const GROUP_BADGES = {
        'Assets': 'assets-badge',
        'Liabilities': 'liabilities-badge',
        'Equity': 'equity-badge',
        'Revenue': 'revenue-badge',
        'Cost of Sales': 'cost-of-sales-badge',
        'Operating Expenses': 'operating-expenses-badge',
        'Other Expenses': 'other-expenses-badge',
        'Other Income': 'other-income-badge',
        'Year-End Adjustments & Closing Entries': 'year-end-adjustments-&-closing-entries-badge'
    };

    // --- 1. DATA FETCHING AND PROCESSING ---

    /**
     * Fetches all account data, processes it, and triggers rendering.
     */
    async function fetchAccountData() {
        try {
            const response = await fetch(accountRoutes.index);
            const data = await response.json();

            if (data && Array.isArray(data)) {
                // 1. Store the flat list and create the ID-based map
                appState.accounts = data;
                // --- FIX: Key the map by account.id, not account_number ---
                appState.accountMap = new Map(data.map(acc => [acc.id, acc])); 
                
                // 2. Build the tree
                appState.accountsTree = buildAccountTree(data);
                
                // 3. Render the list
                renderAccountList();

                // 4. Populate form dropdowns AFTER all data is processed
                handleGroupChange();
            } else {
                console.error('Invalid data format received:', data);
                showMessage('Error loading account data', 'error');
            }
        } catch (error) {
            console.error('Error fetching account data:', error);
            showMessage('Error loading account data', 'error');
        }
    }

    /**
     * Builds a nested tree from the flat account list.
     */
    function buildAccountTree(accounts) {
        const map = new Map();
        const roots = [];

        // 1. Create a map of all accounts, keyed by their ID,
        //    and add an empty 'children' array to each.
        accounts.forEach(account => {
            map.set(account.id, { ...account, children: [] });
        });
        
        // 2. Create a *second* map to find parents by their account_number
        const parentNumberMap = new Map();
        accounts.forEach(account => {
            if (account.account_number !== null) {
                parentNumberMap.set(account.account_number, map.get(account.id));
            }
        });

        // 3. Iterate over the ID-map to link children to parents
        map.forEach(account => {
            if (account.parent_account_number !== null) {
                // This account has a parent. Find the parent object.
                const parent = parentNumberMap.get(account.parent_account_number);
                
                if (parent) {
                    // We found the parent. Add this account as a child.
                     parent.children.push(account);
                } else {
                    // This account's parent_account_number doesn't exist.
                    // It's an orphan, so treat it as a root.
                    roots.push(account);
                }
            } else {
                // This account has parent_account_number = NULL. It's a root.
                roots.push(account);
            }
        });
        
        // Sort roots by ID to preserve original order
        return roots.sort((a, b) => a.id - b.id);
    }

    /**
     * Renders the entire account list as a hierarchical table.
     */
    function renderAccountList() {
        const tableBody = document.querySelector("#defaultAccountsList tbody");
        tableBody.innerHTML = "";
        
        const counters = { total: 0, active: 0 };

        // appState.accountsTree IS the list of all root elements.
        // We just iterate over them and render them.
        appState.accountsTree.forEach(rootAccount => {
            
            // Check if it's a main group header (like Assets)
            if (rootAccount.parent_account_number === null && rootAccount.account_number !== null) {
                const groupHeader = document.createElement("tr");
                groupHeader.className = "account-group-header";
                groupHeader.innerHTML = `<td colspan="6">${rootAccount.account_type}</td>`;
                tableBody.appendChild(groupHeader);
                
                // Recursively render this group's children
                if (rootAccount.children && rootAccount.children.length > 0) {
                    rootAccount.children.sort((a,b) => a.id - b.id).forEach(child => {
                        renderAccountRow(child, tableBody, 1);
                    });
                }
            } else {
                // This is a "label" row or an "orphan" account
                // Just render it at the top level
                renderAccountRow(rootAccount, tableBody, 0); 
            }
        });

        // Update counts
        appState.accounts.forEach(acc => {
            counters.total++;
            if (acc.is_active) counters.active++;
        });
        document.getElementById("accountCount").textContent = counters.total;
        document.getElementById("activeAccountCount").textContent = counters.active;

        initCheckboxes();
    }

    /**
     * Recursively renders a single account row and its children.
     */
    function renderAccountRow(account, tableBody, level) {
        
        if (account.account_number === null) {
            const labelRow = document.createElement("tr");
            labelRow.className = "account-label-header";
            labelRow.dataset.id = account.id;
            const indent = level * 20;

            labelRow.innerHTML = `
                <td><input type="checkbox" class="account-checkbox" data-id="${account.id}"></td>
                <td colspan="5" style="padding-left: ${indent + 16}px;">
                    ${account.account_type}
                </td>
            `;
            tableBody.appendChild(labelRow);

            // Add click listener just for selection
            labelRow.querySelector(".account-checkbox").addEventListener("change", function(e) {
                    e.stopPropagation();
                    toggleAccountSelection(this.dataset.id, this.checked);
                    toggleBulkActions();
            });

            // Render children of this label
            if (account.children && account.children.length > 0) {
                account.children.sort((a,b) => a.id - b.id).forEach(child => {
                    renderAccountRow(child, tableBody, level + 1); 
                });
            }
            return; // Stop execution for this row
        }

        // --- Existing code for regular account rows ---
        const isActive = account.is_active;
        const row = document.createElement("tr");
        row.dataset.id = account.id;
        row.dataset.number = account.account_number;
        if (!isActive) row.classList.add("inactive-account");

        const badgeClass = GROUP_BADGES[account.account_group] || 'assets-badge';
        const indent = level * 20;

        row.innerHTML = `
            <td><input type="checkbox" class="account-checkbox" data-id="${account.id}"></td>
            <td><span class="account-number">${account.account_number}</span></td>
            <td>
                <div style="display: flex; align-items: center; padding-left: ${indent}px;">
                    <span class="account-group-badge ${badgeClass}">${account.account_group.charAt(0)}</span>
                    ${account.account_type}
                </div>
            </td>
            <td><span class="account-type ${account.is_parent ? 'parent' : ''}">${account.is_parent ? "Parent" : "Sub"}</span></td>
            <td>
                <span class="status-badge status-${isActive ? 'active' : 'inactive'}">
                    ${isActive ? 'Active' : 'Inactive'}
                </span>
            </td>
            <td>
                <div class="action-buttons">
                    <button class="action-btn edit-btn" title="Edit">
                        <span class="material-icons">edit</span>
                    </button>
                    <button class="action-btn delete delete-btn" title="Delete">
                        <span class="material-icons">delete</span>
                    </button>
                </div>
            </td>
        `;

        // --- Add Event Listeners ---
        row.addEventListener("click", (e) => {
            if (!e.target.closest('.action-btn') && !e.target.closest('.account-checkbox')) {
                populateFormFromAccount(account);
                document.getElementById('formIcon').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
        row.querySelector(".edit-btn").addEventListener("click", (e) => {
            e.stopPropagation();
            enterEditMode(account);
        });
        row.querySelector(".delete-btn").addEventListener("click", (e) => {
            e.stopPropagation();
            deleteAccount(account.id, account.account_type);
        });

        tableBody.appendChild(row);

        // Render children
        if (account.children && account.children.length > 0) {
            account.children.sort((a,b) => a.id - b.id).forEach(child => {
                renderAccountRow(child, tableBody, level + 1);
            });
        }
    }


    // --- 3. FORM HANDLING ---

    /**
     * Populates form dropdowns based on selected Account Category.
     */
    function handleGroupChange() {
        const groupSelect = document.getElementById("accountGroup");
        const detailTypeSelect = document.getElementById("accountType");
        const parentSelect = document.getElementById("parentAccount");
        const selectedGroup = groupSelect.value;

        // Reset dependent dropdowns
        detailTypeSelect.innerHTML = '<option value="" disabled selected>Select Account Type</option>';
        parentSelect.innerHTML = '<option value="" disabled selected>Select Parent Account</option>';

        if (!selectedGroup) return;
        
        // Add "Create New" options
        detailTypeSelect.add(new Option('-- Create New Account Type --', 'create-new'));
        parentSelect.add(new Option('-- (No Parent) --', ''));

        // Populate dropdowns with accounts from the selected group
        appState.accounts.filter(acc => acc.account_group === selectedGroup).forEach(acc => {
            // Don't add the NULL "label" rows to the dropdown
            if(acc.account_number === null) return; 

            const optionText = `${acc.account_number} - ${acc.account_type}`;
            const option = new Option(optionText, acc.account_number);
            
            // Add to Account Type dropdown
            detailTypeSelect.add(option);

            // Add to Parent Account dropdown if it's a parent
            if (acc.is_parent) {
                const parentOption = new Option(optionText, acc.account_number);
                parentOption.className = "parent-account";
                parentSelect.add(parentOption);
            }
        });

        updatePreview();
    }
    
    /**
     * Shows/hides custom fields when "Create New" is selected.
     */
    function handleAccountTypeChange() {
        const detailTypeSelect = document.getElementById("accountType");
        const customFields = document.getElementById("customAccountTypeFields");
        
        if (detailTypeSelect.value === 'create-new') {
            customFields.classList.remove("hidden");
            // Suggest an account number
            const accountGroup = document.getElementById("accountGroup").value;
            document.getElementById("customAccountNumber").value = suggestAccountNumber(accountGroup);
        } else {
            customFields.classList.add("hidden");
        }
        updatePreview();
    }

    /**
     * Populates the form when an account is clicked or edited.
     */
    function populateFormFromAccount(account) {
        if (!account) return;

        // Don't allow editing the "label" rows
        if(account.account_number === null) {
            showMessage('This is a section label and cannot be edited.', 'info');
            return;
        }

        document.getElementById("accountId").value = account.id;
        document.getElementById("accountGroup").value = account.account_group;
        
        // Trigger group change to populate dropdowns, then set values
        handleGroupChange();
        
        document.getElementById("accountType").value = account.account_number;
        document.getElementById("isParent").value = account.is_parent ? "1" : "0";
        document.getElementById("parentAccount").value = account.parent_account_number || "";
        document.getElementById("description").value = account.description || '';

        // Hide custom fields
        document.getElementById("customAccountTypeFields").classList.add("hidden");

        updatePreview();
    }
    
    /**
     * Enters edit mode.
     */
    function enterEditMode(account) {
        // Don't allow editing the "label" rows
        if(account.account_number === null) {
            showMessage('This is a section label and cannot be edited.', 'info');
            return;
        }

        appState.editMode = true;
        appState.currentAccount = account;

        document.getElementById("formTitle").textContent = "Edit Account";
        document.getElementById("formIcon").textContent = "edit";
        document.getElementById("cancelEditBtn").classList.remove("hidden");
        document.getElementById("deleteAccountBtn").classList.remove("hidden");

        populateFormFromAccount(account);
        document.getElementById('formIcon').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    /**
     * Exits edit mode and resets the form.
     */
    function exitEditMode() {
        appState.editMode = false;
        appState.currentAccount = null;

        document.getElementById("formTitle").textContent = "Create New Account";
        document.getElementById("formIcon").textContent = "add_circle";
        document.getElementById("cancelEditBtn").classList.add("hidden");
        document.getElementById("deleteAccountBtn").classList.add("hidden");

        resetForm();
    }

    /**
     * Resets the form to its initial state.
     */
    function resetForm() {
        document.getElementById("accountForm").reset();
        document.getElementById("accountId").value = "";
        document.getElementById("accountType").innerHTML = '<option value="" disabled selected>Select Category first</option>';
        document.getElementById("parentAccount").innerHTML = '<option value="" disabled selected>Select Parent Account</option>';
        document.getElementById("customAccountTypeFields").classList.add("hidden");
        updatePreview();
        exitEditMode(); // Ensure edit mode is fully exited
    }

    // --- 4. PREVIEW LOGIC ---

    /**
     * Updates the account preview based on form state.
     */
/**
     * Updates the account preview based on form state.
     */
    function updatePreview() {
        const previewContent = document.getElementById("accountPreviewContent");
        const group = document.getElementById("accountGroup").value;
        if (!group) {
            previewContent.innerHTML = `
                <div class="empty-preview">
                    <span class="material-icons">description</span>
                    <p>Select an account category to begin</p>
                </div>`;
            return;
        }

        // Get account data from form
        const { name, number, isParent } = getAccountDataFromForm();
        
        // --- FIX: Get parent ID from the dropdown, then find the full parent object ---
        const parentSelect = document.getElementById("parentAccount");
        const parentId = parentSelect.value;
        const parentAccount = parentId ? appState.accountMap.get(parseInt(parentId)) : null;

        let html = `<div class="account-group-title">
                        <span class="material-icons">folder</span>
                        ${group}
                    </div>
                    <ul class="account-hierarchy">`;
        
        const accountHtml = `
            <div class="account-name">
                <span class="material-icons ${isParent ? 'folder-icon' : 'account-icon'}">
                    ${isParent ? 'folder' : 'description'}
                </span>
                <span class="account-number">${number || '[Number]'}</span>
                ${name || '[Account Name]'}
                ${isParent ? '<span class="account-type parent">(Parent Account)</span>' : ''}
            </div>`;

        if (parentAccount) {
            // Build hierarchy from parent
            let hierarchy = [];
            let currentParent = parentAccount;
            
            while(currentParent) {
                hierarchy.unshift(currentParent);
                // --- FIX: Find the next parent using its ID from the accountMap ---
                const nextParentObj = appState.accounts.find(acc => acc.account_number === currentParent.parent_account_number);
                currentParent = nextParentObj ? appState.accountMap.get(nextParentObj.id) : null;
            }

            let nestedHtml = '';
            hierarchy.forEach(parent => {
                nestedHtml += `
                    <li>
                        <div class="account-name">
                            <span class="material-icons folder-icon">folder</span>
                            <span class="account-number">${parent.account_number}</span>
                            ${parent.account_type}
                        </div>
                        <ul class="account-hierarchy">`;
            });

            nestedHtml += `<li>${accountHtml}</li>`;
            nestedHtml += "</ul></li>".repeat(hierarchy.length);
            html += nestedHtml;

        } else {
            // No parent selected, show at root of group
            html += `<li>${accountHtml}</li>`;
        }

        html += '</ul>';
        previewContent.innerHTML = html;
    }

    /**
     * Helper to get current account info from the form.
     */
    function getAccountDataFromForm() {
        const detailTypeSelect = document.getElementById("accountType");
        const isParent = document.getElementById("isParent").value === "1";
        
        if (detailTypeSelect.value === 'create-new') {
            return {
                name: document.getElementById("customAccountName").value,
                number: document.getElementById("customAccountNumber").value,
                isParent: isParent
            };
        }
        
        // --- FIX: Find account by ID from the map, not by parsing the value ---
        const selectedAccountNumber = detailTypeSelect.value; // This is the account_number
        const selectedAccount = appState.accounts.find(acc => acc.account_number == selectedAccountNumber);

        if (selectedAccount) {
            return {
                name: selectedAccount.account_type,
                number: selectedAccount.account_number,
                isParent: isParent // Use form value for role
            };
        }
        
        return { name: '', number: '', isParent: isParent };
    }

    // --- 5. CRUD OPERATIONS ---

    /**
     * Prepares account data for saving (create or update).
     */
    function prepareAccountData() {
        const accountGroup = document.getElementById("accountGroup").value;
        const detailType = document.getElementById("accountType").value;
        const isParent = document.getElementById("isParent").value === "1";
        const parentAccountNumber = document.getElementById("parentAccount").value || null;
        
        let accountNumber, accountName;

        if (detailType === 'create-new') {
            accountNumber = document.getElementById("customAccountNumber").value;
            accountName = document.getElementById("customAccountName").value;

            if (!accountNumber || !accountName) {
                showMessage('Please enter both Account Number and Name', 'error');
                return null;
            }
            // Add validation
            const validation = validateAccountNumber(accountNumber, accountGroup);
            if (!validation.valid) {
                showMessage(validation.message, 'error');
                return null;
            }
        } else {
            const selectedAccount = appState.accountMap.get(parseInt(detailType));
            if (!selectedAccount) {
                showMessage('Please select a valid Account Type', 'error');
                return null;
            }
            accountNumber = selectedAccount.account_number;
            accountName = selectedAccount.account_type;
        }

        return {
            id: document.getElementById("accountId").value || null,
            account_group: accountGroup,
            account_type: accountName,
            account_number: accountNumber,
            is_parent: isParent ? 1 : 0,
            parent_account_number: parentAccountNumber,
            description: document.getElementById("description").value,
            is_active: 1 // Default to active
        };
    }

    /**
     * Saves (POST) or Updates (PUT) an account.
     */
    async function saveAccountToDatabase(accountData) {
        const saveBtn = document.getElementById("saveAccountBtn");
        const originalBtnText = saveBtn.innerHTML;
        
        try {
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<span class="spinner"></span> Saving...';
            
            const endpoint = appState.editMode 
                ? accountRoutes.update.replace('__ID__', accountData.id)
                : accountRoutes.store;
            const method = appState.editMode ? 'PUT' : 'POST';
            
            const response = await fetch(endpoint, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify(accountData)
            });
            
            const data = await response.json();
            if (data.success) {
                showMessage(`Account ${appState.editMode ? 'updated' : 'saved'}!`, 'success');
                await fetchAccountData(); // Refresh all data
                resetForm();
            } else {
                showMessage(`Error: ${data.message || 'Unknown error'}`, 'error');
            }
        } catch (error) {
            console.error('Save Account Error:', error);
            showMessage('Error saving account: ' + error.message, 'error');
        } finally {
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalBtnText;
        }
    }

    /**
     * Deletes a single account.
     */
    async function deleteAccount(accountId, accountName) {
        if (!confirm(`Are you sure you want to delete "${accountName}"? This cannot be undone.`)) {
            return;
        }

        try {
            const response = await fetch(accountRoutes.destroy.replace('__ID__', accountId), {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            });

            const data = await response.json();
            if (data.success) {
                showMessage('Account deleted successfully!', 'success');
                await fetchAccountData();
                resetForm();
            } else {
                showMessage('Error: ' + (data.message || 'Could not delete account'), 'error');
            }
        } catch (error) {
            console.error('Error deleting account:', error);
            showMessage('Error deleting account: ' + error.message, 'error');
        }
    }

    /**
     * Deletes all selected accounts.
     */
    async function bulkDeleteAccounts() {
        try {
            const response = await fetch(accountRoutes.bulkDestroy, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ ids: Array.from(appState.selectedAccounts) })
            });
            
            const data = await response.json();
            if (data.success) {
                showMessage(`Successfully deleted ${data.deletedCount} accounts`, 'success');
                appState.selectedAccounts.clear();
                toggleBulkActions();
                await fetchAccountData();
            } else {
                showMessage('Error: ' + (data.message || 'Could not delete accounts'), 'error');
            }
        } catch (error) {
            console.error('Error deleting accounts:', error);
            showMessage('Error deleting accounts: ' + error.message, 'error');
        }
    }

    // --- 6. UTILITIES AND HELPERS ---

    /**
     * Validates a new account number.
     */
    function validateAccountNumber(accountNumber, accountType) {
        if (!accountNumber || isNaN(accountNumber)) {
            return { valid: false, message: 'Account number must be a number' };
        }
        
        const num = parseInt(accountNumber);
        const range = ACCOUNT_NUMBER_RANGES[accountType];
        
        if (!range) {
            return { valid: false, message: 'Invalid account category' };
        }
        
        if (num < range.min || num > range.max) {
            return { 
                valid: false, 
                message: `Number must be between ${range.min} and ${range.max} for ${accountType}`
            };
        }
        
        if (appState.accountMap.has(num)) {
            return { valid: false, message: 'Account number already exists' };
        }
        
        return { valid: true };
    }

    /**
     * Suggests the next available account number in a category.
     */
    function suggestAccountNumber(accountType) {
        const range = ACCOUNT_NUMBER_RANGES[accountType];
        if (!range) return '';

        // Find the highest existing number in this range
        let maxNum = range.min - 1;
        appState.accounts.forEach(acc => {
            if (acc.account_group === accountType && acc.account_number > maxNum) {
                maxNum = acc.account_number;
            }
        });

        // Suggest the next number, usually +10
        const nextNum = (Math.floor(maxNum / 10) * 10) + 10;
        
        return nextNum <= range.max ? nextNum : '';
    }
    
    /**
     * Shows a temporary message to the user.
     */
    function showMessage(message, type) {
        const messageContainer = document.getElementById("messageContainer");
        const icon = type === 'success' ? 'check_circle' : 'error';
        messageContainer.innerHTML = `
            <div class="message ${type}-message">
                <span class="material-icons">${icon}</span>
                ${message}
            </div>`;
        
        setTimeout(() => { messageContainer.innerHTML = ''; }, 5000);
    }
    
    /**
     * Handles 'Select All' checkbox and individual checkboxes.
     */
    function initCheckboxes() {
        const selectAll = document.getElementById("selectAllCheckbox");
        const checkboxes = document.querySelectorAll(".account-checkbox");

        selectAll.addEventListener("change", function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
                toggleAccountSelection(checkbox.dataset.id, checkbox.checked);
            });
            toggleBulkActions();
        });

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener("change", function() {
                toggleAccountSelection(this.dataset.id, this.checked);
                toggleBulkActions();
            });
        });
    }

    function toggleAccountSelection(accountId, isSelected) {
        if (isSelected) {
            appState.selectedAccounts.add(accountId);
        } else {
            appState.selectedAccounts.delete(accountId);
            document.getElementById("selectAllCheckbox").checked = false;
        }
    }

    function toggleBulkActions() {
        const bulkActions = document.getElementById("bulkActions");
        bulkActions.classList.toggle("hidden", appState.selectedAccounts.size === 0);
    }

    // --- 7. INITIALIZATION ---
    document.addEventListener('DOMContentLoaded', function() {
        // --- Form Event Listeners ---
        document.getElementById("accountGroup").addEventListener("change", handleGroupChange);
        document.getElementById("accountType").addEventListener("change", handleAccountTypeChange);
        
        // Update preview on all relevant form changes
        ["isParent", "parentAccount", "customAccountNumber", "customAccountName"]
            .forEach(id => document.getElementById(id).addEventListener("change", updatePreview));
        ["customAccountNumber", "customAccountName"]
            .forEach(id => document.getElementById(id).addEventListener("input", updatePreview));

        // --- Form Action Listeners ---
        document.getElementById("saveAccountBtn").addEventListener("click", async () => {
            const accountData = prepareAccountData();
            if (accountData) {
                await saveAccountToDatabase(accountData);
            }
        });
        document.getElementById("cancelEditBtn").addEventListener("click", exitEditMode);
        document.getElementById("deleteAccountBtn").addEventListener("click", () => {
            if (appState.currentAccount) {
                deleteAccount(appState.currentAccount.id, appState.currentAccount.account_type);
            }
        });

        // --- Table Action Listeners ---
        document.getElementById("accountSearch").addEventListener("input", function() {
            const searchTerm = this.value.toLowerCase();
            document.querySelectorAll("#defaultAccountsList tbody tr").forEach(row => {
                // Ignore group header rows from search
                if (row.classList.contains('account-group-header') || row.classList.contains('account-label-header')) {
                    row.style.display = ""; // Always show headers/labels
                    return;
                }
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? "" : "none";
            });
        });

        document.getElementById("exportBtn").addEventListener("click", () => {
             window.location.href = accountRoutes.export;
        });

        // --- Bulk Action Listeners ---
        document.getElementById("bulkDeleteBtn").addEventListener("click", bulkDeleteAccounts);
        document.getElementById("bulkEditBtn").addEventListener("click", () => {
             showMessage('Bulk edit functionality not yet implemented', 'info');
        });
        document.getElementById("cancelBulkAction").addEventListener("click", () => {
            appState.selectedAccounts.clear();
            document.getElementById("selectAllCheckbox").checked = false;
            document.querySelectorAll(".account-checkbox").forEach(cb => cb.checked = false);
            toggleBulkActions();
        });
        
        // --- Load Initial Data ---
        fetchAccountData();
    });

</script>
@endpush