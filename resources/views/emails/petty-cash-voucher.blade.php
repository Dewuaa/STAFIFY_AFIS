<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petty Cash Voucher</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .voucher-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #1F5497;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #1F5497;
            margin: 0;
            font-size: 28px;
        }
        
        .company-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .voucher-info {
            text-align: right;
        }
        
        .voucher-details {
            margin: 30px 0;
        }
        
        .detail-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        
        .detail-label {
            width: 30%;
            font-weight: bold;
            color: #555;
        }
        
        .detail-value {
            width: 70%;
            color: #333;
        }
        
        .amount-highlight {
            font-weight: bold;
            color: #1F5497;
            font-size: 18px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending {
            background-color: #FFC107;
            color: #333;
        }
        
        .status-approved {
            background-color: #4CAF50;
            color: white;
        }
        
        .status-rejected {
            background-color: #F44336;
            color: white;
        }
        
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        
        .signature-box {
            width: 30%;
            text-align: center;
            min-width: 150px;
            margin-bottom: 20px;
        }
        
        .signature-line {
            border-top: 2px solid #333;
            margin-top: 50px;
            padding-top: 10px;
            font-weight: bold;
        }
        
        .notes-section {
            margin: 30px 0;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="voucher-container">
        <div class="header">
            <h1>PETTY CASH VOUCHER</h1>
        </div>
        
        <div class="company-info">
            <div>
                <strong>Stafffy Inc</strong><br>
                54 Irving Street<br>
                New Asinan<br>
                Olongapo City, 2200 ZAMBALES<br>
                PHILIPPINES<br>
                staffify@gmail.com
            </div>
            <div class="voucher-info">
                <p><strong>Voucher #:</strong> {{ $voucher->voucher_number }}</p>
                <p><strong>Date:</strong> {{ $voucher->date_issued ? $voucher->date_issued->format('F j, Y') : date('F j, Y') }}</p>
                <p><strong>Status:</strong> 
                    <span class="status-badge status-{{ $voucher->status }}">
                        {{ ucfirst($voucher->status) }}
                    </span>
                </p>
                @if($voucher->is_signed)
                    <p style="color: #4CAF50; font-weight: bold;">✓ Electronically Signed</p>
                @endif
            </div>
        </div>
        
        <div class="voucher-details">
            <div class="detail-row">
                <div class="detail-label">Payee:</div>
                <div class="detail-value">{{ $voucher->payee_name }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Email:</div>
                <div class="detail-value">{{ $voucher->payee_email }}</div>
            </div>
            
            @if($voucher->contact_number)
            <div class="detail-row">
                <div class="detail-label">Contact:</div>
                <div class="detail-value">{{ $voucher->contact_number }}</div>
            </div>
            @endif
            
            @if($voucher->department)
            <div class="detail-row">
                <div class="detail-label">Department:</div>
                <div class="detail-value">{{ $voucher->department }}</div>
            </div>
            @endif
            
            @if($voucher->position)
            <div class="detail-row">
                <div class="detail-label">Position:</div>
                <div class="detail-value">{{ $voucher->position }}</div>
            </div>
            @endif
            
            <div class="detail-row">
                <div class="detail-label">Purpose:</div>
                <div class="detail-value">{{ $voucher->purpose }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Amount:</div>
                <div class="detail-value amount-highlight">₱{{ number_format($voucher->amount, 2) }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Category:</div>
                <div class="detail-value">{{ $voucher->category ? $voucher->category->category_name : 'N/A' }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Payment Method:</div>
                <div class="detail-value">{{ $voucher->payment_method }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Receipt Attached:</div>
                <div class="detail-value">{{ $voucher->receipt_attached ? 'Yes' : 'No' }}</div>
            </div>
        </div>
        
        @if($voucher->notes)
        <div class="notes-section">
            <h3>Notes:</h3>
            <p>{!! nl2br(e($voucher->notes)) !!}</p>
        </div>
        @endif
        
        <div class="signature-section">
            <div class="signature-box">
                @if($hasAdminSignature)
                    <div style="color: #4CAF50; font-weight: bold; font-style: italic;">Authorized Signature</div>
                    <div>{{ $voucher->approved_by ?: 'Stafffy Inc' }}</div>
                @endif
                <div class="signature-line">Approved By</div>
            </div>
            
            <div class="signature-box">
                <div>{{ $voucher->received_by ?? '' }}</div>
                <div class="signature-line">Received By</div>
            </div>
            
            <div class="signature-box">
                @if($voucher->is_signed)
                    <div style="color: #4CAF50; font-weight: bold;">Electronically Signed</div>
                    @if($voucher->signature_image)
                        <img src="{{ $voucher->signature_image }}" 
                             alt="Digital Signature" 
                             style="max-width: 200px; max-height: 60px; border: 1px solid #ccc; background: white; margin: 5px 0;">
                    @endif
                    <div>{{ $voucher->payee_name }}</div>
                    <div><small>{{ $voucher->signature_date ? $voucher->signature_date->format('F j, Y g:i A') : '' }}</small></div>
                @elseif($requestPayeeSignature && $signatureToken)
                    <p>Please click the link below to sign this voucher electronically.</p>
                @endif
                <div class="signature-line">Payee Signature</div>
            </div>
        </div>
        
        <div class="footer">
            <p>This is an electronically generated voucher from Stafffy Inc.<br>
            For any queries, please contact us at staffify@gmail.com</p>
        </div>
    </div>
</body>
</html>

