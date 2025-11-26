@extends('layouts.app')

@section('title', 'Petty Cash Voucher')
@section('description', 'Manage petty cash vouchers - Create, view, edit, and send vouchers to payees')

@push('styles')
    <style>
        /* --- 1. Root Variables (Consistent) --- */
        :root {
            --primary-color: #3B82F6;
            --primary-color-hover: #184278;
            --secondary-color: #f1f5f9;
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

        /* --- 2. General Page Layout --- */
        .petty-cash-page { padding: 0; max-width: 100%; margin: 0 auto; }

        .page-header {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin-bottom: 24px;
        }
        
        .page-header h1 {
            font-size: 1.75rem; font-weight: 600; color: var(--text-color); margin: 0;
        }

        .content-card {
            background: var(--white); border-radius: var(--border-radius);
            box-shadow: var(--box-shadow); padding: 24px; overflow: hidden;
        }

        /* --- 3. Unified Button System --- */
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            gap: 8px; padding: 10px 16px; font-size: 14px; font-weight: 600;
            border-radius: var(--border-radius); border: 1px solid transparent;
            cursor: pointer; transition: var(--transition); text-decoration: none; white-space: nowrap;
        }
        .btn svg { width: 16px; height: 16px; }

        .btn-primary { background-color: var(--primary-color); color: var(--white); }
        .btn-primary:hover { background-color: var(--primary-color-hover); }
        
        .btn-secondary { background-color: var(--white); color: var(--primary-color); border-color: var(--border-color); }
        .btn-secondary:hover { background-color: var(--secondary-color); }
        
        .btn-danger { background-color: var(--danger-color); color: var(--white); }
        .btn-danger:hover { background-color: var(--danger-color-hover); }
        
        .btn-warning { background-color: var(--warning-color); color: var(--white); }
        .btn-warning:hover { background-color: var(--warning-color-hover); }

        .btn-icon {
            padding: 8px; width: 38px; height: 38px; border-radius: 50%;
            background-color: var(--secondary-color); color: var(--text-color-light); border: none;
        }
        .btn-icon:hover { background-color: var(--primary-color); color: var(--white); }
        
        .btn-group { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }

        .input-group { display: flex; }
        .input-group select, .input-group input { flex-grow: 1; }
        .input-group select { border-top-right-radius: 0; border-bottom-right-radius: 0; }
        .input-group-btn {
            border-top-left-radius: 0; border-bottom-left-radius: 0;
            border: 1px solid var(--border-color); border-left: none;
            padding: 0 12px; background: #f8f9fa;
        }
        .input-group-btn:hover { background: #e9ecef; }

        /* --- 4. Voucher List Table --- */
        .vouchers-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .vouchers-table th, .vouchers-table td {
            padding: 12px 16px; text-align: left; border-bottom: 1px solid var(--border-color);
            color: var(--text-color); font-size: 14px;
        }
        .vouchers-table th { background-color: #f8f9fa; color: var(--text-color-light); font-weight: 600; font-size: 12px; text-transform: uppercase; }
        .vouchers-table tr:hover { background-color: var(--secondary-color); }
        .vouchers-table .action-buttons { display: flex; gap: 8px; justify-content: flex-start; }
        .table-responsive-wrapper { overflow-x: auto; }

        /* --- 5. Status Badges --- */
        .status-badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-approved { background-color: #d1fae5; color: #065f46; }
        .status-rejected { background-color: #fee2e2; color: #991b1b; }
        .badge-signed, .signed-badge, .receipt-signed { color: var(--success-color); font-weight: bold; }
        .badge-signed { margin-left: 5px; }
        .signed-badge { margin-bottom: 5px; }

        /* --- 6. Summary Cards --- */
        .summary-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px; }
        .summary-card-item { text-align: center; padding: 20px; border-radius: var(--border-radius); background-color: var(--white); border: 1px solid var(--border-color); box-shadow: var(--box-shadow); }
        .stat-icon-wrapper { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; }
        .stat-icon-wrapper i { font-size: 20px; }
        .icon-disbursed { background-color: whitesmoke; color: var(--primary-color); }
        .icon-pending { background-color: #fef3c7; color: var(--warning-color); }
        .icon-approved { background-color: #d1fae5; color: #065f46; }
        .summary-label { font-size: 14px; font-weight: 600; text-transform: uppercase; margin-bottom: 8px; color: var(--text-color-light); }
        .summary-value { font-size: 28px; font-weight: 700; margin: 0; }
        .text-success { color: #065f46 !important; }
        .text-warning { color: #92400e !important; }
        .text-primary-custom { color: var(--primary-color) !important; }

        /* --- 7. Modal System --- */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5); display: none; align-items: center; justify-content: center;
            z-index: 1000; backdrop-filter: blur(3px); transition: opacity 0.3s ease-out;
        }
        .modal-content {
            background: var(--white); padding: 24px; border-radius: var(--border-radius);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); position: relative;
            animation: modalFadeIn 0.3s ease-out; width: 90%; max-height: 90vh; overflow-y: auto;
        }
        .modal-lg { max-width: 1100px; }
        .modal-md { max-width: 600px; }
        .modal-sm { max-width: 500px; }
        @keyframes modalFadeIn { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }

        .modal-header { display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid var(--border-color); margin-bottom: 24px; }
        .modal-header h2 { margin: 0; font-size: 1.25rem; color: var(--text-color); font-weight: 600; }
        .modal-close-btn { font-size: 24px; cursor: pointer; background: none; border: none; color: var(--text-color-light); padding: 0; line-height: 1; }
        .modal-close-btn:hover { color: var(--text-color); }

        /* --- 8. Form Styles --- */
        .form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
        .form-group label { font-weight: 600; color: var(--text-color); font-size: 14px; text-align: left; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 10px; border-radius: var(--border-radius); border: 1px solid var(--border-color);
            font-size: 14px; transition: var(--transition); box-sizing: border-box;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 2px rgba(31, 84, 151, 0.2);
        }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border-color); }

        /* --- 9. Voucher Modal Layout --- */
        .voucher-modal-layout { display: flex; gap: 30px; }
        .voucher-form-pane { flex: 1; min-width: 400px; }
        .voucher-preview-pane {
            flex: 1; background: #f8f9fa; border-radius: var(--border-radius);
            padding: 24px; border: 1px solid var(--border-color);
            max-height: 70vh; overflow-y: auto; position: relative;
        }

        /* Preview Styles */
        .voucher-logo { margin-right: 20px; position: relative; }
        .voucher-logo img { max-width: 150px; }
        .voucher-preview-pane::before, .voucher-document::before {
            content: ''; position: absolute; top: 24px; left: 24px; width: 150px; height: 150px;
            background-image: url("{{ asset('assets/images/Staffy-Logo.png') }}");
            background-repeat: no-repeat; background-position: top left; background-size: contain;
            opacity: 0.08; z-index: 1; pointer-events: none;
        }
        .voucher-preview-content, .voucher-document-content { position: relative; z-index: 2; }
        .voucher-preview-pane h2, .voucher-document h2 {
            color: var(--primary-color); margin-top: 0; font-size: 1.5rem; font-weight: 700;
            margin-bottom: 20px; border-bottom: 2px solid var(--border-color); padding-bottom: 10px; text-align: left;
        }
        .voucher-header-info { display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 20px; line-height: 1.6; }
        .voucher-company-info { text-align: left; }
        .voucher-meta-info { text-align: right; flex-shrink: 0; padding-left: 20px; }
        .voucher-meta-info p { margin: 0 0 5px 0; font-weight: 500; }

        .voucher-details { margin: 20px 0; border: 1px solid var(--border-color); border-radius: var(--border-radius); overflow: hidden; background: var(--white); }
        .detail-row { display: flex; padding: 12px; border-bottom: 1px solid var(--border-color); font-size: 14px; }
        .detail-row:last-child { border-bottom: none; }
        .detail-row:nth-child(even) { background: #fdfdfd; }
        .detail-label { width: 30%; min-width: 120px; font-weight: 600; color: var(--text-color-light); padding-right: 10px; flex-shrink: 0; text-align: left; }
        .detail-value { width: 70%; color: var(--text-color); font-weight: 500; word-break: break-word; text-align: right; }
        .highlight { font-weight: 700; color: var(--primary-color); font-size: 1.25rem; }

        .contact-info, .notes { font-size: 14px; margin-top: 20px; text-align: left; line-height: 1.6; }
        .notes p:first-child { margin-top: 0; }
        .notes p:last-child { margin-bottom: 0; min-height: 40px; }

        .signature { font-size: 9px; margin-top: 40px; display: flex; justify-content: space-between; gap: 20px; }
        .signature-block { width: 30%; text-align: center; padding-top: 8px; }
        .signature-name { min-height: 20px; font-weight: 600; color: var(--text-color); border-bottom: 1px solid #333; padding-bottom: 5px; margin-bottom: 5px; }
        .signature-title { font-size: 12px; color: var(--text-color-light); text-transform: uppercase; }
        .signature-image { max-width: 180px; max-height: 50px; border: 1px solid #ccc; background: white; margin: 5px 0; }
        .signature-date { font-size: 11px; color: var(--text-color-light); }

        /* --- 10. PDF Loading --- */
        .pdf-loading {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255, 255, 255, 0.8); display: flex; align-items: center; justify-content: center;
            z-index: 9999; display: none;
        }
        .pdf-loading-spinner {
            width: 50px; height: 50px; border: 5px solid var(--secondary-color);
            border-top-color: var(--primary-color); border-radius: 50%; animation: spin 1s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .success-message { background-color: #d4edda; color: #155724; padding: 15px; border-radius: var(--border-radius); margin: 0 24px 24px 24px; }
        .voucher-view-actions { margin-bottom: 24px; padding: 16px 24px; background: var(--white); border-radius: var(--border-radius); box-shadow: var(--box-shadow); margin-left: 24px; margin-right: 24px; }
        .voucher-document { background: var(--white); padding: 40px; border-radius: var(--border-radius); box-shadow: var(--box-shadow); max-width: 800px; margin: 0 auto 24px auto; position: relative; }

        /* --- 11. RESPONSIVE ADJUSTMENTS (FIXED) --- */
        @media (max-width: 992px) {
            /* Stack the modal content */
            .voucher-modal-layout {
                flex-direction: column;
            }
            /* Fix the minimum width causing scroll */
            .voucher-form-pane {
                min-width: 100%; 
            }
            .voucher-preview-pane {
                max-height: none; /* Let it grow naturally on mobile */
            }
        }

        @media (max-width: 768px) {
            /* Stack Summary Cards into 1 column */
            .summary-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            /* Force table horizontal scroll on small screens */
            .vouchers-table {
                min-width: 800px; 
            }
            .content-card {
                padding: 16px;
            }
            
            /* Full width button in header */
            .page-header {
                width: 100%;
            }
            .page-header .btn {
                width: 100%;
            }

            /* Modal Adjustments */
            .modal-content {
                padding: 16px;
                width: 95%;
            }
            
            /* Stack modal actions buttons */
            .modal-actions {
                flex-direction: column-reverse;
                gap: 10px;
            }
            .modal-actions .btn {
                width: 100%;
            }
            
            /* Adjust signature block spacing */
            .signature {
                gap: 10px;
            }
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
                    <label style="flex-direction: row; align-items: center; text-align: left;">
                        <input type="checkbox" name="admin_signature" checked style="width: auto; margin-right: 10px;"> Include authorized signature
                    </label>
                    <label style="flex-direction: row; align-items: center; text-align: left;">
                        <input type="checkbox" name="request_signature" checked style="width: auto; margin-right: 10px;"> Request payee to sign
                    </label>
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
                @if($editId) @method('PUT') @endif
                
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

                        <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                            <div class="form-group" style="flex: 1; min-width: 150px;">
                                <label>Department</label>
                                <input type="text" name="department" id="department" value="{{ $voucherData ? ($voucherData->department ?? '') : '' }}">
                            </div>
                            <div class="form-group" style="flex: 1; min-width: 150px;">
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

                        <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                            <div class="form-group" style="flex: 1; min-width: 150px;">
                                <label>Approved By</label>
                                <input type="text" name="approved_by" id="approvedBy" value="{{ $voucherData ? ($voucherData->approved_by ?? '') : '' }}">
                            </div>
                            <div class="form-group" style="flex: 1; min-width: 150px;">
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
                            <label style="flex-direction: row; align-items: center; display: flex; text-align: left;">
                                <input type="checkbox" name="receipt_attached" id="receiptAttached" style="width: auto; margin-right: 10px;"
                                    {{ $voucherData && $voucherData->receipt_attached ? 'checked' : '' }}> 
                                Receipt Attached
                            </label>
                        </div>
                    </div>

                    <div class="voucher-preview-pane">
                        <div class="voucher-preview-content">
                            <div class="voucher-logo">
                                <img src="{{ asset('assets/images/Stafify-Logo.png') }}" alt="Stafify Logo">
                            </div>
                            <h2>Petty Cash Voucher</h2>
                            <div class="voucher-header-info">
                                <div class="voucher-company-info">
                                    <strong>Stafffy Inc</strong><br>
                                    54 Irving Street<br>New Asinan<br>Olongapo City, 2200 ZAMBALES<br>PHILIPPINES
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
                            </div>

                            <div class="contact-info">
                                <strong>Contact Information:</strong><br>
                                Email: <span id="previewPayeeEmail">{{ $voucherData ? $voucherData->payee_email : '-' }}</span><br>
                                Phone: <span id="previewContactNumber">{{ $voucherData ? ($voucherData->contact_number ?? '-') : '-' }}</span>
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
                                    <div class="signature-name">&nbsp;</div>
                                    <div class="signature-title">Payee Signature</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="generatePDF()">Print Preview</button>
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

    <div class="summary-grid">
        <div class="summary-card-item">
            <div class="stat-icon-wrapper icon-disbursed"><i class="fas fa-file-invoice-dollar"></i></div>
            <div class="summary-label">Total Disbursed</div>
            <div class="summary-value text-primary-custom">₱{{ number_format($totalDisbursed ?? 0, 2) }}</div>
        </div>
        <div class="summary-card-item">
            <div class="stat-icon-wrapper icon-pending"><i class="fas fa-hourglass-half"></i></div>
            <div class="summary-label">Total Pending</div>
            <div class="summary-value text-warning">₱{{ number_format($totalPending ?? 0, 2) }}</div>
        </div>
        <div class="summary-card-item">
            <div class="stat-icon-wrapper icon-approved"><i class="fas fa-check-circle"></i></div>
            <div class="summary-label">Total Approved</div>
             <div class="summary-value text-success">₱{{ number_format($totalApproved ?? 0, 2) }}</div>
        </div>
    </div>

    <div class="content-card">
        @if($vouchers->count() > 0)
            <div class="table-responsive-wrapper">
                <table class="vouchers-table">
                    <thead>
                        <tr>
                            <th>Voucher #</th>
                            <th>Date</th>
                            <th>Payee</th>
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
                                <td>{{ \Illuminate\Support\Str::limit($voucher->purpose, 30) }}</td>
                                <td>₱{{ number_format($voucher->amount, 2) }}</td>
                                <td>{{ $voucher->category ? $voucher->category->category_name : 'N/A' }}</td>
                                <td><span class="status-badge status-{{ $voucher->status }}">{{ ucfirst($voucher->status) }}</span></td>
                                <td>
                                    <div class="action-buttons btn-group">
                                        <button class="btn btn-icon" title="Edit" onclick="window.location.href='{{ route('billing.petty.cash', ['edit' => $voucher->voucher_id]) }}'">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                        </button>
                                        <form method="POST" action="{{ route('petty.cash.destroy', $voucher->voucher_id) }}" style="display: inline;" onsubmit="return confirm('Delete this voucher?')">
                                            @csrf @method('DELETE')
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
    const loader = document.createElement('div'); loader.className = 'pdf-loading';
    loader.innerHTML = '<div class="pdf-loading-spinner"></div>';
    document.body.appendChild(loader); loader.style.display = 'flex';
    const { jsPDF } = window.jspdf; const doc = new jsPDF('p', 'pt', 'a4');
    let voucherElement;
    if (document.querySelector('.voucher-document')) voucherElement = document.querySelector('.voucher-document');
    else if (document.querySelector('.voucher-preview-pane')) voucherElement = document.querySelector('.voucher-preview-pane');
    else { alert('Could not find voucher content'); loader.remove(); return; }
    const elementClone = voucherElement.cloneNode(true);
    elementClone.style.width = '700px'; elementClone.style.padding = '20px'; elementClone.style.backgroundColor = 'white';
    elementClone.style.position = 'absolute'; elementClone.style.left = '-9999px'; elementClone.style.top = '0';
    document.body.appendChild(elementClone);
    html2canvas(elementClone, { scale: 2, useCORS: true, backgroundColor: 'white' }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const imgWidth = doc.internal.pageSize.getWidth() - 40;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        doc.addImage(imgData, 'PNG', 20, 20, imgWidth, imgHeight);
        doc.save('voucher.pdf'); elementClone.remove(); loader.remove();
    }).catch(err => { console.error(err); alert('Error generating PDF'); elementClone.remove(); loader.remove(); });
}

window.openModal = () => { const m = document.getElementById("voucherModal"); if(m) m.style.display = "flex"; };
window.closeModal = () => { 
    const m = document.getElementById('voucherModal'); if(m) m.style.display = 'none'; 
    @if($editId) if(location.search.includes('edit=')) location.href = '{{ route('billing.petty.cash') }}'; @endif
};

document.addEventListener("DOMContentLoaded", function () {
    window.openEmailModal = (id, email) => {
        document.getElementById('email_voucher_id').value = id;
        document.getElementById('email_recipient').value = email;
        document.getElementById('emailModal').style.display = 'flex';
    };
    window.closeEmailModal = () => document.getElementById('emailModal').style.display = 'none';
    
    window.openStatusModal = (id, status) => {
        document.getElementById('status_voucher_id').value = id;
        const sel = document.getElementById('new_status');
        if(sel) { for(let i=0; i<sel.options.length; i++) if(sel.options[i].value === status) sel.selectedIndex = i; }
        document.getElementById('statusModal').style.display = 'flex';
    };
    window.closeStatusModal = () => document.getElementById('statusModal').style.display = 'none';

    // Live Preview Logic
    function updatePreview() {
        const setText = (id, val, def = '-') => { const el = document.getElementById(id); if(el) el.textContent = val || def; };
        const setVal = (srcId, destId, def = '-') => { const el = document.getElementById(srcId); if(el) setText(destId, el.value, def); };
        
        setVal('payeeName', 'previewPayeeName', 'Full Name');
        setVal('payeeEmail', 'previewPayeeEmail');
        setVal('contactNumber', 'previewContactNumber');
        setVal('purpose', 'previewPurpose');
        setVal('approvedBy', 'previewApprovedBy');
        setVal('receivedBy', 'previewReceivedBy');
        
        const amt = document.getElementById('amount');
        if(amt) setText('previewAmount', '₱' + (parseFloat(amt.value)||0).toFixed(2));
        
        const date = document.getElementById('dateIssued');
        if(date && date.value) {
            const d = new Date(date.value);
            setText('previewDateIssued', d.toLocaleDateString('en-US', {year:'numeric', month:'long', day:'numeric'}));
        }

        const cat = document.getElementById('categoryId');
        if(cat && cat.selectedIndex >= 0) setText('previewCategory', cat.options[cat.selectedIndex].text);
        
        const status = document.getElementById('status');
        if(status) {
            const el = document.getElementById('previewStatus');
            if(el) { el.textContent = status.options[status.selectedIndex].text; el.className = 'status-badge status-' + status.value; }
        }
    }

    ['dateIssued', 'payeeName', 'payeeEmail', 'contactNumber', 'purpose', 'amount', 'categoryId', 'status', 'approvedBy', 'receivedBy']
        .forEach(id => {
            const el = document.getElementById(id);
            if(el) { el.addEventListener('input', updatePreview); el.addEventListener('change', updatePreview); }
        });

    if(document.getElementById('voucherModal').style.display === 'flex') updatePreview();
});
</script>
@endpush