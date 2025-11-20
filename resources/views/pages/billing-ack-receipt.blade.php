@extends('layouts.app')

@section('title', 'Acknowledgment Receipt')
@section('description', 'Manage acknowledgment receipts - Create, view, edit, and send receipts to customers')

@push('styles')
    <style>
        /* --- 1. Root Variables --- */
        :root {
            --primary-color: #3B82F6; /* Staffify blue from sidebar */
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

        /* --- 2. General Page Layout --- */
        .ack-receipt-page {
            padding: 0;
            max-width: 100%;
            margin: 0 auto;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
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

        /* --- 3. Unified Button System --- */
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
            width: 38px; /* Square */
            height: 38px;
            border-radius: 50%; /* Circle */
            background-color: var(--secondary-color);
            color: var(--text-color-light);
            border: none;
        }
        .btn-icon:hover {
            background-color: var(--primary-color);
            color: var(--white);
        }
        
        /* Button group for actions */
        .btn-group {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        /* --- 4. Receipt List Table --- */
        .receipts-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .receipts-table th,
        .receipts-table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-color);
            font-size: 14px;
        }

        .receipts-table th {
            background-color: #f8f9fa;
            color: var(--text-color-light);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
        }

        .receipts-table tr:hover {
            background-color: var(--secondary-color); /* This is a visible light grey */
        }

        .receipts-table .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: flex-start;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-signed {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        /* --- 5. Unified Modal System --- */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            /* 🚀 FIX 1: Center the modal on screen */
            align-items: center; 
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(3px);
            /* 🚀 FIX 2: Remove scrolling from the overlay */
            /* overflow-y: auto; <-- REMOVED */
            /* padding: 40px 0; <-- REMOVED */
        }

        .modal-content {
            background: var(--white);
            padding: 24px;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            position: relative;
            animation: modalFadeIn 0.3s ease-out;
            width: 90%;
            /* 🚀 FIX 3: Add max-height and scrolling to the modal itself */
            max-height: 90vh; /* 90% of the viewport height */
            overflow-y: auto; 
            /* margin-bottom: 40px; <-- REMOVED */
        }

        /* Modal Sizes */
        .modal-lg { max-width: 1100px; }
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
        
          /* --- 2. Summary Card Styles (FIXED FOR FONT-AWESOME) --- */
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
    /* FIX: This now targets <i> and sets font-size */
    .stat-icon-wrapper i {
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
    .icon-receipts {
        /* FIX: Changed whitesmoke to var(--primary-light) */
        background-color: whitesmoke; 
        color: var(--primary-color);
    }
    .icon-pending {
        background-color: #fef3c7; /* Light yellow */
        color: var(--warning-color);
    }
    .icon-received {
        background-color: #d1fae5; /* Light green */
        color: #065f46;
    }
    
    /* Text Colors */
    .text-success { color: #065f46 !important; }
    .text-warning { color: #92400e !important; }
    .text-primary-custom { color: var(--primary-color) !important; }

        /* --- 6. Form Styling (Inside Modals) --- */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            text-align: left;
        }
        
        .form-group.col-span-2 {
            grid-column: span 2;
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
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border-radius: var(--border-radius);
            border: 1px solid var(--border-color);
            font-size: 14px;
            transition: var(--transition);
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
        .input-group select {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border-left: none;
        }
        .input-group input {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .input-group-btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border: 1px solid var(--border-color);
            border-left: none;
            padding: 0 12px;
            background: #f8f9fa;
        }
        
        .modal-actions {
            display: flex;
            justify-content: flex-end; /* Align buttons to the right */
            gap: 12px;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
        }

        /* --- 7. Receipt Create/Edit Modal (Specifics) --- */
        .receipt-modal-layout {
            display: flex;
            gap: 30px;
        }
        .receipt-form-pane {
            flex: 1; /* Form takes 1 part */
            min-width: 400px;
        }
        .receipt-preview-pane {
            flex: 1; /* Preview takes 1 part */
            background: #f8f9fa;
            border-radius: var(--border-radius);
            padding: 24px;
            border: 1px solid var(--border-color);
            max-height: 70vh;
            overflow-y: auto;
        }
        
        /* Handle responsive for modal layout */
        @media (max-width: 992px) {
            .receipt-modal-layout {
                flex-direction: column;
            }
            .receipt-preview-pane {
                max-height: 500px; /* Set a fixed height when stacked */
            }
        }

        /* --- 8. Receipt View Page & Preview --- */
        .invoice-view-actions {
            margin-bottom: 24px;
            padding: 16px;
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            display: flex;
            flex-wrap: wrap; /* Allow buttons to wrap on small screens */
            gap: 12px;
        }

        .invoice-document {
            background: var(--white);
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            max-width: 800px; /* A4-like width */
            margin: 0 auto; /* Center the document */
        }
        
        @media (max-width: 768px) {
            .invoice-document {
                padding: 20px;
            }
        }
        
        /* Styles shared by preview-pane and invoice-document */
        .receipt-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap; /* Wrap for small screens */
            gap: 16px;
        }
        .receipt-header .company-info {
            font-size: 14px;
            line-height: 1.6;
            text-align: left;
        }
        .receipt-header .receipt-meta {
            text-align: right;
            font-size: 14px;
            flex-shrink: 0; /* Prevent shrinking */
        }
        .receipt-meta p { margin: 0 0 5px 0; }
        .receipt-meta .receipt-id { font-weight: bold; font-size: 1.1em; }
        
        .receipt-statement {
            margin: 24px 0;
            font-size: 15px;
            line-height: 1.7;
            text-align: left;
        }
        .receipt-statement .highlight {
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .receipt-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 24px;
            font-size: 14px;
            line-height: 1.6;
        }
        
        @media (max-width: 600px) {
            .receipt-details-grid {
                grid-template-columns: 1fr; /* Stack on small screens */
            }
        }

        .receipt-details-box {
            background: #f8f9fa;
            border: 1px solid var(--border-color);
            padding: 16px;
            border-radius: var(--border-radius);
            text-align: left;
        }
        .receipt-details-box p { margin: 0 0 8px 0; }
        .receipt-details-box p:last-child { margin-bottom: 0; }
        
        .receipt-notes { 
            margin-top: 24px; font-size: 14px;
            line-height: 1.6;
            text-align: left;
        }
        .receipt-signatures {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            font-size: 14px;
        }
        .signature-box {
            width: 45%;
            text-align: center;
            padding-top: 10px;
            border-top: 1px solid #999;
        }
        
        .receipt-signed-badge {
            color: var(--success-color);
            font-weight: bold;
        }
        
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
        }

        .receipt-preview-pane h2 {
            color: var(--primary-color);
            margin-top: 0;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: left;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 10px;
        }

         .receipt-logo {
            display: flex;
            position: relative;
        }

        .receipt-logo img {
            height: 25px;
        }
        /* PDF Loading Spinner */
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
            display: none; /* Hidden by default */
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

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        window.openModal = function() {
            const modal = document.getElementById("receiptModal");
            if (modal) {
                modal.style.display = "flex";
            }
        };

        window.closeModal = function() {
            const modal = document.getElementById('receiptModal');
            if (modal) {
                modal.style.display = 'none';
            }
            @if($editId)
                // Only redirect if we were on an edit page
                if (window.location.search.includes('edit=')) {
                    window.location.href = '{{ route('billing.ack.receipt') }}';
                }
            @endif
        };

        function generatePDF() {
            const loader = document.createElement('div');
            loader.className = 'pdf-loading';
            loader.innerHTML = '<div class="pdf-loading-spinner"></div>';
            document.body.appendChild(loader);
            loader.style.display = 'flex';

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4'
            });

            let receiptElement;
            // Check if we are in the "View" page
            if (document.querySelector('.invoice-document')) {
                receiptElement = document.querySelector('.invoice-document');
            // Check if we are in the "Create/Edit" modal
            } else if (document.querySelector('.receipt-preview-pane')) {
                receiptElement = document.querySelector('.receipt-preview-pane');
            } else {
                alert('Could not find receipt content');
                loader.remove();
                return;
            }

            // Clone the node to avoid modifying the original
            const elementClone = receiptElement.cloneNode(true);
            
            // Apply styling for PDF generation
            elementClone.style.width = '190mm'; // A4 width minus margins
            elementClone.style.padding = '20px';
            elementClone.style.boxSizing = 'border-box';
            elementClone.style.backgroundColor = 'white';
            elementClone.style.border = 'none'; // Remove preview border
            elementClone.style.boxShadow = 'none'; // Remove shadow
            
            // Append clone off-screen to render
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
                const pageWidth = doc.internal.pageSize.getWidth();
                const pageHeight = doc.internal.pageSize.getHeight();
                
                const imgWidth = canvas.width;
                const imgHeight = canvas.height;
                const ratio = imgWidth / imgHeight;

                let finalWidth = pageWidth - 20; // Page width with 10mm margins
                let finalHeight = finalWidth / ratio;

                // Check if it's too tall
                if (finalHeight > (pageHeight - 20)) {
                    finalHeight = pageHeight - 20; // Page height with 10mm margins
                    finalWidth = finalHeight * ratio;
                }

                const xPos = (pageWidth - finalWidth) / 2; // Center horizontally
                const yPos = 10; // 10mm top margin
                
                doc.addImage(imgData, 'PNG', xPos, yPos, finalWidth, finalHeight);
                doc.save('receipt_' + (new Date().getTime()) + '.pdf');
                
                elementClone.remove();
                loader.remove();
            }).catch(err => {
                console.error('Error generating PDF:', err);
                alert('Error generating PDF. Please try again.');
                elementClone.remove();
                loader.remove();
            });
        }

        document.addEventListener("DOMContentLoaded", function () {
            window.openEmailModal = function(receiptId, email) {
                document.getElementById('email_receipt_id').value = receiptId;
                document.getElementById('email_recipient').value = email;
                document.getElementById('emailModal').style.display = 'flex';
            };
            
            window.closeEmailModal = function() {
                document.getElementById('emailModal').style.display = 'none';
            };
        });

        window.togglePurposeFields = function() {
            const purposeType = document.getElementById('purposeType').value;
            const paymentFields = document.getElementById('paymentFields');
            const itemsFields = document.getElementById('itemsFields');
            const purposeStatementPreview = document.getElementById('purposeStatementPreview');
            const itemsStatementPreview = document.getElementById('itemsStatementPreview');
            
            if (purposeType === 'payment') {
                paymentFields.style.display = 'block';
                itemsFields.style.display = 'none';
                purposeStatementPreview.style.display = 'inline';
                itemsStatementPreview.style.display = 'none';
            } else {
                paymentFields.style.display = 'none';
                itemsFields.style.display = 'block';
                purposeStatementPreview.style.display = 'none';
                itemsStatementPreview.style.display = 'inline';
          
          }
            updatePreview();
        };

        function updatePreview() {
            // Check if elements exist before updating
            if (document.getElementById('previewCustomerName')) {
                document.getElementById('previewCustomerName').textContent = 
                    document.getElementById('customerName').value || "Full Name";
            }
            if (document.getElementById('previewCustomerEmail')) {
                document.getElementById('previewCustomerEmail').textContent = 
                    document.getElementById('customerEmail').value || "-";
            }
            if (document.getElementById('previewCustomerPhone')) {
                document.getElementById('previewCustomerPhone').textContent = 
                    document.getElementById('contactNumber').value || "-";
            }
            if (document.getElementById('previewCustomerAddress')) {
                document.getElementById('previewCustomerAddress').textContent = 
                    document.getElementById('address').value || "-";
            }
            
            const purposeType = document.getElementById('purposeType').value;
            
            if (purposeType === 'payment') {
                if (document.getElementById('previewPurpose')) {
                    document.getElementById('previewPurpose').textContent = 
                        document.getElementById('paymentFor').value || "payment purpose";
                }
            } else {
                if (document.getElementById('previewItems')) {
                    document.getElementById('previewItems').textContent = 
                        document.getElementById('itemsReceived').value || "items";
                }
            }
            
            if (document.getElementById('previewLocation')) {
                document.getElementById('previewLocation').textContent = 
                    document.getElementById('location').value || "Olongapo City";
            }
            
            if (document.getElementById('previewAmount')) {
                document.getElementById('previewAmount').textContent = 
                    (parseFloat(document.getElementById('amount').value) || 0).toFixed(2);
            }
            
            if (document.getElementById('paymentStatusPreview')) {
                const paymentStatus = document.getElementById('paymentStatus').value;
                document.getElementById('paymentStatusPreview').textContent = 
                    paymentStatus === 'partial' ? '(Partial Payment)' : 
                    paymentStatus === 'full' ? '(Full Payment)' : '(Down Payment)';
            }
            
            if (document.getElementById('previewPaymentMethod')) {
                const paymentMethodSelect = document.getElementById('paymentMethod');
                const selectedPaymentMethod = paymentMethodSelect.options[paymentMethodSelect.selectedIndex].text;
                document.getElementById('previewPaymentMethod').textContent = selectedPaymentMethod;
            }
            
            if (document.getElementById('previewReferenceNumber')) {
                document.getElementById('previewReferenceNumber').textContent = 
                    document.getElementById('referenceNumber').value || "-";
            }
            
            if (document.getElementById('previewNotes')) {
                document.getElementById('previewNotes').innerHTML = 
                    document.getElementById('notes').value.replace(/\n/g, '<br>') || "";
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            ['customerName', 'customerEmail', 'contactNumber', 'address', 'purposeType', 'paymentFor', 'itemsReceived', 
             'location', 'amount', 'paymentStatus', 'paymentMethod', 'referenceNumber', 'notes'].forEach(function(id) {
                const element = document.getElementById(id);
                if (element) {
                  {
                    element.addEventListener('input', updatePreview);
                    element.addEventListener('change', updatePreview);
                }
                }
            });

            if (document.getElementById('purposeType')) {
                togglePurposeFields();
                updatePreview();
            }
        });

        @if($editId)
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById('receiptModal');
            if (modal) {
                modal.style.display = 'flex';
                updatePreview(); // Run preview on load for edit
            }
        });
        @endif
    </script>
