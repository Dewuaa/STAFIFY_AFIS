@extends('layouts.app')

@section('title', 'E-Invoice')
@section('description', 'Manage your invoices - Create, view, edit, and send invoices to your customers')

@push('styles')
    <style>
        /* --- 1. Root Variables --- */
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
        .invoice-page-content { padding: 0; max-width: 100%; margin: 0 auto; }
        .page-header { display: flex; justify-content: flex-start; align-items: center; margin-bottom: 24px; }
        .page-header h1 { font-size: 1.75rem; font-weight: 600; color: var(--text-color); margin: 0; }
        
        .content-card { background: var(--white); border-radius: var(--border-radius); box-shadow: var(--box-shadow); overflow: hidden; padding: 24px; }

        /* --- 3. Unified Button System --- */
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 16px; font-size: 14px; font-weight: 600; border-radius: var(--border-radius); border: 1px solid transparent; cursor: pointer; transition: var(--transition); text-decoration: none; white-space: nowrap; }
        .btn svg { width: 16px; height: 16px; }
        .btn-primary { background-color: var(--primary-color); color: var(--white); }
        .btn-primary:hover { background-color: var(--primary-color-hover); }
        .btn-secondary { background-color: var(--white); color: var(--primary-color); border-color: var(--border-color); }
        .btn-secondary:hover { background-color: var(--secondary-color); }
        .btn-danger { background-color: var(--danger-color); color: var(--white); }
        .btn-danger:hover { background-color: var(--danger-color-hover); }
        .btn-warning { background-color: var(--warning-color); color: var(--white); }
        .btn-warning:hover { background-color: var(--warning-color-hover); }
        .btn-icon { padding: 8px; width: 38px; height: 38px; border-radius: 50%; background-color: var(--secondary-color); color: var(--text-color-light); border: none; display: flex; align-items: center; justify-content: center; }
        .btn-icon:hover { background-color: var(--primary-color); color: var(--white); }
        .btn-group { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }
        .input-group-btn { border-top-left-radius: 0; border-bottom-left-radius: 0; border: 1px solid var(--border-color); border-left: none; padding: 0 12px; background: #f8f9fa; }

        /* --- 4. Invoice List Table --- */
        .invoices-table { width: 100%; border-collapse: collapse; margin-top: 0; }
        .invoices-table th, .invoices-table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid var(--border-color); color: var(--text-color); font-size: 14px; white-space: nowrap; }
        .invoices-table th { background-color: #f8f9fa; color: var(--text-color-light); font-weight: 600; font-size: 12px; text-transform: uppercase; }
        .invoices-table tr:hover { background-color: var(--secondary-color); }
        .invoices-table .action-buttons { display: flex; gap: 8px; justify-content: flex-start; }
        .table-responsive-wrapper { overflow-x: auto; }

        /* --- 5. Tax Badge Styles --- */
        .tax-badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; }
        .tax-vat { background-color: #d1fae5; color: #065f46; }
        .tax-nonvat { background-color: #fee2e2; color: #991b1b; }

        /* --- 6. Summary Card Styles --- */
        .summary-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px; }
        .summary-card-item { text-align: center; padding: 20px; border-radius: var(--border-radius); background-color: var(--white); border: 1px solid var(--border-color); box-shadow: var(--box-shadow); }
        .stat-icon-wrapper { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; }
        .stat-icon-wrapper i { font-size: 20px; }
        .icon-total { background-color: whitesmoke; color: var(--primary-color); }
        .icon-vat { background-color: #d1fae5; color: #065f46; }
        .icon-nonvat { background-color: #fee2e2; color: #991b1b; }
        .summary-label { font-size: 14px; font-weight: 600; text-transform: uppercase; margin-bottom: 8px; color: var(--text-color-light); }
        .summary-value { font-size: 28px; font-weight: 700; margin: 0; }
        .text-success { color: #065f46 !important; }
        .text-danger { color: #991b1b !important; }
        .text-primary-custom { color: var(--primary-color) !important; }

        /* --- 7. Modal System --- */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); display: none; align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(3px); transition: opacity 0.3s ease-out; }
        .modal-content { background: var(--white); padding: 24px; border-radius: var(--border-radius); box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); position: relative; animation: modalFadeIn 0.3s ease-out; width: 90%; max-height: 90vh; overflow-y: auto; }
        .modal-lg { max-width: 1100px; }
        .modal-md { max-width: 600px; }
        .modal-sm { max-width: 500px; }
        @keyframes modalFadeIn { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        .modal-header { display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid var(--border-color); margin-bottom: 24px; }
        .modal-header h2 { margin: 0; font-size: 1.25rem; color: var(--text-color); font-weight: 600; }
        .modal-close-btn { font-size: 24px; cursor: pointer; background: none; border: none; color: var(--text-color-light); padding: 0; line-height: 1; }

        /* --- 8. Form Styles --- */
        .form-grid-2 { display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 16px; }
        .form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
        .form-group label { font-weight: 600; color: var(--text-color); font-size: 14px; text-align: left; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border-radius: var(--border-radius); border: 1px solid var(--border-color); font-size: 14px; transition: var(--transition); box-sizing: border-box; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2); }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .input-group { display: flex; }
        .input-group select { border-top-right-radius: 0; border-bottom-right-radius: 0; flex-grow: 1; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border-color); }

        /* --- 9. Invoice Modal Layout --- */
        .invoice-modal-layout { display: flex; gap: 30px; }
        .invoice-form-pane { flex: 1; min-width: 400px; }
        .invoice-preview-pane { flex: 1; background: #f8f9fa; border-radius: var(--border-radius); padding: 24px; border: 1px solid var(--border-color); max-height: 70vh; overflow-y: auto; }

        /* Preview Styles */
        .invoice-preview-pane h2 { color: var(--primary-color); margin-top: 0; font-size: 1.5rem; font-weight: 700; text-align: left; margin-bottom: 20px; border-bottom: 2px solid var(--border-color); padding-bottom: 10px; }
        .voucher-logo img { max-width: 150px; }
        .voucher-header-info { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 14px; }
        .voucher-company-info { line-height: 1.6; text-align: left; }
        .voucher-meta-info { text-align: right; line-height: 1.6; }
        
        .voucher-details { margin-top: 30px; border: 1px solid var(--border-color); border-radius: var(--border-radius); overflow: hidden; background: var(--white); }
        .detail-row { display: flex; padding: 12px; border-bottom: 1px solid var(--border-color); font-size: 14px; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { width: 40%; font-weight: 600; color: var(--text-color-light); text-align: left;}
        .detail-value { width: 60%; text-align: right; color: var(--text-color); font-weight: 500; }
        .highlight { font-weight: 700; color: var(--primary-color); font-size: 1.1rem; }

        /* --- 10. Invoice View Page --- */
        .invoice-view-actions { margin-bottom: 24px; padding: 16px 24px; background: var(--white); border-radius: var(--border-radius); box-shadow: var(--box-shadow); display: flex; flex-wrap: wrap; gap: 12px; }
        .invoice-document { background: var(--white); padding: 40px; border-radius: var(--border-radius); box-shadow: var(--box-shadow); max-width: 800px; margin: 0 auto 24px auto; position: relative; }
        .invoice-document h2 { color: var(--primary-color); margin-top: 0; font-size: 1.5rem; font-weight: 700; text-align: left; margin-bottom: 20px; border-bottom: 2px solid var(--border-color); padding-bottom: 10px; }
        .business-tax-info { background-color: #e8f4f8; padding: 10px; margin-bottom: 15px; border-left: 4px solid var(--primary-color); border-radius: 4px; font-size: 14px; text-align: left; }
        
        /* --- 11. PDF Loading --- */
        .pdf-loading { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); display: none; align-items: center; justify-content: center; z-index: 9999; }
        .pdf-loading-spinner { width: 50px; height: 50px; border: 5px solid var(--secondary-color); border-top-color: var(--primary-color); border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .success-message { background-color: #d4edda; color: #155724; padding: 15px; border-radius: var(--border-radius); margin: 0 24px 24px 24px; }

        /* --- 12. RESPONSIVE ADJUSTMENTS --- */
        @media (max-width: 992px) {
            .invoice-modal-layout { flex-direction: column; }
            .invoice-form-pane { min-width: 100%; }
            .invoice-preview-pane { max-height: none; margin-top: 20px; }
            .form-grid-2 { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            .summary-grid { grid-template-columns: 1fr; gap: 15px; }
            
            .content-card { padding: 16px; }
            .invoices-table { min-width: 800px; }
            .table-responsive-wrapper { overflow-x: auto; -webkit-overflow-scrolling: touch; margin-bottom: 10px; }

            .page-header { width: 100%; }
            .page-header .btn { width: 100%; }

            .modal-content { width: 95%; padding: 16px; max-height: 95vh; }
            .modal-actions { flex-direction: column-reverse; gap: 10px; }
            .modal-actions .btn { width: 100%; }
            
            .invoice-view-actions { flex-direction: column; }
            .invoice-view-actions .btn { width: 100%; justify-content: center; }
            
            .invoice-document { padding: 20px; }
            .voucher-header-info { flex-direction: column; gap: 15px; }
            .voucher-meta-info { text-align: left; }
        }
    </style>
@endpush

@section('content')
<div class="invoice-page-content">
    
    <div class="modal-overlay" id="taxModal" style="{{ $showTaxModal ? 'display: flex;' : 'display: none;' }}">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <h2>Add New Tax Rate</h2>
                <button class="modal-close-btn" onclick="window.location.href='{{ route('billing.invoice') }}'">×</button>
            </div>
            <form method="POST" action="{{ route('invoices.tax.store') }}">
                @csrf
                <div class="form-group">
                    <label>Tax Name</label>
                    <input type="text" name="tax_name" required>
                </div>
                <div class="form-group">
                    <label>Tax Rate (%)</label>
                    <input type="number" name="tax_rate" step="0.01" required>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary">Save Tax Rate</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="discountModal" style="{{ $showDiscountModal ? 'display: flex;' : 'display: none;' }}">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <h2>Add New Discount</h2>
                <button class="modal-close-btn" onclick="window.location.href='{{ route('billing.invoice') }}'">×</button>
            </div>
            <form method="POST" action="{{ route('invoices.discount.store') }}">
                @csrf
                <div class="form-group">
                    <label>Discount Name</label>
                    <input type="text" name="discount_name" required>
                </div>
                <div class="form-group">
                    <label>Discount Value</label>
                    <input type="text" name="discount_value" required placeholder="e.g., 100 or 10%">
                    <small>Use number for fixed amount or add % for percentage</small>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary">Save Discount</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="emailModal" style="display: none;">
        <div class="modal-content modal-md">
            <div class="modal-header">
                <h2>Send Invoice via Email</h2>
                <button class="modal-close-btn" onclick="document.getElementById('emailModal').style.display='none'">×</button>
            </div>
            <form method="POST" action="{{ route('invoices.send-email') }}">
                @csrf
                <input type="hidden" name="invoice_id" id="email_invoice_id" value="">
                
                <div class="form-group">
                    <label>Recipient Email</label>
                    <input type="email" name="email_recipient" id="email_recipient" required>
                </div>
                
                <div class="form-group">
                    <label>Subject</label>
                    <input type="text" name="email_subject" value="Invoice from Stafffy Inc" required>
                </div>
                
                <div class="form-group">
                    <label>Message</label>
                    <textarea name="email_message" rows="4" required>Dear customer,

Please find attached your invoice. For any queries, please don't hesitate to contact us.

Best regards,
Stafffy Inc</textarea>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary">Send Email</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="invoiceModal" style="{{ ($showModal || $editId) ? 'display: flex;' : 'display: none;' }}">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h2>{{ $editId ? 'Edit Invoice' : 'Create Invoice' }}</h2>
                <button class="modal-close-btn" onclick="closeModal()">×</button>
            </div>
            
            <form method="POST" action="{{ $editId ? route('invoices.update', $editId) : route('invoices.store') }}">
                @csrf
                @if($editId)
                    @method('PUT')
                    <input type="hidden" name="invoice_id" value="{{ $editId }}">
                @endif
                
                <div class="invoice-modal-layout">
                    <div class="invoice-form-pane">
                        <div class="business-tax-info">
                            <strong>Current Business Tax Mode:</strong> {{ $taxType }}
                            @if(!$isVat)
                                <br><small style="color: #d9534f;">New invoices will use NON-VAT calculations with 3% withholding tax.</small>
                            @endif
                        </div>
                        
                        @if($editId)
                            <div class="business-tax-info" style="background-color: #fff3cd; border-left-color: #ffc107;">
                                <strong>Original Invoice Mode:</strong> {{ $invoiceTaxType }}
                                <br><small>This invoice will be updated to current business settings when saved.</small>
                            </div>
                        @endif

                        <div class="form-group">
                            <label>Customer Name</label>
                            <input type="text" name="customer_name" id="customerName" required value="{{ $invoiceData ? $invoiceData->Customer_Name : '' }}">
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="customer_email" id="customerEmail" required value="{{ $invoiceData ? $invoiceData->Customer_Email : '' }}">
                        </div>

                        <div class="form-group">
                            <label>Billing Address</label>
                            <textarea name="billing_address" id="billingAddress" required>{{ $invoiceData ? $invoiceData->Billing_Address : '' }}</textarea>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label>Item Description</label>
                                <textarea name="item_name" id="itemName" required style="min-height: 120px;">{{ $invoiceData ? $invoiceData->Item_Name : '' }}</textarea>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label>Price (₱)</label>
                                    <input type="number" name="price" id="price" step="0.01" required value="{{ $invoiceData ? $invoiceData->Price : '' }}">
                                </div>
                                <div class="form-group">
                                    <label>Quantity</label>
                                    <input type="number" name="quantity" id="quantity" required value="{{ $invoiceData ? $invoiceData->Quantity : '' }}">
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                            <div class="form-group" style="flex: 1; min-width: 200px;">
                                <label>Tax</label>
                                <div class="input-group">
                                    <select name="tax_option" id="taxOption" onchange="updatePreviewFromSelect()">
                                        @foreach($taxRates as $tax)
                                            <option value="{{ $tax->id }}" 
                                                {{ ($invoiceData && $invoiceData->tax_id == $tax->id) || 
                                                   ($invoiceData && !$invoiceData->tax_id && $invoiceData->Tax == $tax->tax_rate) ? 'selected' : '' }}>
                                                {{ $tax->tax_name }} ({{ $tax->tax_rate }}%)
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn input-group-btn" onclick="window.location.href='{{ route('billing.invoice', ['add_tax' => 1]) }}'">+</button>
                                </div>
                            </div>
                            
                            <div class="form-group" style="flex: 1; min-width: 200px;">
                                <label>Discount</label>
                                <div class="input-group">
                                    <select name="discount_option" id="discountOption" onchange="updatePreviewFromSelect()">
                                        @foreach($discountRates as $discount)
                                            <option value="{{ $discount->id }}" 
                                                {{ ($invoiceData && $invoiceData->discount_id == $discount->id) || 
                                                   ($invoiceData && !$invoiceData->discount_id && $invoiceData->Discount == $discount->discount_value) ? 'selected' : '' }}>
                                                {{ $discount->discount_name }} ({{ $discount->discount_value }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn input-group-btn" onclick="window.location.href='{{ route('billing.invoice', ['add_discount' => 1]) }}'">+</button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Terms & Conditions</label>
                            <textarea name="terms" id="terms">{{ $invoiceData ? $invoiceData->Terms : '' }}</textarea>
                        </div>
                    </div>

                    <div class="invoice-preview-pane">
                        <div class="voucher-preview-content">
                            <div class="voucher-logo">
                                <img src="{{ asset('assets/images/Stafify-Logo.png') }}" alt="Stafify Logo" style="max-width: 150px;">
                            </div>
                            <h2>E-Invoice</h2>
                            
                            <div class="voucher-header-info">
                                <div class="voucher-company-info">
                                    <strong>Stafffy Inc</strong><br>
                                    54 Irving Street<br>New Asinan<br>Olongapo City, 2200 ZAMBALES<br>PHILIPPINES<br>staffify@gmail.com
                                </div>
                                <div class="voucher-meta-info">
                                    <p><strong>Invoice #:</strong> <span id="previewInvoiceId">New</span></p>
                                    <p><strong>Date:</strong> <span id="previewDateIssued">{{ date('F j, Y') }}</span></p>
                                    <p><strong>Due Date:</strong> <span id="previewDueDate">{{ date('F j, Y', strtotime('+7 days')) }}</span></p>
                                </div>
                            </div>
                            
                            <div class="voucher-details">
                                <div class="detail-row">
                                    <div class="detail-label">Bill To:</div>
                                    <div class="detail-value" id="previewCustomerName">Customer Name</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Email:</div>
                                    <div class="detail-value" id="previewCustomerEmail">-</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Address:</div>
                                    <div class="detail-value" id="previewCustomerAddress">-</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Item:</div>
                                    <div class="detail-value" id="previewItem">-</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Price:</div>
                                    <div class="detail-value">₱<span id="previewPrice">0.00</span></div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Quantity:</div>
                                    <div class="detail-value" id="previewQty">0</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Subtotal:</div>
                                    <div class="detail-value">₱<span id="previewSubtotal">0.00</span></div>
                                </div>
                                
                                <div id="vatFields" style="display: {{ $isVat ? 'contents' : 'none' }};">
                                    <div class="detail-row" style="border-top: 2px solid var(--border-color);">
                                        <div class="detail-label">Subtotal</div>
                                        <div class="detail-value"><span id="subtotal">₱0.00</span></div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Discount</div>
                                        <div class="detail-value"><span id="discount">₱0.00</span></div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Tax (12%)</div>
                                        <div class="detail-value"><span id="tax">₱0.00</span></div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label highlight" style="font-size: 1.1rem;">Total</div>
                                        <div class="detail-value highlight"><span id="total">₱0.00</span></div>
                                    </div>
                                </div>
                                
                                <div id="nonVatFields" style="display: {{ $isVat ? 'none' : 'contents' }};">
                                    <div class="detail-row" style="padding: 8px 12px; background: #fef2f2; border-top: 2px solid var(--border-color); border-bottom: 1px solid var(--border-color);">
                                        <div class="detail-value" style="width: 100%; text-align: center; color: var(--danger-color); font-weight: 600; font-size: 12px; text-align: left;">
                                            THIS DOCUMENT IS NOT VALID FOR CLAIM OF INPUT TAX.
                                        </div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Subtotal</div>
                                        <div class="detail-value"><span id="subtotal_nonvat">₱0.00</span></div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Discount</div>
                                        <div class="detail-value"><span id="discount_nonvat">₱0.00</span></div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Withholding Tax (3%)</div>
                                        <div class="detail-value"><span id="wht_nonvat">₱0.00</span></div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label highlight" style="font-size: 1.1rem;">Total</div>
                                        <div class="detail-value highlight"><span id="total_nonvat">₱0.00</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="window.print()">Print Invoice</button>
                    <button type="submit" class="btn btn-primary">{{ $editId ? 'Update Invoice' : 'Save Invoice' }}</button>
                </div>
            </form>
        </div>
    </div>

    @if((!$viewId || !$invoiceData) && !$showTaxModal && !$showDiscountModal)
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
            <div class="stat-icon-wrapper icon-total">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div class="summary-label">Total Invoiced</div>
            <div class="summary-value text-primary-custom">₱{{ number_format($totalInvoiced ?? 0, 2) }}</div>
        </div>
        <div class="summary-card-item">
            <div class="stat-icon-wrapper icon-vat">
                 <i class="fas fa-check-circle"></i>
            </div>
            <div class="summary-label">Total VAT</div>
            <div class="summary-value text-success">₱{{ number_format($totalVat ?? 0, 2) }}</div>
        </div>
        <div class="summary-card-item">
            <div class="stat-icon-wrapper icon-nonvat">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="summary-label">Total Non-VAT</div>
             <div class="summary-value text-danger">₱{{ number_format($totalNonVat ?? 0, 2) }}</div>
        </div>
    </div>
    @endif

    @if($emailSent)
        <div class="success-message">Invoice has been sent successfully via email!</div>
    @endif

    @if($viewId && $invoiceData)
        @php
             $calculations = [
                'subtotal' => $invoiceData->Price * $invoiceData->Quantity,
                'discount' => 0,
                'tax' => 0,
                'total' => 0,
            ];
            
            $discountInput = $invoiceData->Discount;
            if (strpos($discountInput, '%') !== false) {
                $calculations['discount'] = $calculations['subtotal'] * (floatval($discountInput) / 100);
            } else {
                $calculations['discount'] = floatval($discountInput);
            }
            
            $taxableAmount = $calculations['subtotal'] - $calculations['discount'];
            
            if ($invoiceIsVat) {
                $calculations['tax'] = $taxableAmount * ($invoiceData->Tax / 100);
                $calculations['total'] = $taxableAmount + $calculations['tax'];
            } else {
                $calculations['tax'] = $taxableAmount * 0.03;
                $calculations['total'] = $taxableAmount - $calculations['tax'];
            }
        @endphp
        
        <div class="invoice-view">
            <div class="invoice-view-actions btn-group">
                <button onclick="window.location.href='{{ route('billing.invoice') }}'" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                    Back to List
                </button>
                <button onclick="window.location.href='{{ route('billing.invoice', ['edit' => $viewId]) }}'" class="btn btn-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                    Edit
                </button>
                 <button type="button" class="btn btn-secondary" onclick="window.print()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0c.317.087.65.162.98.232m-9.98 0l-.04.022c-.512.227-.815.76-.815 1.319v2.1a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-2.1c0-.56-.303-1.092-.815-1.319l-.04-.022m-10.76-9.281c.24.03.48.062.72.096m-.72-.096c.317.087.65.162.98.232m-9.98 0l-.04.022c-.512.227-.815.76-.815 1.319v2.1a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-2.1c0-.56-.303-1.092-.815-1.319l-.04-.022m-10.76-9.281c.24.03.48.062.72.096m-.72-.096L9 5.25l3 3m0 0l3-3m-3 3v5.25m-6 3h12" /></svg>
                    Print
                </button>
                <button onclick="openEmailModal({{ $viewId }}, '{{ addslashes($invoiceData->Customer_Email) }}')" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                    Email
                </button>
                <form id="delete-form" action="{{ route('invoices.destroy', $viewId) }}" method="POST" style="display: none;" onsubmit="return confirm('Delete this invoice?')">
                    @csrf @method('DELETE')
                </form>
                <button onclick="document.getElementById('delete-form').submit();" class="btn btn-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12.502 0c-.411.011-.82.028-1.229.05c-3.17.198-6.096.657-8.634 1.183L3.15 5.79m14.456 0L13.8 3.51a2.25 2.25 0 0 0-3.182 0L6.844 5.79m11.136 0c-0.291-.01-0.582-.019-0.873-.028m-10.01 0c-.29.009-.581.018-.872.028" /></svg>
                    Delete
                </button>
            </div>
            
            <div class="invoice-document">
                 <div class="receipt-header">
                    <div class="company-info">
                        <strong>Stafffy Inc</strong><br>
                        54 Irving Street<br>New Asinan<br>Olongapo City, 2200 ZAMBALES<br>PHILIPPINES<br>staffify@gmail.com
                    </div>
                    <div class="receipt-meta">
                        <p class="receipt-id">Invoice #: E-INV-{{ $invoiceData->created_at->format('Ymd') }}-{{ sprintf('%06d', $invoiceData->Invoice_Id) }}</p>
                        <p><strong>Date:</strong> {{ $invoiceData->created_at ? $invoiceData->created_at->format('F j, Y') : date('F j, Y') }}</p>
                        <p><strong>Due Date:</strong> {{ $invoiceData->created_at ? $invoiceData->created_at->copy()->addDays(7)->format('F j, Y') : date('F j, Y', strtotime('+7 days')) }}</p>
                    </div>
                </div>
                <hr>
                 <div style="display: flex; justify-content: space-between; font-size: 14px; margin: 20px 0; flex-wrap: wrap; gap: 20px;">
                    <div>
                        <strong>Bill To:</strong><br>
                        {{ $invoiceData->Customer_Name }}<br>
                        Email: {{ $invoiceData->Customer_Email }}<br>
                        Address: {{ $invoiceData->Billing_Address }}
                    </div>
                    <div>
                        <strong>Payment Details:</strong><br>
                        Status: Pending<br>
                        Due: Upon receipt<br>
                        Method: Bank Transfer
                    </div>
                </div>
                
                <div class="voucher-details" style="margin-top: 30px;">
                     <div class="detail-row">
                        <div class="detail-label">Item:</div>
                        <div class="detail-value" style="text-align: left;">{{ $invoiceData->Item_Name }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Price:</div>
                        <div class="detail-value">₱{{ number_format($invoiceData->Price, 2) }}</div>
                    </div>
                     <div class="detail-row">
                        <div class="detail-label">Quantity:</div>
                        <div class="detail-value">{{ $invoiceData->Quantity }}</div>
                    </div>
                    
                    @if($invoiceIsVat)
                         <div class="detail-row" style="border-top: 2px solid var(--border-color);">
                            <div class="detail-label">Subtotal</div>
                            <div class="detail-value">₱{{ number_format($calculations['subtotal'], 2) }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Discount</div>
                            <div class="detail-value">₱{{ number_format($calculations['discount'], 2) }}</div>
                        </div>
                         <div class="detail-row">
                            <div class="detail-label">Tax (12%)</div>
                            <div class="detail-value">₱{{ number_format($calculations['tax'], 2) }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label highlight">Total</div>
                            <div class="detail-value highlight">₱{{ number_format($calculations['total'], 2) }}</div>
                        </div>
                    @else
                        <div class="detail-row" style="padding: 8px 12px; background: #fef2f2; border-top: 2px solid var(--border-color); border-bottom: 1px solid var(--border-color);">
                            <div class="detail-value" style="width: 100%; text-align: center; color: var(--danger-color); font-weight: 600; font-size: 12px;">
                                THIS DOCUMENT IS NOT VALID FOR CLAIM OF INPUT TAX.
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Subtotal</div>
                            <div class="detail-value">₱{{ number_format($calculations['subtotal'], 2) }}</div>
                        </div>
                         <div class="detail-row">
                            <div class="detail-label">Discount</div>
                            <div class="detail-value">₱{{ number_format($calculations['discount'], 2) }}</div>
                        </div>
                         <div class="detail-row">
                            <div class="detail-label">Withholding Tax (3%)</div>
                            <div class="detail-value">₱{{ number_format($calculations['tax'], 2) }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label highlight">Total</div>
                            <div class="detail-value highlight">₱{{ number_format($calculations['total'], 2) }}</div>
                        </div>
                    @endif
                </div>
                
                 @if(!empty($invoiceData->Terms))
                    <div class="terms" style="margin-top: 30px; font-size: 14px;">
                        <h4>Terms & Conditions</h4>
                        <p>{!! nl2br(e($invoiceData->Terms)) !!}</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="content-card">
            @if($invoices && $invoices->count() > 0)
                <div class="table-responsive-wrapper">
                    <table class="invoices-table">
                        <thead>
                            <tr>
                                <th>E-Invoice #</th>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Tax Mode</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach($invoices as $invoice)
                                @php
                                     $listInvoiceIsVat = true;
                                    $listInvoiceTaxType = 'VAT';
                                    
                                    if (isset($invoice->invoice_mode) && !empty($invoice->invoice_mode)) {
                                        $listInvoiceIsVat = ($invoice->invoice_mode === 'VAT');
                                        $listInvoiceTaxType = $invoice->invoice_mode;
                                    } elseif (isset($invoice->tax_type_at_creation) && !empty($invoice->tax_type_at_creation)) {
                                        $listInvoiceIsVat = (strpos($invoice->tax_type_at_creation, 'Non-VAT') === false);
                                        $listInvoiceTaxType = $listInvoiceIsVat ? 'VAT' : 'NON-VAT';
                                    }
                                    
                                    $discountInput = $invoice->Discount;
                                    $subtotal = $invoice->Price * $invoice->Quantity;
                                    $discountAmount = 0;
                                    if (strpos($discountInput, '%') !== false) {
                                        $discountAmount = $subtotal * (floatval($discountInput) / 100);
                                    } else {
                                        $discountAmount = floatval($discountInput);
                                    }
                                    
                                    $taxableAmount = $subtotal - $discountAmount;
                                    
                                    if ($listInvoiceIsVat) {
                                        $tax = $taxableAmount * ($invoice->Tax / 100);
                                        $total = $taxableAmount + $tax;
                                    } else {
                                        $tax = $taxableAmount * 0.03;
                                        $total = $taxableAmount - $tax;
                                    }
                                @endphp
                                <tr>
                                    <td>
                                         @if($invoice->created_at)
                                            E-INV-{{ $invoice->created_at->format('Ymd') }}-{{ sprintf('%06d', $invoice->Invoice_Id) }}
                                        @else
                                            {{ $invoice->Invoice_Id }}
                                        @endif
                                    </td>
                                    <td>{{ $invoice->Customer_Name }}</td>
                                    <td>{{ $invoice->Customer_Email }}</td>
                                    <td>{{ $invoice->Item_Name }}</td>
                                    <td>₱{{ number_format($invoice->Price, 2) }}</td>
                                    <td>{{ $invoice->Quantity }}</td>
                                    <td>₱{{ number_format($total, 2) }}</td>
                                    <td><span class="tax-badge {{ $listInvoiceIsVat ? 'tax-vat' : 'tax-nonvat' }}">{{ $listInvoiceTaxType }}</span></td>
                                    <td>{{ $invoice->created_at ? $invoice->created_at->format('M j, Y') : 'N/A' }}</td>
                                    <td>
                                        <div class="action-buttons btn-group">
                                            <button class="btn btn-icon" title="View" onclick="window.location.href='{{ route('billing.invoice', ['view' => $invoice->Invoice_Id]) }}'">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.634l3.061-5.636a.5.5 0 0 1 .44-.223h13.911a.5.5 0 0 1 .44.223l3.061 5.636a1.012 1.012 0 0 1 0 .634l-3.061 5.636a.5.5 0 0 1-.44.223H5.537a.5.5 0 0 1-.44-.223L2.036 12.322Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                            </button>
                                            <button class="btn btn-icon" title="Edit" onclick="window.location.href='{{ route('billing.invoice', ['edit' => $invoice->Invoice_Id]) }}'">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                            </button>
                                             <button class="btn btn-icon" title="Email" onclick="openEmailModal({{ $invoice->Invoice_Id }}, '{{ addslashes($invoice->Customer_Email) }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                                            </button>
                                             <form id="delete-form-{{ $invoice->Invoice_Id }}" action="{{ route('invoices.destroy', $invoice->Invoice_Id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this invoice?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-icon" title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12.502 0c-.411.011-.82.028-1.229.05c-3.17.198-6.096.657-8.634 1.183L3.15 5.79m14.456 0L13.8 3.51a2.25 2.25 0 0 0-3.182 0L6.844 5.79m11.136 0c-0.291-.01-0.582-.019-0.873-.028m-10.01 0c-.29.009-.581.018-.872.028" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>No invoices found. Create your first invoice.</p>
            @endif
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Pass PHP values to JavaScript
const businessIsVat = {{ $isVat ? 'true' : 'false' }};
const businessTaxType = '{{ addslashes($taxType) }}';

function calculatePreview() {
    const priceEl = document.getElementById('price');
    const qtyEl = document.getElementById('quantity');
    if (!priceEl || !qtyEl) return;
    
    const price = parseFloat(priceEl.value) || 0;
    const qty = parseInt(qtyEl.value) || 0;
    const subtotal = price * qty;

    const taxSelect = document.getElementById('taxOption');
    if (!taxSelect) return;
    const selectedTaxOption = taxSelect.options[taxSelect.selectedIndex];
    const taxText = selectedTaxOption.text;
    const taxMatch = taxText.match(/\(([^)]+)\)/);
    const taxRate = taxMatch ? parseFloat(taxMatch[1]) : 0;

    const discountSelect = document.getElementById('discountOption');
    if (!discountSelect) return;
    const selectedDiscountOption = discountSelect.options[discountSelect.selectedIndex];
    const discountText = selectedDiscountOption.text;
    const discountMatch = discountText.match(/\(([^)]+)\)/);
    const discountValue = discountMatch ? discountMatch[1] : '0';
    
    let discount = 0;
    if (discountValue.includes('%')) {
        discount = subtotal * (parseFloat(discountValue) / 100);
    } else {
        discount = parseFloat(discountValue) || 0;
    }

    const taxableAmount = subtotal - discount;
    
    let tax = 0;
    let total = taxableAmount;
    
    if (businessIsVat) {
        tax = taxableAmount * (taxRate / 100);
        total = taxableAmount + tax;
    } else {
        tax = taxableAmount * 0.03;
        total = taxableAmount - tax;
    }

    const previewSubtotal = document.getElementById('previewSubtotal');
    if (previewSubtotal) previewSubtotal.textContent = subtotal.toFixed(2);
    
    const subtotalEl = document.getElementById('subtotal');
    const discountEl = document.getElementById('discount');
    const taxEl = document.getElementById('tax');
    const totalEl = document.getElementById('total');
    
    if (subtotalEl) subtotalEl.textContent = '₱' + subtotal.toFixed(2);
    if (discountEl) discountEl.textContent = '₱' + discount.toFixed(2);
    if (taxEl) taxEl.textContent = '₱' + tax.toFixed(2);
    if (totalEl) totalEl.textContent = '₱' + total.toFixed(2);
    
    const subtotalNonVatEl = document.getElementById('subtotal_nonvat');
    const discountNonVatEl = document.getElementById('discount_nonvat');
    const whtNonVatEl = document.getElementById('wht_nonvat');
    const totalNonVatEl = document.getElementById('total_nonvat');
    
    if (subtotalNonVatEl) subtotalNonVatEl.textContent = subtotal.toFixed(2);
    if (discountNonVatEl) discountNonVatEl.textContent = discount.toFixed(2);
    if (whtNonVatEl) whtNonVatEl.textContent = tax.toFixed(2);
    if (totalNonVatEl) totalNonVatEl.textContent = total.toFixed(2);
    
    const vatFields = document.getElementById('vatFields');
    const nonVatFields = document.getElementById('nonVatFields');
    if (vatFields) vatFields.style.display = businessIsVat ? 'block' : 'none';
    if (nonVatFields) nonVatFields.style.display = businessIsVat ? 'none' : 'block';
}

function updatePreview() {
    const customerNameEl = document.getElementById('customerName');
    const previewCustomerNameEl = document.getElementById('previewCustomerName');
    if (customerNameEl && previewCustomerNameEl) {
        previewCustomerNameEl.textContent = customerNameEl.value || "Customer Name";
    }
    
    const customerEmailEl = document.getElementById('customerEmail');
    const previewCustomerEmailEl = document.getElementById('previewCustomerEmail');
    if (customerEmailEl && previewCustomerEmailEl) {
        previewCustomerEmailEl.textContent = customerEmailEl.value || "-";
    }
    
    const billingAddressEl = document.getElementById('billingAddress');
    const previewCustomerAddressEl = document.getElementById('previewCustomerAddress');
    if (billingAddressEl && previewCustomerAddressEl) {
        previewCustomerAddressEl.textContent = billingAddressEl.value || "-";
    }
    
    const itemNameEl = document.getElementById('itemName');
    const previewItemEl = document.getElementById('previewItem');
    if (itemNameEl && previewItemEl) {
        previewItemEl.textContent = itemNameEl.value || "-";
    }
    
    const priceEl = document.getElementById('price');
    const previewPriceEl = document.getElementById('previewPrice');
    if (priceEl && previewPriceEl) {
        previewPriceEl.textContent = (parseFloat(priceEl.value) || 0).toFixed(2);
    }
    
    const quantityEl = document.getElementById('quantity');
    const previewQtyEl = document.getElementById('previewQty');
    if (quantityEl && previewQtyEl) {
        previewQtyEl.textContent = quantityEl.value || "0";
    }
    
    calculatePreview();
}

function updatePreviewFromSelect() {
    calculatePreview();
}

function openModal() {
    const modal = document.getElementById('invoiceModal');
    if (modal) {
        modal.style.display = 'flex';
        calculatePreview();
    }
}

function closeModal() {
    const modal = document.getElementById('invoiceModal');
    if (modal) {
        modal.style.display = 'none';
        @if($editId)
            window.location.href = '{{ route('billing.invoice') }}';
        @endif
    }
}

function openEmailModal(invoiceId, email) {
    const invoiceIdEl = document.getElementById('email_invoice_id');
    const emailRecipientEl = document.getElementById('email_recipient');
    const emailModal = document.getElementById('emailModal');
    
    if (invoiceIdEl) invoiceIdEl.value = invoiceId;
    if (emailRecipientEl) emailRecipientEl.value = email;
    if (emailModal) emailModal.style.display = 'flex';
}

document.addEventListener("DOMContentLoaded", function() {
    const inputFields = ['customerName', 'customerEmail', 'billingAddress', 'itemName', 'price', 'quantity'];
    
    inputFields.forEach(function(id) {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener("input", updatePreview);
        }
    });

    // Add listeners for tax and discount dropdowns
    const taxOption = document.getElementById('taxOption');
    const discountOption = document.getElementById('discountOption');
    if (taxOption) {
        taxOption.addEventListener('change', updatePreviewFromSelect);
    }
    if (discountOption) {
        discountOption.addEventListener('change', updatePreviewFromSelect);
    }
    
    updatePreview();
    
    console.log('Business Tax Mode:', businessTaxType);
    console.log('VAT Mode:', businessIsVat ? 'Enabled' : 'Disabled');
    
    @if($editId)
        const modal = document.getElementById('invoiceModal');
        if (modal) {
            modal.style.display = 'flex';
            updatePreview();
        }
    @endif
});
</script>
@endpush