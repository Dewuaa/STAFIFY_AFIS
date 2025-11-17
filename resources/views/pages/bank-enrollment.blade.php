@extends('layouts.app')

@section('title', 'Bank Enrollment')

@push('styles')
    <style>
        /* --- 1. Root Variables (Consistent) --- */
        :root {
            --primary-color: #3B82F6; /* Staffify blue */
            --primary-color-hover: #184278;
            --secondary-color: #f1f5f9; /* Light grey for secondary buttons */
            --secondary-color-hover: #e2e8f0;
            --danger-color: #e74c3c;
            --danger-color-hover: #c0392b;
            --warning-color: #f39c12;
            --warning-color-hover: #e67e22;
            --success-color: #4caf50;
            
            --text-color: #334155;
            --text-color-light: #64748b;
            --border-color: #e2e8f0;
            --white: #ffffff;
            
            --border-radius: 6px;
            --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.07);
            --transition: all 0.2s ease-in-out;
        }

        /* --- 2. General Page Layout (Consistent) --- */
        .bank-enrollment-page {
            padding: 0;
            max-width: 100%;
            margin: 0 auto;
        }

        .page-header {
            display: flex;
            justify-content: flex-start; /* Button moved left */
            align-items: center;
            margin-bottom: 24px;
        }
        
        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--text-color);
            margin: 0;
        }
        
        .content-card {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden; /* For table border-radius */
            padding: 24px;
        }

        /* --- 3. Unified Button System (Consistent) --- */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 600;
            border-radius: var(--border-radius);
            border: 1px solid transparent;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            white-space: nowrap;
        }
        
        .btn svg {
            width: 16px;
            height: 16px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: var(--white);
        }
        .btn-primary:hover {
            background-color: var(--primary-color-hover);
        }

        .btn-secondary {
            background-color: var(--danger-color);
            color: var(--white);
            border-color: var(--border-color);
        }
        .btn-secondary:hover {
            background-color: var(--danger-color-hover);
        }
        
        .btn-icon {
            padding: 8px;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: var(--secondary-color);
            color: var(--text-color-light);
            border: none;
        }
        .btn-icon:hover {
            background-color: var(--primary-color);
            color: var(--white);
        }
        .btn-icon.delete:hover {
            background-color: var(--danger-color);
        }
        
        .btn-group {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        /* --- 4. Bank List Table (Consistent) --- */
        .banks-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .banks-table th,
        .banks-table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-color);
            font-size: 14px;
        }

        .banks-table th {
            background-color: #f8f9fa;
            color: var(--text-color-light);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
        }

        .banks-table tr:hover {
            background-color: var(--secondary-color); /* Visible hover */
        }

        .banks-table .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: flex-start; /* Aligns icons to the left */
        }

        /* --- 5. Stat Cards  --- */
        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(4, 1fr); 
            gap: 20px; 
            margin-bottom: 24px; 
        }
        .stat-card { 
            text-align: center; 
            padding: 20px; 
            border-radius: var(--border-radius); 
            background-color: var(--white); /* Changed to white */
            border: 1px solid var(--border-color);
            box-shadow: var(--box-shadow);
        }
        .stat-icon-wrapper {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px auto;
        }
        .stat-icon-wrapper svg {
            width: 24px;
            height: 24px;
        }
        
        /* Icon Colors */
        .icon-php-savings {
            background-color: whitesmoke;
            color: var(--primary-color);
        }
        .icon-php-current {
            background-color: #e0f8f0; /* Light green */
            color: #10b981;
        }
        .icon-dollar-savings {
            background-color: #fffbeb; /* Light yellow */
            color: #f59e0b;
        }
        .icon-dollar-current {
            background-color: #fee2e2; /* Light red */
            color: #ef4444;
        }
        
        .stat-label { 
            font-size: 14px; 
            font-weight: 600; 
            text-transform: uppercase; 
            margin-bottom: 8px; 
            color: var(--text-color-light); 
        }
        .stat-value { 
            font-size: 28px; 
            font-weight: 700; 
            margin: 0;
            color: var(--text-color); /* Changed to dark text */
        }

        /* --- 6. Unified Modal System (Consistent) --- */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: flex-start;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(3px);
            overflow-y: auto;
            padding: 40px 0;
        }

        .modal-content {
            background: var(--white);
            padding: 24px;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            position: relative;
            animation: modalFadeIn 0.3s ease-out;
            width: 90%;
            margin-bottom: 40px;
        }
        
        .modal-md { max-width: 600px; }
        .modal-sm { max-width: 500px; }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 24px;
        }
        
        .modal-header h2 {
            margin: 0;
            font-size: 1.25rem;
            color: var(--text-color);
            font-weight: 600;
        }
        
        .modal-close-btn {
            font-size: 24px;
            cursor: pointer;
            background: none;
            border: none;
            color: var(--text-color-light);
            padding: 0;
            line-height: 1;
        }
        .modal-close-btn:hover {
            color: var(--text-color);
        }
        
        /* --- 7. Form Styling (Consistent) --- */
        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 16px; /* Added for spacing inside forms */
        }

        .form-group label {
            font-weight: 600;
            color: var(--text-color);
            font-size: 14px;
            text-align: left;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="number"],
        .form-group select {
            width: 100%;
            padding: 10px;
            border-radius: var(--border-radius);
            border: 1px solid var(--border-color);
            font-size: 14px;
            transition: var(--transition);
            box-sizing: border-box; 
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(31, 84, 151, 0.2);
        }
        
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
        }
        
        /* --- 8. View Modal Specifics --- */
        .view-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            font-size: 14px;
        }
        .view-details-grid div {
            padding-bottom: 10px;
        }
        .view-details-grid strong {
            color: var(--text-color-light);
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .view-details-grid span {
            color: var(--text-color);
            font-weight: 500;
        }

        /* --- 9. Utility & Other Styles --- */
        .notification { 
            padding: 15px; 
            margin: 0 24px 24px 24px; 
            border-radius: var(--border-radius); 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
        }
        .notification.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .notification.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
        @media (max-width: 1200px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: 1fr; }
            .form-grid-2 { grid-template-columns: 1fr; }
            .view-details-grid { grid-template-columns: 1fr; }
        }
    </style>