@endpush

@section('content')
<div class="ack-receipt-page">

    {{-- PAYMENT METHOD MODAL (Modal 1) --}}
    <div class="modal-overlay" id="paymentMethodModal" style="{{ $showPaymentMethodModal ? 'display: flex;' : 'display: none;' }}">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <h2>Add New Payment Method</h2>
                <button class="modal-close-btn" onclick="window.location.href='{{ route('billing.ack.receipt') }}'">×</button>
            </div>
            
            <form method="POST" action="{{ route('ack.receipt.payment-method.store') }}">
                @csrf
                <div class="form-group">
                    <label>Method Name</label>
                    <input type="text" name="method_name" required>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary">Save Payment Method</button>
                </div>
            </form>
        </div>
    </div>

    {{-- EMAIL MODAL (Modal 2) --}}
    <div class="modal-overlay" id="emailModal" style="display: none;">
        <div class="modal-content modal-md">
            <div class="modal-header">
                <h2>Send Receipt via Email</h2>
                <button class="modal-close-btn" onclick="closeEmailModal()">×</button>
            </div>
            
            <form method="POST" action="{{ route('ack.receipt.send-email') }}">
                @csrf
                <input type="hidden" name="receipt_id" id="email_receipt_id" value="">
                
                <div class="form-group">
                    <label>Recipient Email</label>
                    <input type="email" name="email_recipient" id="email_recipient" required>
                </div>
                
                <div class="form-group">
                    <label>Subject</label>
                    <input type="text" name="email_subject" value="Receipt from Stafffy Inc" required>
                </div>
                
                <div class="form-group">
                    <label>Message</label>
                    <textarea name="email_message" rows="4" required>Dear customer,

