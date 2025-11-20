@extends('layouts.app')

@section('title', 'Petty Cash Voucher')
@section('description', 'Manage petty cash vouchers - Create, view, edit, and send vouchers to payees')

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
        .petty-cash-page {
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
            background-color: var(--white);
            color: var(--primary-color);
            border-color: var(--border-color);
        }
        .btn-secondary:hover {
            background-color: var(--secondary-color);
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: var(--white);
        }
        .btn-danger:hover {
            background-color: var(--danger-color-hover);
        }

        .btn-warning {
            background-color: var(--warning-color);
            color: var(--white);
        }
        .btn-warning:hover {
            background-color: var(--warning-color-hover);
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
        
        .btn-group {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .input-group-btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border: 1px solid var(--border-color);
            border-left: none;
            padding: 0 12px;
            background: #f8f9fa;
        }
        .input-group-btn:hover {
            background: #e9ecef;
        }

        /* --- 4. Voucher List Table (Consistent) --- */
        .vouchers-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .vouchers-table th,
        .vouchers-table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-color);
            font-size: 14px;
        }

        .vouchers-table th {
            background-color: #f8f9fa;
            color: var(--text-color-light);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
        }

        /* No alternating row color */
        .vouchers-table tr:hover {
            background-color: var(--secondary-color); /* Visible hover color */
        }

        .vouchers-table .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: flex-start;
        }

        /* --- 5. New Status Badge Styles (Consistent) --- */
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-approved {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .badge-signed {
            display: inline-block;
            margin-left: 5px;
            color: var(--success-color);
            font-weight: bold;
        }
        .signed-badge {
            color: var(--success-color);
            font-weight: bold;
            margin-bottom: 5px;
        }
        .receipt-signed {
            color: var(--success-color);
            font-weight: bold;
        }

        /* --- NEW: SUMMARY CARD STYLES --- */
    .summary-grid { 
        display: grid; 
        grid-template-columns: repeat(3, 1fr); /* 3 cards */
        gap: 20px; 
        margin-bottom: 24px; 
    }
    .summary-card-item { 
        text-align: center; 
        padding: 20px; 
        border-radius: var(--border-radius); 
        background-color: var(--white);
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
    .stat-icon-wrapper i { /* Target FontAwesome <i> tags */
        font-size: 20px;
    }
    .summary-label { 
        font-size: 14px; 
        font-weight: 600; 
        text-transform: uppercase; 
        margin-bottom: 8px; 
        color: var(--text-color-light); 
    }
    .summary-value { 
        font-size: 28px; 
        font-weight: 700; 
        margin: 0;
    }
    
    /* Icon Colors */
    .icon-disbursed {
        background-color: whitesmoke;
        color: var(--primary-color);
    }
    .icon-pending {
        background-color: #fef3c7; /* Light yellow */
        color: var(--warning-color);
    }
    .icon-approved {
        background-color: #d1fae5; /* Light green */
        color: #065f46;
    }
    
    /* Text Colors */
    .text-success { color: #065f46 !important; }
    .text-warning { color: #92400e !important; }
    .text-primary-custom { color: var(--primary-color) !important; }


        /* --- 6. Unified Modal System (REVERTED to Centered) --- */
       .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            /* REVERTED: Center the modal on screen */
            align-items: center; 
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(3px);
            transition: opacity 0.3s ease-out;
        }

        .modal-content {
            background: var(--white);
            padding: 24px;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            position: relative;
            animation: modalFadeIn 0.3s ease-out;
            width: 90%;
            /* REVERTED: Add max-height and scrolling to the modal itself */
            max-height: 90vh; /* 90% of the viewport height */
            overflow-y: auto;
        }

        /* Modal Sizes */
        .modal-lg { max-width: 1100px; } /* This is the one for the voucher modal */
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
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 16px;
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
        .form-group input[type="date"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border-radius: var(--border-radius);
            border: 1px solid var(--border-color);
            font-size: 14px;
            transition: var(--transition);
            box-sizing: border-box;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(31, 84, 151, 0.2);
        }
        
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .input-group {
            display: flex;
        }
        .input-group select,
        .input-group input {
            flex-grow: 1;
        }
        .input-group select {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
        }

        /* --- 8. Voucher Create/Edit Modal (Specifics) --- */
        .voucher-modal-layout {
            display: flex;
            gap: 30px;
        }
        .voucher-form-pane {
            flex: 1;
            min-width: 400px;
        }
        .voucher-preview-pane {
            flex: 1;
            background: #f8f9fa;
            border-radius: var(--border-radius);
            padding: 24px;
            border: 1px solid var(--border-color);
            max-height: 70vh;
            overflow-y: auto;
            position: relative; /* For watermark */
        }
        
        .voucher-logo {
            margin-right: 20px; 
            position: relative;
        }

        .voucher-logo img {
            height: 25px;
        }

        /* 🚀 NEW: Watermark styles */
        .voucher-preview-pane::before,
        .voucher-document::before {
            content: '';
            position: absolute;
            top: 24px;
            left: 24px;
            width: 150px; 
            height: 150px;
            background-image: url("{{ asset('assets/images/Staffy-Logo.png') }}");
            background-repeat: no-repeat;
            background-position: top left;
            background-size: contain;
            opacity: 0.08; /* Subtle watermark */
            z-index: 1;
            pointer-events: none; /* Allows clicking through it */
        }

        /* 🚀 NEW: Wrapper for preview content */
        .voucher-preview-content,
        .voucher-document-content {
            position: relative;
            z-index: 2; /* Ensures content is above watermark */
        }

        @media (max-width: 992px) {
            .voucher-modal-layout {
                flex-direction: column;
            }
            .voucher-preview-pane {
                max-height: 500px;
            }
        }

        /* --- 9. Voucher View Page (Consistent) --- */
        .voucher-view-actions {
            margin-bottom: 24px;
            padding: 16px 24px;
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-left: 24px;
            margin-right: 24px;
        }

        .voucher-document {
            background: var(--white);
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            max-width: 800px;
            margin: 0 auto 24px auto;
            position: relative; /* For watermark */
        }
        
        /* 🚀 IMPROVEMENT: Voucher Preview Styles */
        .voucher-preview-pane h2, .voucher-document h2 {
            color: var(--primary-color);
            margin-top: 0;
            font-size: 1.5rem; /* Bigger title */
            font-weight: 700;
            text-align: left;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 10px;
        }
        .voucher-header-info {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .voucher-company-info {
            line-height: 1.6;
            text-align: left;
        }
        .voucher-meta-info {
            text-align: right;
            flex-shrink: 0;
            padding-left: 20px;
        }
        .voucher-meta-info p {
            margin: 0 0 5px 0;
            font-weight: 500;
        }

        .voucher-details {
            margin: 20px 0;
            border: 1px solid var(--border-color); /* Add a border */
            border-radius: var(--border-radius);
            overflow: hidden; /* For border-radius */
            background: var(--white); /* White background for rows */
        }
        .detail-row {
            display: flex;
            padding: 12px; /* Increased padding */
            border-bottom: 1px solid var(--border-color);
            font-size: 14px;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-row:nth-child(even) {
            background: #fdfdfd; /* Very light alternating color */
        }
        .detail-label {
            width: 30%;
            min-width: 120px; /* Ensure label width */
            font-weight: 600; /* Bolder label */
            color: var(--text-color-light);
            padding-right: 10px;
            flex-shrink: 0;
            text-align: left;
        }
        .detail-value {
            width: 70%;
            color: var(--text-color);
            font-weight: 500;
            word-break: break-word; /* Handle long content */
            text-align: right;
        }
        .highlight { /* This is for the Amount */
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.25rem; /* Make amount stand out */
        }
        
        .contact-info {
            font-size: 14px;
            line-height: 1.6;
            margin-top: 20px;
            text-align: left;
        }
        .notes {
            font-size: 14px;
            margin-top: 20px;
            text-align: left;
        }
        .notes p:first-child {
            margin-top: 0;
        }
        .notes p:last-child {
            margin-bottom: 0;
            min-height: 40px;
        }
        
        .signature {
            font-size: 9px;
            margin-top: 40px; 
            display: flex; 
            justify-content: space-between;
            gap: 20px; /* Add gap */
        }
        .signature-block {
            width: 30%; 
            text-align: center;
            padding-top: 8px; /* Space above text */
        }
        .signature-block .signature-name {
            min-height: 20px;
            font-weight: 600;
            color: var(--text-color);
            border-bottom: 1px solid #333; /* Darker line */
            padding-bottom: 5px;
            margin-bottom: 5px;
        }
        .signature-block .signature-title {
            font-size: 12px;
            color: var(--text-color-light);
            text-transform: uppercase;
        }
        .signature-block .signature-image {
            max-width: 180px; 
            max-height: 50px; 
            border: 1px solid #ccc; 
            background: white; 
            margin: 5px 0;
        }
        .signature-block .signature-date {
            font-size: 11px;
            color: var(--text-color-light);
        }

        /* --- 10. Utility & Other Styles --- */
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: var(--border-radius);
            margin: 0 24px 24px 24px;
        }
        .pdf-loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            display: none;
        }
        .pdf-loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid var(--secondary-color);
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
@endpush

@section('content')
<div class="petty-cash-page">
    
    <div class="modal-overlay" id="categoryModal" style="{{ $showCategoryModal ? 'display: flex;' : 'display: none;' }}">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <h2>Add New Expense Category</h2>
                <button class="modal-close-btn" onclick="window.location.href='{{ route('billing.petty.cash') }}'">×</button>
            </div>
            <form method="POST" action="{{ route('petty.cash.category.store') }}">
                @csrf
                <div class="form-group">
                    <label>Category Name</label>
                    <input type="text" name="category_name" required>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="emailModal" style="display: none;">
        <div class="modal-content modal-md">
            <div class="modal-header">
                <h2>Send Voucher via Email</h2>
                <button class="modal-close-btn" onclick="closeEmailModal()">×</button>
            </div>
            <form method="POST" action="{{ route('petty.cash.send-email') }}">
                @csrf
                <input type="hidden" name="voucher_id" id="email_voucher_id" value="">
                
                <div class="form-group">
                    <label>Recipient Email</label>
                    <input type="email" name="email_recipient" id="email_recipient" required>
                </div>
                
                <div class="form-group">
                    <label>Subject</label>
                    <input type="text" name="email_subject" value="Petty Cash Voucher from Stafffy Inc" required>
                </div>
                
                <div class="form-group">
                    <label>Message</label>
                    <textarea name="email_message" rows="4" required>Dear colleague,

Please find attached your petty cash voucher. For any queries, please don't hesitate to contact us.

Best regards,
Stafffy Inc</textarea>
                </div>
                
                <div style="margin-top: 15px; display: flex; flex-direction: column; gap: 10px;">
                    <label><input type="checkbox" name="admin_signature" checked> Include authorized signature</label>
                    <label><input type="checkbox" name="request_signature" checked> Request payee to sign</label>
                </div>
                
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary">Send Email</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="statusModal" style="display: none;">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <h2>Update Voucher Status</h2>
                <button class="modal-close-btn" onclick="closeStatusModal()">×</button>
            </div>
            <form method="POST" action="{{ route('petty.cash.update-status') }}">
                @csrf
                <input type="hidden" name="voucher_id" id="status_voucher_id" value="">
                <div class="form-group">
                    <label>New Status</label>
                    <select name="new_status" id="new_status" required>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="voucherModal" style="{{ ($showModal || $editId) ? 'display: flex;' : 'display: none;' }}">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h2>{{ $editId ? 'Edit Voucher' : 'Create Voucher' }}</h2>
                <button class="modal-close-btn" onclick="closeModal()">×</button>
            </div>
            
            <form method="POST" class="voucher-form" action="{{ $editId ? route('petty.cash.update', $editId) : route('petty.cash.store') }}">
                @csrf
                @if($editId)
                    @method('PUT')
                @endif
                
                <div class="voucher-modal-layout">
                    <div class="voucher-form-pane">
                        <div class="form-group">
                            <label>Date Issued</label>
                            <input type="date" name="date_issued" id="dateIssued" required value="{{ $voucherData ? $voucherData->date_issued->format('Y-m-d') : date('Y-m-d') }}">
                        </div>

                        <div class="form-group">
                            <label>Payee Name</label>
                            <input type="text" name="payee_name" id="payeeName" required value="{{ $voucherData ? $voucherData->payee_name : '' }}">
                        </div>

                        <div class="form-group">
                            <label>Payee Email</label>
                            <input type="email" name="payee_email" id="payeeEmail" required value="{{ $voucherData ? $voucherData->payee_email : '' }}">
                        </div>
                            
                        <div class="form-group">
                            <label>Contact Number</label>
                            <input type="text" name="contact_number" id="contactNumber" value="{{ $voucherData ? ($voucherData->contact_number ?? '') : '' }}">
                        </div>

                        <div style="display: flex; gap: 16px;">
                            <div class="form-group" style="flex: 1;">
                                <label>Department</label>
                                <input type="text" name="department" id="department" value="{{ $voucherData ? ($voucherData->department ?? '') : '' }}">
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label>Position</label>
                                <input type="text" name="position" id="position" value="{{ $voucherData ? ($voucherData->position ?? '') : '' }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Purpose/Description</label>
                            <textarea name="purpose" id="purpose" required>{{ $voucherData ? $voucherData->purpose : '' }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Amount (₱)</label>
                            <input type="number" name="amount" id="amount" step="0.01" required value="{{ $voucherData ? $voucherData->amount : '' }}">
                        </div>

                        <div class="form-group">
                            <label>Expense Category</label>
                            <div class="input-group">
                                <select name="category_id" id="categoryId">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $voucherData && $voucherData->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn input-group-btn" onclick="window.location.href='{{ route('billing.petty.cash', ['add_category' => 1]) }}'">+</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Payment Method</label>
                            <select name="payment_method" id="paymentMethod">
                                <option value="Cash" {{ $voucherData && $voucherData->payment_method == 'Cash' ? 'selected' : '' }}>Cash</option>
                                <option value="Check" {{ $voucherData && $voucherData->payment_method == 'Check' ? 'selected' : '' }}>Check</option>
                                <option value="Bank Transfer" {{ $voucherData && $voucherData->payment_method == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="Mobile Payment" {{ $voucherData && $voucherData->payment_method == 'Mobile Payment' ? 'selected' : '' }}>Mobile Payment</option>
                            </select>
                        </div>

                        <div style="display: flex; gap: 16px;">
                            <div class="form-group" style="flex: 1;">
                                <label>Approved By</label>
                                <input type="text" name="approved_by" id="approvedBy" value="{{ $voucherData ? ($voucherData->approved_by ?? '') : '' }}">
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label>Received By</label>
                                <input type="text" name="received_by" id="receivedBy" value="{{ $voucherData ? ($voucherData->received_by ?? '') : '' }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" id="notes">{{ $voucherData ? ($voucherData->notes ?? '') : '' }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" id="status">
                                <option value="pending" {{ !$voucherData || $voucherData->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $voucherData && $voucherData->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $voucherData && $voucherData->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <label style="flex-direction: row; align-items: center;">
                                <input type="checkbox" name="receipt_attached" id="receiptAttached" style="width: auto; margin-right: 10px;"
                                    {{ $voucherData && $voucherData->receipt_attached ? 'checked' : '' }}> 
                                Receipt Attached
                            </label>
                        </div>
                    </div>

                    <div class="voucher-preview-pane">
                        {{-- 🚀 NEW: Wrapper for content z-indexing --}}
                        <div class="voucher-preview-content">
                            <div class="voucher-logo">
                                <img src="{{ asset('assets/images/Stafify-Logo.png') }}" alt="Stafify Logo">
                            </div>
                            <h2>Petty Cash Voucher</h2>
                            <div class="voucher-header-info">
                                <div class="voucher-company-info">
                                    <strong>Stafffy Inc</strong><br>
                                    54 Irving Street<br>New Asinan<br>Olongapo City, 2200 ZAMBALES<br>PHILIPPINES<br>staffify@gmail.com
                                </div>
                                <div class="voucher-meta-info">
                                    <p><strong>Voucher #:</strong> <span id="previewVoucherNumber">{{ $voucherData ? $voucherData->voucher_number : 'New' }}</span></p>
                                    <p><strong>Date:</strong> <span id="previewDateIssued">{{ $voucherData ? $voucherData->date_issued->format('F j, Y') : date('F j, Y') }}</span></p>
                                    <p><strong>Status:</strong> <span class="status-badge status-{{ $voucherData ? $voucherData->status : 'pending' }}" id="previewStatus">{{ $voucherData ? ucfirst($voucherData->status) : 'Pending' }}</span></p>
                                </div>
                            </div>
                            
                            <div class="voucher-details">
                                <div class="detail-row">
                                    <div class="detail-label">Payee:</div>
                                    <div class="detail-value" id="previewPayeeName">{{ $voucherData ? $voucherData->payee_name : 'Full Name' }}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Department:</div>
                                    <div class="detail-value" id="previewDepartment">{{ $voucherData ? ($voucherData->department ?? '-') : '-' }}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Position:</div>
                                    <div class="detail-value" id="previewPosition">{{ $voucherData ? ($voucherData->position ?? '-') : '-' }}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Purpose:</div>
                                    <div class="detail-value" id="previewPurpose">{{ $voucherData ? $voucherData->purpose : '-' }}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Amount:</div>
                                    <div class="detail-value highlight" id="previewAmount">₱{{ $voucherData ? number_format($voucherData->amount, 2) : '0.00' }}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Category:</div>
                                    <div class="detail-value" id="previewCategory">{{ $voucherData && $voucherData->category ? $voucherData->category->category_name : '-' }}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Payment Method:</div>
                                    <div class="detail-value" id="previewPaymentMethod">{{ $voucherData ? $voucherData->payment_method : 'Cash' }}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Receipt Attached:</div>
                                    <div class="detail-value" id="previewReceiptAttached">{{ ($voucherData && $voucherData->receipt_attached) ? 'Yes' : 'No' }}</div>
                                </div>
                            </div>

                            <div class="contact-info">
                                <div>
                                    <strong>Contact Information:</strong><br>
                                    Email: <span id="previewPayeeEmail">{{ $voucherData ? $voucherData->payee_email : '-' }}</span><br>
                                    Phone: <span id="previewContactNumber">{{ $voucherData ? ($voucherData->contact_number ?? '-') : '-' }}</span>
                                </div>
                            </div>

                            <div class="notes">
                                <p><strong>Notes:</strong></p>
                                <p id="previewNotes">{!! $voucherData ? nl2br(e($voucherData->notes ?? '')) : '' !!}</p>
                            </div>

                            <div class="signature">
                                <div class="signature-block">
                                    <div class="signature-name" id="previewApprovedBy">{{ $voucherData ? ($voucherData->approved_by ?? '') : '' }}</div>
                                    <div class="signature-title">Approved By</div>
                                </div>
                                <div class="signature-block">
                                    <div class="signature-name" id="previewReceivedBy">{{ $voucherData ? ($voucherData->received_by ?? '') : '' }}</div>
                                    <div class="signature-title">Received By</div>
                                </div>
                                <div class="signature-block">
                                    @if($voucherData && $voucherData->is_signed)
                                        <div class="signed-badge">Electronically Signed</div>
                                        @if($voucherData->signature_image)
                                            <img src="{{ $voucherData->signature_image }}" alt="Digital Signature" class="signature-image">
                                        @endif
                                        <div class="signature-name" style="border-bottom: none;">{{ $voucherData->payee_name }}</div>
                                        <div class="signature-date"><small>{{ $voucherData->signature_date ? $voucherData->signature_date->format('F j, Y g:i A') : '' }}</small></div>
                                    @else
                                        <div class="signature-name" id="previewSignature">&nbsp;</div>
                                    @endif
                                    <div class="signature-title">Payee Signature</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="generatePDF()">Print Voucher</button>
                    <button type="submit" class="btn btn-primary">{{ $editId ? 'Update Voucher' : 'Save Voucher' }}</button>
                </div>
            </form>
        </div>
    </div>

    @if(!$viewId)
    <div class="page-header">
        <button onclick="openModal()" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Create New
        </button>
    </div>
    @endif

    @if(!$viewId)
    <div class="summary-grid">
        <div class="summary-card-item">
            <div class="stat-icon-wrapper icon-disbursed">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div class="summary-label">Total Disbursed</div>
            <div class="summary-value text-primary-custom">₱{{ number_format($totalDisbursed ?? 0, 2) }}</div>
        </div>
        <div class="summary-card-item">
            <div class="stat-icon-wrapper icon-pending">
                 <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="summary-label">Total Pending</div>
            <div class="summary-value text-warning">₱{{ number_format($totalPending ?? 0, 2) }}</div>
        </div>
        <div class="summary-card-item">
            <div class="stat-icon-wrapper icon-approved">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="summary-label">Total Approved</div>
             <div class="summary-value text-success">₱{{ number_format($totalApproved ?? 0, 2) }}</div>
        </div>
    </div>
    @endif
    @if($emailSent)
        <div class="success-message">Voucher has been sent successfully via email!</div>
    @endif
    @if($statusUpdated)
        <div class="success-message">Voucher status has been updated successfully!</div>
    @endif

    @if($viewId && $voucherData)
        <div class="voucher-view">
            <div class="voucher-view-actions btn-group">
                <button onclick="window.location.href='{{ route('billing.petty.cash') }}'" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                    Back to List
                </button>
                <button onclick="window.location.href='{{ route('billing.petty.cash', ['edit' => $viewId]) }}'" class="btn btn-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                    Edit
                </button>
                <form method="POST" action="{{ route('petty.cash.destroy', $viewId) }}" style="display: inline;" onsubmit="return confirm('Delete this voucher?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12.502 0c-.411.011-.82.028-1.229.05c-3.17.198-6.096.657-8.634 1.183L3.15 5.79m14.456 0L13.8 3.51a2.25 2.25 0 0 0-3.182 0L6.844 5.79m11.136 0c-0.291-.01-0.582-.019-0.873-.028m-10.01 0c-.29.009-.581.018-.872.028" /></svg>
                        Delete
                    </button>
                </form>
                <button onclick="generatePDF()" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0c.317.087.65.162.98.232m-9.98 0l-.04.022c-.512.227-.815.76-.815 1.319v2.1a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-2.1c0-.56-.303-1.092-.815-1.319l-.04-.022m-10.76-9.281c.24.03.48.062.72.096m-.72-.096c.317.087.65.162.98.232m-9.98 0l-.04.022c-.512.227-.815.76-.815 1.319v2.1a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-2.1c0-.56-.303-1.092-.815-1.319l-.04-.022m-10.76-9.281c.24.03.48.062.72.096m-.72-.096L9 5.25l3 3m0 0l3-3m-3 3v5.25m-6 3h12" /></svg>
                    Print
                </button>
                <button onclick="openEmailModal({{ $viewId }}, '{{ addslashes($voucherData->payee_email) }}')" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                    Email
                </button>
                <button onclick="openStatusModal({{ $viewId }}, '{{ $voucherData->status }}')" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                    Update Status
                </button>
            </div>
            
            <div class="voucher-document">
                {{-- 🚀 NEW: Wrapper for content z-indexing --}}
                <div class="voucher-document-content">
                    <h2>Petty Cash Voucher</h2>
                    <div class="voucher-header-info">
                        <div class="voucher-company-info">
                            <strong>Stafffy Inc</strong><br>
                            54 Irving Street<br>New Asinan<br>Olongapo City, 2200 ZAMBALES<br>PHILIPPINES<br>staffify@gmail.com
                        </div>
                        <div class="voucher-meta-info">
                            <p><strong>Voucher #:</strong> {{ $voucherData->voucher_number }}</p>
                            <p><strong>Date:</strong> {{ $voucherData->date_issued ? $voucherData->date_issued->format('F j, Y') : date('F j, Y') }}</p>
                            <p><strong>Status:</strong> <span class="status-badge status-{{ $voucherData->status }}">{{ ucfirst($voucherData->status) }}</span></p>
                            @if($voucherData->is_signed)
                                <p class="receipt-signed">✓ Signed by payee on {{ $voucherData->signature_date ? $voucherData->signature_date->format('F j, Y') : '' }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="voucher-details">
                        <div class="detail-row">
                            <div class="detail-label">Payee:</div>
                            <div class="detail-value">{{ $voucherData->payee_name }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Department:</div>
                            <div class="detail-value">{{ $voucherData->department ?: '-' }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Position:</div>
                            <div class="detail-value">{{ $voucherData->position ?: '-' }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Purpose:</div>
                            <div class="detail-value">{{ $voucherData->purpose }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Amount:</div>
                            <div class="detail-value highlight">₱{{ number_format($voucherData->amount, 2) }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Category:</div>
                            <div class="detail-value">{{ $voucherData->category ? $voucherData->category->category_name : 'N/A' }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Payment Method:</div>
                            <div class="detail-value">{{ $voucherData->payment_method }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Receipt Attached:</div>
                            <div class="detail-value">{{ $voucherData->receipt_attached ? 'Yes' : 'No' }}</div>
                        </div>
                    </div>

                    <div class="contact-info">
                        <div>
                            <strong>Contact Information:</strong><br>
                            Email: {{ $voucherData->payee_email }}<br>
                            Phone: {{ $voucherData->contact_number ?: '-' }}
                        </div>
                    </div>

                    <div class="notes">
                        <p><strong>Notes:</strong></p>
                        <p>{!! $voucherData->notes ? nl2br(e($voucherData->notes)) : '' !!}</p>
                    </div>

                    <div class="signature">
                        <div class="signature-block">
                            <div class="signature-name">{{ $voucherData->approved_by ?: '' }}</div>
                            <div class="signature-title">Approved By</div>
                        </div>
                        
                        <div class="signature-block">
                            <div class="signature-name">{{ $voucherData->received_by ?: '' }}</div>
                            <div class="signature-title">Received By</div>
                        </div>
                        
                        <div class="signature-block">
                            @if($voucherData->is_signed)
                                <div class="signed-badge">Electronically Signed</div>
                                @if($voucherData->signature_image)
                                    <img src="{{ $voucherData->signature_image }}" alt="Digital Signature" class="signature-image">
                                @endif
                                <div class="signature-name" style="border-bottom: none;">{{ $voucherData->payee_name }}</div>
                                <div class="signature-date">{{ $voucherData->signature_date ? $voucherData->signature_date->format('F j, Y g:i A') : '' }}</div>
                            @else
                                <div class="signature-name">&nbsp;</div>
                            @endif
                            <div class="signature-title">Payee Signature</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="content-card">
            @if($vouchers->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="vouchers-table">
                        <thead>
                            <tr>
                                <th>Voucher #</th>
                                <th>Date</th>
                                <th>Payee</th>
                                <th>Department</th>
                                <th>Purpose</th>
                                <th>Amount</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vouchers as $voucher)
                                <tr>
                                    <td>{{ $voucher->voucher_number }}</td>
                                    <td>{{ $voucher->date_issued ? $voucher->date_issued->format('M j, Y') : '-' }}</td>
                                    <td>{{ $voucher->payee_name }}</td>
                                    <td>{{ $voucher->department ?: '-' }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($voucher->purpose, 30) }}</td>
                                    <td>₱{{ number_format($voucher->amount, 2) }}</td>
                                    <td>{{ $voucher->category ? $voucher->category->category_name : 'N/A' }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $voucher->status }}">{{ ucfirst($voucher->status) }}</span>
                                        @if($voucher->is_signed)
                                            <span class="badge-signed" title="Signed by payee">✓</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons btn-group">
                                            <button class="btn btn-icon" title="View" onclick="window.location.href='{{ route('billing.petty.cash', ['view' => $voucher->voucher_id]) }}'">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.634l3.061-5.636a.5.5 0 0 1 .44-.223h13.911a.5.5 0 0 1 .44.223l3.061 5.636a1.012 1.012 0 0 1 0 .634l-3.061 5.636a.5.5 0 0 1-.44.223H5.537a.5.5 0 0 1-.44-.223L2.036 12.322Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                            </button>
                                            <button class="btn btn-icon" title="Edit" onclick="window.location.href='{{ route('billing.petty.cash', ['edit' => $voucher->voucher_id]) }}'">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                            </button>
                                            <form method="POST" action="{{ route('petty.cash.destroy', $voucher->voucher_id) }}" style="display: inline;" onsubmit="return confirm('Delete this voucher?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon" title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12.502 0c-.411.011-.82.028-1.229.05c-3.17.198-6.096.657-8.634 1.183L3.15 5.79m14.456 0L13.8 3.51a2.25 2.25 0 0 0-3.182 0L6.844 5.79m11.136 0c-0.291-.01-0.582-.019-0.873-.028m-10.01 0c-.29.009-.581.018-.872.028" /></svg>
                                                </button>
                                            </form>
                                            <button class="btn btn-icon" title="Email" onclick="openEmailModal({{ $voucher->voucher_id }}, '{{ addslashes($voucher->payee_email) }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                                            </button>
                                            <button class="btn btn-icon" title="Update Status" onclick="openStatusModal({{ $voucher->voucher_id }}, '{{ $voucher->status }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>No vouchers found. Create your first voucher.</p>
            @endif
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
function generatePDF() {
    const loader = document.createElement('div');
    loader.className = 'pdf-loading';
    loader.innerHTML = '<div class="pdf-loading-spinner"></div>';
    document.body.appendChild(loader);
    loader.style.display = 'flex';

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'pt', 'a4');
    
    let voucherElement;
    if (document.querySelector('.voucher-document')) {
        voucherElement = document.querySelector('.voucher-document');
    } else if (document.querySelector('.voucher-preview-pane')) {
        voucherElement = document.querySelector('.voucher-preview-pane');
    } else {
        alert('Could not find voucher content');
        loader.remove();
        return;
    }
    
    const elementClone = voucherElement.cloneNode(true);
    elementClone.style.width = '700px';
    elementClone.style.padding = '20px';
    elementClone.style.boxSizing = 'border-box';
    elementClone.style.backgroundColor = 'white';
    elementClone.style.border = 'none';
    elementClone.style.boxShadow = 'none';
    elementClone.style.position = 'absolute';
    elementClone.style.left = '-9999px';
    elementClone.style.top = '0';
    document.body.appendChild(elementClone);
    
    const options = {
        scale: 2,
        useCORS: true,
        allowTaint: true,
        backgroundColor: 'white',
        scrollX: 0,
        scrollY: 0,
        width: elementClone.scrollWidth,
        height: elementClone.scrollHeight,
        windowWidth: elementClone.scrollWidth,
        windowHeight: elementClone.scrollHeight
    };
    
    html2canvas(elementClone, options).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const imgWidth = doc.internal.pageSize.getWidth() - 40;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        
        const xPos = (doc.internal.pageSize.getWidth() - imgWidth) / 2;
        const yPos = 20;
        
        doc.addImage(imgData, 'PNG', xPos, yPos, imgWidth, imgHeight);
        doc.save('voucher_' + (new Date().getTime()) + '.pdf');
        
        elementClone.remove();
        loader.remove();
    }).catch(err => {
        console.error('Error generating PDF:', err);
        alert('Error generating PDF. Please try again.');
        elementClone.remove();
        loader.remove();
    });
}

window.openModal = function() {
    const modal = document.getElementById("voucherModal");
    if (modal) {
        modal.style.display = "flex";
    }
};

window.closeModal = function() {
    const modal = document.getElementById('voucherModal');
    if (modal) {
        modal.style.display = 'none';
    }
    @if($editId)
        if (window.location.search.includes('edit=')) {
            window.location.href = '{{ route('billing.petty.cash') }}';
        }
    @endif
};

document.addEventListener("DOMContentLoaded", function () {
    window.openEmailModal = function(voucherId, email) {
        document.getElementById('email_voucher_id').value = voucherId;
        document.getElementById('email_recipient').value = email;
        document.getElementById('emailModal').style.display = 'flex';
    };
    
    window.closeEmailModal = function() {
        document.getElementById('emailModal').style.display = 'none';
    };

    window.openStatusModal = function(voucherId, currentStatus) {
        document.getElementById('status_voucher_id').value = voucherId;
        const statusSelect = document.getElementById('new_status');
        if (statusSelect) {
            for (let i = 0; i < statusSelect.options.length; i++) {
                if (statusSelect.options[i].value === currentStatus) {
                    statusSelect.selectedIndex = i;
                    break;
                }
            }
        }
        document.getElementById('statusModal').style.display = 'flex';
    };
    
    window.closeStatusModal = function() {
        document.getElementById('statusModal').style.display = 'none';
    };

    function updatePreview() {
        const dateIssued = document.getElementById('dateIssued');
        if (dateIssued && dateIssued.value) {
            const dateObj = new Date(dateIssued.value);
            const options = { year: 'numeric', month: 'long', day: 'numeric', timeZone: 'UTC' };
            document.getElementById('previewDateIssued').textContent = dateObj.toLocaleDateString('en-US', options);
        } else if (dateIssued) {
             document.getElementById('previewDateIssued').textContent = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
        }

        const payeeName = document.getElementById('payeeName');
        if (payeeName) {
            document.getElementById('previewPayeeName').textContent = payeeName.value || 'Full Name';
        }

        const payeeEmail = document.getElementById('payeeEmail');
        if (payeeEmail) {
            document.getElementById('previewPayeeEmail').textContent = payeeEmail.value || '-';
        }

        const contactNumber = document.getElementById('contactNumber');
        if (contactNumber) {
            document.getElementById('previewContactNumber').textContent = contactNumber.value || '-';
        }

        const department = document.getElementById('department');
        if (department) {
            document.getElementById('previewDepartment').textContent = department.value || '-';
        }

        const position = document.getElementById('position');
        if (position) {
            document.getElementById('previewPosition').textContent = position.value || '-';
        }

        const purpose = document.getElementById('purpose');
        if (purpose) {
            document.getElementById('previewPurpose').textContent = purpose.value || '-';
        }

        const amount = document.getElementById('amount');
        if (amount) {
            document.getElementById('previewAmount').textContent = '₱' + (parseFloat(amount.value) || 0).toFixed(2);
        }

        const categoryId = document.getElementById('categoryId');
        if (categoryId && categoryId.options.length > 0) {
            document.getElementById('previewCategory').textContent = categoryId.options[categoryId.selectedIndex].text;
        }

        const paymentMethod = document.getElementById('paymentMethod');
        if (paymentMethod) {
            document.getElementById('previewPaymentMethod').textContent = paymentMethod.value;
        }

        const receiptAttached = document.getElementById('receiptAttached');
        if (receiptAttached) {
            document.getElementById('previewReceiptAttached').textContent = receiptAttached.checked ? 'Yes' : 'No';
        }

        const notes = document.getElementById('notes');
        if (notes) {
            document.getElementById('previewNotes').innerHTML = notes.value.replace(/\n/g, '<br>') || '';
        }

        const status = document.getElementById('status');
        if (status) {
            const statusElem = document.getElementById('previewStatus');
            statusElem.textContent = status.options[status.selectedIndex].text;
            statusElem.className = 'status-badge status-' + status.value;
        }

        const approvedBy = document.getElementById('approvedBy');
        if (approvedBy) {
            document.getElementById('previewApprovedBy').textContent = approvedBy.value || '';
        }

        const receivedBy = document.getElementById('receivedBy');
        if (receivedBy) {
            document.getElementById('previewReceivedBy').textContent = receivedBy.value || '';
        }
    }

    const formFields = [
        'dateIssued', 'payeeName', 'payeeEmail', 'contactNumber', 'department', 
        'position', 'purpose', 'amount', 'categoryId', 'paymentMethod', 
        'receiptAttached', 'notes', 'status', 'approvedBy', 'receivedBy'
    ];

    formFields.forEach(function(id) {
        const field = document.getElementById(id);
        if (field) {
            field.addEventListener('input', updatePreview);
            field.addEventListener('change', updatePreview);
        }
    });

    // Run preview on initial load
    if (document.getElementById('voucherModal').style.display === 'flex') {
        updatePreview();
    }

    @if($editId || $showModal)
        const modal = document.getElementById('voucherModal');
        if (modal) {
            modal.style.display = 'flex';
            updatePreview(); // Run preview on load for edit/create
        }
    @endif
});
</script>
@endpush