@endpush

@section('content')
<div class="bank-enrollment-page">

    @if(!empty($message))
        <div class="notification {{ str_contains($message, 'Error') ? 'error' : 'success' }}">{{ $message }}</div>
    @elseif(session('message'))
        <div class="notification success">{{ session('message') }}</div>
    @endif

    <div class="page-header">
        @if(!isset($viewId))
            <button type="button" class="btn btn-primary" onclick="openModal()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Add Bank
            </button>
        @endif
    </div>
    
    <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon-wrapper icon-php-savings">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375" />
                    </svg>
                </div>
                <div class="stat-label">Savings - PHP</div>
                <div class="stat-value">{{ $savingsPhpCount ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-wrapper icon-php-current">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52a2.25 2.25 0 0 1-1.006 1.916l-7.5 4.25a2.25 2.25 0 0 1-2.475 0l-7.5-4.25A2.25 2.25 0 0 1 3 6.47V4.5A2.25 2.25 0 0 1 5.25 2.25h13.5A2.25 2.25 0 0 1 21 4.5v1.97" />
                    </svg>
                </div>
                <div class="stat-label">Current - PHP</div>
                <div class="stat-value">{{ $currentPhpCount ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-wrapper icon-dollar-savings">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div class="stat-label">Savings - Dollar</div>
                <div class="stat-value">{{ $savingsDollarCount ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-wrapper icon-dollar-current">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6V4.5m0 0v1.5A2.25 2.25 0 0 0 5.25 8.25h13.5A2.25 2.25 0 0 0 21 6v-1.5m0 0A2.25 2.25 0 0 0 18.75 2.25H5.25A2.25 2.25 0 0 0 3 4.5m.007 0H1.5M3.75 4.5v15m0 0v-3.44A48.354 48.354 0 0 1 12 15c2.65 0 5.193.303 7.5.875m-7.5.875V18.75m0 0A121.2 121.2 0 0 0 18.75 16.5m-15 0a120.67 120.67 0 0 1 7.5-1.125" />
                    </svg>
                </div>
                <div class="stat-label">Current - Dollar</div>
                <div class="stat-value">{{ $currentDollarCount ?? 0 }}</div>
            </div>
        </div>

    <div class="content-card">
        <div style="overflow-x: auto;">
            <table class="banks-table">
                <thead>
                    <tr>
                        <th>Bank ID</th>
                        <th>Bank Name</th>
                        <th>Account Name</th>
                        <th>Account No.</th>
                        <th>Account Type</th>
                        <th>Currency</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banks ?? [] as $bank)
                        <tr>
                            <td>{{ $bank->id }}</td>
                            <td>{{ $bank->bank_name }}</td>
                            <td>{{ $bank->account_name }}</td>
                            <td>{{ $bank->account_no }}</td>
                            <td>{{ $bank->account_type }}</td>
                            <td>{{ $bank->currency }}</td>
                            <td>
                                <div class="action-buttons btn-group">
                                    <button class="btn btn-icon" onclick="viewBank({{ $bank->id }})" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.634l3.061-5.636a.5.5 0 0 1 .44-.223h13.911a.5.5 0 0 1 .44.223l3.061 5.636a1.012 1.012 0 0 1 0 .634l-3.061 5.636a.5.5 0 0 1-.44.223H5.537a.5.5 0 0 1-.44-.223L2.036 12.322Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                    </button>
                                    <button class="btn btn-icon" onclick="editBank({{ $bank->id }})" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                    </button>
                                    <form method="POST" action="{{ route('banks.destroy', $bank->id) }}" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-icon delete" onclick="confirmRowDelete(this)" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12.502 0c-.411.011-.82.028-1.229.05c-3.17.198-6.096.657-8.634 1.183L3.15 5.79m14.456 0L13.8 3.51a2.25 2.25 0 0 0-3.182 0L6.844 5.79m11.136 0c-0.291-.01-0.582-.019-0.873-.028m-10.01 0c-.29.009-.581.018-.872.028" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #999;">No banks enrolled yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal-overlay" id="bankModal" style="{{ (isset($showModal) && $showModal) ? 'display: flex;' : 'display: none;' }}">
        <div class="modal-content modal-md">
            <div class="modal-header">
                <h2>{{ isset($editId) ? 'Edit Bank Account' : 'Add Bank Account' }}</h2>
                <!-- <button class="modal-close-btn" onclick="closeModal()">×</button> -->
            </div>
            
            <form method="post" action="{{ isset($editId) ? route('banks.update', $editId) : route('banks.store') }}" id="bankForm">
                @csrf
                @if(isset($editId))
                    @method('PUT')
                @endif
                
                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="bank_name">Bank Name</label>
                        <input type="text" id="bank_name" name="bank_name" placeholder="BPI, BDO, UnionBank..." 
                               value="{{ isset($bankData) ? $bankData->bank_name : '' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="account_name">Account Name</label>
                        <input type="text" id="account_name" name="account_name" placeholder="Staffify Holdings Inc." 
                               value="{{ isset($bankData) ? $bankData->account_name : '' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="account_no">Account No.</label>
                        <input type="text" id="account_no" name="account_no" placeholder="1234-5678-9012" 
                               value="{{ isset($bankData) ? $bankData->account_no : '' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="account_type">Account Type</label>
                        <select id="account_type" name="account_type" required>
                            <option value="" {{ !isset($bankData) ? 'selected' : '' }} disabled>Select account type</option>
                            <option value="Savings - PHP" {{ (isset($bankData) && $bankData->account_type == 'Savings - PHP') ? 'selected' : '' }}>Savings - PHP</option>
                            <option value="Current - PHP" {{ (isset($bankData) && $bankData->account_type == 'Current - PHP') ? 'selected' : '' }}>Current - PHP</option>
                            <option value="Savings - Dollar" {{ (isset($bankData) && $bankData->account_type == 'Savings - Dollar') ? 'selected' : '' }}>Savings - Dollar</option>
                            <option value="Current - Dollar" {{ (isset($bankData) && $bankData->account_type == 'Current - Dollar') ? 'selected' : '' }}>Current - Dollar</option>
                        </select>
                    </div>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">{{ isset($editId) ? 'Update Bank' : 'Add Bank' }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="viewBankModal" style="display: none;">
        <div class="modal-content modal-md">
            <div class="modal-header">
                <h2>Bank Account Details</h2>
                <!-- <button class="modal-close-btn" onclick="closeViewModal()">×</button> -->
            </div>
            
            <div class="view-details-grid">
                <div>
                    <strong>Bank ID:</strong>
                    <span id="view_bank_id">-</span>
                </div>
                <div>
                    <strong>Bank Name:</strong>
                    <span id="view_bank_name">-</span>
                </div>
                <div>
                    <strong>Account Name:</strong>
                    <span id="view_account_name">-</span>
                </div>
                <div>
                    <strong>Account No.:</strong>
                    <span id="view_account_no">-</span>
                </div>
                <div>
                    <strong>Account Type:</strong>
                    <span id="view_account_type">-</span>
                </div>
                <div>
                    <strong>Currency:</strong>
                    <span id="view_currency">-</span>
                </div>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeViewModal()">Close</button>
                <button type="button" class="btn btn-primary" onclick="editBankFromView()">Edit Bank</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentViewBankId = null;

    function openModal() { 
        document.getElementById('bankModal').style.display = 'flex'; 
    }

    function closeModal() { 
        document.getElementById('bankModal').style.display = 'none';
        @if(isset($editId))
            // Redirect back to the main bank page
            window.location.href = '{{ route('bank.enrollment') }}';
        @endif
    }

    function closeViewModal() {
        document.getElementById('viewBankModal').style.display = 'none';
        currentViewBankId = null;
        // Redirect back to the main bank page if we were on a view-specific URL
        @if(isset($viewId))
            window.location.href = '{{ route('bank.enrollment') }}';
        @endif
    }

    function viewBank(id) {
        // This function navigates to the view-enabled URL
        window.location.href = '{{ url('/banks') }}/' + id;
    }

    function editBank(id) {
        // This function navigates to the edit-enabled URL
        window.location.href = '{{ url('/banks') }}/' + id + '/edit';
    }

    function editBankFromView() {
        if (currentViewBankId) {
            editBank(currentViewBankId);
        }
    }

    function confirmRowDelete(btn) { 
        if (confirm('Are you sure you want to delete this bank account?')) { 
            btn.closest('form').submit(); 
        } 
    }

    // Close modal when clicking outside
    document.getElementById('bankModal').addEventListener('click', function(e) { 
        if (e.target === this) { 
            closeModal(); 
        } 
    });

    document.getElementById('viewBankModal').addEventListener('click', function(e) { 
        if (e.target === this) { 
            closeViewModal(); 
        } 
    });

    // If we're viewing a bank account, show it in view modal
    @if(isset($viewId) && isset($bankData))
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('view_bank_id').textContent = '{{ $bankData->id }}';
            document.getElementById('view_bank_name').textContent = '{{ $bankData->bank_name }}';
            document.getElementById('view_account_name').textContent = '{{ $bankData->account_name }}';
            document.getElementById('view_account_no').textContent = '{{ $bankData->account_no }}';
            document.getElementById('view_account_type').textContent = '{{ $bankData->account_type }}';
            document.getElementById('view_currency').textContent = '{{ $bankData->currency }}';
            currentViewBankId = {{ $viewId }};
            document.getElementById('viewBankModal').style.display = 'flex';
        });
    @endif

    // If we're in edit mode, ensure modal is open
    @if(isset($editId) && isset($showModal) && $showModal)
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('bankModal').style.display = 'flex';
        });
    @endif
</script>
@endpush