Please find attached your acknowledgment receipt. For any queries, please don't hesitate to contact us.

Best regards,
Stafffy Inc</textarea>
                </div>
                
                <div style="margin-top: 15px; display: flex; flex-direction: column; gap: 10px;">
                    <label><input type="checkbox" name="admin_signature" checked> Include authorized signature</label>
                    <label><input type="checkbox" name="request_signature" checked> Request customer to sign</label>
                </div>
                
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary">Send Email</button>
                </div>
            </form>
        </div>
    </div>

    {{-- CREATE/EDIT MODAL (Modal 3) --}}
    <div class="modal-overlay" id="receiptModal" style="{{ ($showModal || $editId) ? 'display: flex;' : 'display: none;' }}">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h2>{{ $editId ? 'Edit Receipt' : 'Create Receipt' }}</h2>
                <button class="modal-close-btn" onclick="closeModal()">×</button>
            </div>
            
            <form method="POST" action="{{ $editId ? route('ack.receipt.update', $editId) : route('ack.receipt.store') }}">
                @csrf
                @if($editId)
                    @method('PUT')
                @endif
                
                <div class="receipt-modal-layout">
                    <div class="receipt-form-pane">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="customer_name" id="customerName" required value="{{ $receiptData ? $receiptData->Customer_Name : '' }}">
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="customer_email" id="customerEmail" required value="{{ $receiptData ? $receiptData->Customer_Email : '' }}">
                            </div>
                            <div class="form-group">
                                <label>Contact Number</label>
                                <input type="text" name="contact_number" id="contactNumber" required value="{{ $receiptData ? ($receiptData->contact_number ?? '') : '' }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" id="address" required>{{ $receiptData ? $receiptData->Address : '' }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Purpose</label>
                            <select name="purpose_type" id="purposeType" onchange="togglePurposeFields()">
                                <option value="payment" {{ $receiptData && $receiptData->purpose_type == 'payment' ? 'selected' : '' }}>Payment For</option>
                                <option value="items" {{ $receiptData && $receiptData->purpose_type == 'items' ? 'selected' : '' }}>Received Items</option>
                            </select>
                        </div>

                        <div id="paymentFields" class="form-group" style="display: {{ (!$receiptData || $receiptData->purpose_type == 'payment') ? 'block' : 'none' }};">
                            <label>Payment Description</label>
                            <textarea name="payment_for" id="paymentFor">{{ $receiptData ? ($receiptData->Payment_For ?? '') : '' }}</textarea>
                        </div>

                        <div id="itemsFields" class="form-group" style="display: {{ ($receiptData && $receiptData->purpose_type == 'items') ? 'block' : 'none' }};">
                            <label>Items Received</label>
                            <textarea name="items_received" id="itemsReceived">{{ $receiptData ? ($receiptData->items_received ?? '') : '' }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="location" id="location" value="{{ $receiptData ? ($receiptData->location ?? '') : '' }}">
                        </div>

                        <div class="form-group">
                            <label>Amount (₱)</label>
                            <div class="input-group">
                                <input type="number" name="amount" id="amount" step="0.01" required value="{{ $receiptData ? $receiptData->Amount : '' }}">
                                <select name="payment_status" id="paymentStatus">
                                    <option value="down" {{ $receiptData && $receiptData->payment_status == 'down' ? 'selected' : '' }}>Down Payment</option>
                                    <option value="partial" {{ $receiptData && $receiptData->payment_status == 'partial' ? 'selected' : '' }}>Partial Payment</option>
                                    <option value="full" {{ $receiptData && $receiptData->payment_status == 'full' ? 'selected' : '' }}>Full Payment</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Payment Method</label>
                            <div class="input-group">
                                <select name="payment_method" id="paymentMethod" onchange="updatePreview()">
                                    @foreach($paymentMethods as $method)
                                        <option value="{{ $method->id }}" {{ $receiptData && $receiptData->Payment_Method_Id == $method->id ? 'selected' : '' }}>
                                            {{ $method->method_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn input-group-btn" onclick="window.location.href='{{ route('billing.ack.receipt', ['add_payment_method' => 1]) }}'">+</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Reference Number (Check/Transaction ID)</label>
                            <input type="text" name="reference_number" id="referenceNumber" value="{{ $receiptData ? ($receiptData->Reference_Number ?? '') : '' }}">
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" id="notes">{{ $receiptData ? ($receiptData->Notes ?? '') : '' }}</textarea>
                        </div>
                    </div>
                    
                    <div class="receipt-preview-pane">
                            <div class="receipt-logo">
                                <img src="{{ asset('assets/images/Stafify-Logo.png') }}" alt="Staffify Logo">
                            </div>
                            <h2>Acknowledgment Receipt</h2>
                        <div class="receipt-header">
                            <div class="company-info">
                                <strong>Stafffy Inc</strong><br>
                                54 Irving Street<br>New Asinan<br>Olongapo City, 2200 ZAMBALES<br>PHILIPPINES<br>staffify@gmail.com
                            </div>
                            <div class="receipt-meta">
                                <p class="receipt-id">Receipt #: <span id="previewReceiptId">{{ $receiptData ? $receiptData->Receipt_Id : 'New' }}</span></p>
                                <p><strong>Date:</strong> {{ $currentDate }}</p>
                            </div>
                        </div>
                        <hr>
                        
                        <div class="receipt-statement">
                            <p>I, <span id="previewCustomerName" class="highlight">{{ $receiptData ? $receiptData->Customer_Name : 'Full Name' }}</span>, 
                            <span id="purposeStatementPreview">
                                received from Stafffy Inc the amount of ₱<span id="previewAmount" class="highlight">{{ $receiptData ? number_format($receiptData->Amount, 2) : '0.00' }}</span> 
                                <span id="paymentStatusPreview">{{ $receiptData && $receiptData->payment_status == 'partial' ? '(Partial Payment)' : ($receiptData && $receiptData->payment_status == 'down' ? '(Down Payment)' : '(Full Payment)') }}</span> 
                                for <span id="previewPurpose" class="highlight">{{ $receiptData ? ($receiptData->Payment_For ?? 'payment purpose') : 'payment purpose' }}</span>.
                            </span>
                            <span id="itemsStatementPreview" style="display: {{ ($receiptData && $receiptData->purpose_type == 'items') ? 'inline' : 'none' }};">
                                received from Stafffy Inc the following items: <span id="previewItems" class="highlight">{{ $receiptData ? ($receiptData->items_received ?? 'items') : 'items' }}</span>.
                            </span>
                            </p>
                            <p>Done, this {{ date('jS \d\a\y \of F Y') }}, at <span id="previewLocation" class="highlight">{{ $receiptData ? ($receiptData->location ?? 'Olongapo City') : 'Olongapo City' }}</span>.</p>
                        </div>

                        <div class="receipt-details-grid">
                            <div class="receipt-details-box">
                                <strong>Contact Information:</strong><br>
                                Email: <span id="previewCustomerEmail">{{ $receiptData ? $receiptData->Customer_Email : '-' }}</span><br>
                                Phone: <span id="previewCustomerPhone">{{ $receiptData ? ($receiptData->contact_number ?? '-') : '-' }}</span><br>
                                Address: <span id="previewCustomerAddress">{{ $receiptData ? $receiptData->Address : '-' }}</span>
                            </div>
                            <div class="receipt-details-box">
                                <p><strong>Payment Method:</strong> <span id="previewPaymentMethod">{{ $receiptData && $receiptData->paymentMethod ? $receiptData->paymentMethod->method_name : '-' }}</span></p>
                                <p><strong>Reference Number:</strong> <span id="previewReferenceNumber">{{ $receiptData ? ($receiptData->Reference_Number ?? '-') : '-' }}</span></p>
                            </div>
                        </div>

                        <div class="receipt-notes">
                            <p><strong>Notes:</strong></p>
                            <p id="previewNotes">{{ $receiptData ? nl2br(e($receiptData->Notes ?? '')) : '' }}</p>
                        </div>

                        <div class="receipt-signatures">
                            <div class="signature-box">Customer Signature</div>
                            <div class="signature-box">Authorized Signature</div>
                        </div>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="generatePDF()">Print Receipt</button>
                    <button type="submit" class="btn btn-primary">{{ $editId ? 'Update Receipt' : 'Save Receipt' }}</button>
                </div>
            </form>
        </div>
    </div>


    {{-- PAGE HEADER (Visible on List View) --}}
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
            <div class="stat-icon-wrapper icon-receipts">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="summary-label">Total Receipts</div>
            <div class="summary-value text-primary-custom">{{ $receipts->count() ?? 0 }}</div>
        </div>
        <div class="summary-card-item">
            <div class="stat-icon-wrapper icon-pending">
                 <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="summary-label">Pending Amount</div>
            <div class="summary-value text-warning">₱{{ number_format($pendingAmount ?? 0, 2) }}</div>
        </div>
        <div class="summary-card-item">
            <div class="stat-icon-wrapper icon-received">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="summary-label">Total Received</div>
             <div class="summary-value text-success">₱{{ number_format($receivedAmount ?? 0, 2) }}</div>
        </div>
    </div>
    @endif
    @if($emailSent)
        <div class="success-message">Receipt has been sent successfully via email!</div>
    @endif

    {{-- MAIN CONTENT (View or List) --}}
    @if($viewId && $receiptData)
        {{-- VIEW RECEIPT SECTION --}}
        <div class="invoice-view">
            <div class="invoice-view-actions">
                <button onclick="window.location.href='{{ route('billing.ack.receipt') }}'" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                    Back to List
                </button>
                <button onclick="window.location.href='{{ route('billing.ack.receipt', ['edit' => $viewId]) }}'" class="btn btn-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                    Edit
                </button>
                <button type="button" class="btn btn-secondary" onclick="generatePDF()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0c.317.087.65.162.98.232m-9.98 0l-.04.022c-.512.227-.815.76-.815 1.319v2.1a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-2.1c0-.56-.303-1.092-.815-1.319l-.04-.022m-10.76-9.281c.24.03.48.062.72.096m-.72-.096c.317.087.65.162.98.232m-9.98 0l-.04.022c-.512.227-.815.76-.815 1.319v2.1a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-2.1c0-.56-.303-1.092-.815-1.319l-.04-.022m-10.76-9.281c.24.03.48.062.72.096m-.72-.096L9 5.25l3 3m0 0l3-3m-3 3v5.25m-6 3h12" /></svg>
                    Print
                </button>
                <button onclick="openEmailModal({{ $viewId }}, '{{ addslashes($receiptData->Customer_Email) }}')" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                    Email
                </button>
                <form method="POST" action="{{ route('ack.receipt.destroy', $viewId) }}" style="display: inline;" onsubmit="return confirm('Delete this receipt?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12.502 0c-.411.011-.82.028-1.229.05c-3.17.198-6.096.657-8.634 1.183L3.15 5.79m14.456 0L13.8 3.51a2.25 2.25 0 0 0-3.182 0L6.844 5.79m11.136 0c-0.291-.01-0.582-.019-0.873-.028m-10.01 0c-.29.009-.581.018-.872.028" /></svg>
                        Delete
                    </button>
                </form>
            </div>
            
            <div class="invoice-document">
                <div class="receipt-header">
                    <div class="company-info">
                        <strong>Stafffy Inc</strong><br>
                        54 Irving Street<br>New Asinan<br>Olongapo City, 2200 ZAMBALES<br>PHILIPPINES<br>staffify@gmail.com
                    </div>
                    <div class="receipt-meta">
                        <p class="receipt-id">Receipt #: {{ $receiptData->Receipt_Id }}</p>
                        <p><strong>Date:</strong> {{ $receiptData->created_at ? $receiptData->created_at->format('F j, Y') : date('F j, Y') }}</p>
                        @if($receiptData->is_signed)
                            <p class="receipt-signed-badge">✓ Signed by customer on {{ $receiptData->signature_date ? $receiptData->signature_date->format('F j, Y') : '' }}</p>
                        @endif
                    </div>
                </div>
                <hr>
                
                <div class="receipt-statement">
                    <p>I, <strong class="highlight">{{ $receiptData->Customer_Name }}</strong>, 
                    @if(!isset($receiptData->purpose_type) || $receiptData->purpose_type == 'payment')
                        received from Stafffy Inc the amount of <strong class="highlight">₱{{ number_format($receiptData->Amount, 2) }}</strong>
                        @if(isset($receiptData->payment_status) && $receiptData->payment_status == 'partial')
                            (Partial Payment)
                        @elseif(isset($receiptData->payment_status) && $receiptData->payment_status == 'down')
                            (Down Payment)
                        @else
                            (Full Payment)
                        @endif
                        for <strong class="highlight">{{ $receiptData->Payment_For ?? '' }}</strong>.
                    @else
                        received from Stafffy Inc the following items: <strong class="highlight">{{ $receiptData->items_received ?? '' }}</strong>.
                    @endif
                    </p>
                    <p>Done, this {{ $receiptData->created_at ? $receiptData->created_at->format('jS \d\a\y \of F Y') : date('jS \d\a\y \of F Y') }}, at <strong class="highlight">{{ $receiptData->location ?? 'Olongapo City' }}</strong>.</p>
                </div>

                <div class="receipt-details-grid">
                    <div class="receipt-details-box">
                        <strong>Contact Information:</strong><br>
                        Email: {{ $receiptData->Customer_Email }}<br>
                        Phone: {{ $receiptData->contact_number ?? '-' }}<br>
                        Address: {{ $receiptData->Address }}
                    </div>
                    <div class="receipt-details-box">
                        <p><strong>Payment Method:</strong> {{ $receiptData->paymentMethod ? $receiptData->paymentMethod->method_name : 'N/A' }}</p>
                        <p><strong>Reference Number:</strong> {{ $receiptData->Reference_Number ?? '-' }}</p>
                    </div>
                </div>

                <div class="receipt-notes">
                    <p><strong>Notes:</strong></p>
                    <p>{{ $receiptData->Notes ? nl2br(e($receiptData->Notes)) : '' }}</p>
                </div>

                <div class="receipt-signatures">
                    <div class="signature-box">
                        @if($receiptData->is_signed)
                            <div style="margin-bottom: 10px;">
                                @if(!empty($receiptData->signature_image))
                                    <img src="{{ $receiptData->signature_image }}" alt="Customer Signature" style="max-width: 100%; height: 60px;">
                                @else
                                    <span style="color: var(--success-color); font-weight: bold;">✓ Electronically Signed</span>
                                @endif
                            </div>
                            <div style="font-size: 12px; color: #666;">
                                @if($receiptData->signature_date)
                                    Signed on {{ $receiptData->signature_date->format('F j, Y g:i A') }}
                                @endif
                            </div>
                        @endif
                        Customer Signature
                    </div>
                    <div class="signature-box">Authorized Signature</div>
                </div>
            </div>
        </div>
    @else
        {{-- RECEIPT LIST SECTION --}}
        <div class="content-card">
            @if($receipts->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="receipts-table">
                        <thead>
                            <tr>
                                <th>Receipt #</th>
                                <th>Full Name</th>
                                <th>Contact</th>
                                <th>Purpose</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Location</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($receipts as $receipt)
                                @php
                                    $purpose = '';
                                    if (isset($receipt->purpose_type) && $receipt->purpose_type == 'items') {
                                        $purpose = 'Items: ' . \Illuminate\Support\Str::limit($receipt->items_received ?? '', 30);
                                    } else {
                                        $purpose = 'Payment: ' . \Illuminate\Support\Str::limit($receipt->Payment_For ?? '', 30);
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $receipt->Receipt_Id }}</td>
                                    <td>{{ $receipt->Customer_Name }}</td>
                                    <td>{{ $receipt->contact_number ?? 'N/A' }}</td>
                                    <td>{{ $purpose }}</td>
                                    <td>₱{{ number_format($receipt->Amount, 2) }}</td>
                                    <td>
                                        @if(isset($receipt->payment_status))
                                            @if($receipt->payment_status == 'partial') Partial
                                            @elseif($receipt->payment_status == 'down') Down
                                            @else Full
                                            @endif
                                        @else -
                                        @endif
                                    </td>
                                    <td>{{ $receipt->location ?? 'N/A' }}</td>
                                    <td>{{ $receipt->created_at ? $receipt->created_at->format('M j, Y') : '-' }}</td>
                                    
                                    {{-- 1. THIS IS THE CORRECTED STATUS COLUMN --}}
                                    <td>
                                        @if($receipt->is_signed)
                                            <span class="status-badge status-signed">Signed</span>
                                        @else
                                            <span class="status-badge status-pending">Pending</span>
                                        @endif
                                    </td>
                                    
                                    {{-- 2. THIS IS THE CORRECTED ACTIONS COLUMN (NO "Access denied" TEXT) --}}
                                    <td>
                                        <div class="action-buttons btn-group">
                                            {{-- VIEW BUTTON --}}
                                            <button class="btn btn-icon" title="View" onclick="window.location.href='{{ route('billing.ack.receipt', ['view' => $receipt->Receipt_Id]) }}'">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.634l3.061-5.636a.5.5 0 0 1 .44-.223h13.911a.5.5 0 0 1 .44.223l3.061 5.636a1.012 1.012 0 0 1 0 .634l-3.061 5.636a.5.5 0 0 1-.44.223H5.537a.5.5 0 0 1-.44-.223L2.036 12.322Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                            </button>
                                            
                                            {{-- EDIT BUTTON --}}
                                            <button class="btn btn-icon" title="Edit" onclick="window.location.href='{{ route('billing.ack.receipt', ['edit' => $receipt->Receipt_Id]) }}'">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                            </button>
                                            
                                            {{-- EMAIL BUTTON --}}
                                            <button class="btn btn-icon" title="Email" onclick="openEmailModal({{ $receipt->Receipt_Id }}, '{{ addslashes($receipt->Customer_Email) }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                                            </button>
                                            
                                            {{-- DELETE BUTTON --}}
                                            <form method="POST" action="{{ route('ack.receipt.destroy', $receipt->Receipt_Id) }}" style="display: inline;" onsubmit="return confirm('Delete this receipt?')">
                                                @csrf
                                                @method('DELETE')
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
                <p>No receipts found. Create your first receipt.</p>
            @endif
        </div>
    @endif
</div>
@endsection