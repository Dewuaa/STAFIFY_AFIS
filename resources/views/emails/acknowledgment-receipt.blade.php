<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acknowledgment Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .receipt { padding: 20px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; flex-wrap: wrap; }
        .company-info { margin-bottom: 20px; }
        .receipt-statement { margin: 25px 0; padding: 15px; background: #f9f9f9; border-radius: 5px; border-left: 4px solid #1F5497; line-height: 1.6; }
        .highlight { font-weight: bold; color: #1F5497; }
        .details { margin-top: 20px; background-color: #f9f9f9; padding: 15px; border-radius: 5px; }
        .signature { margin-top: 40px; display: flex; justify-content: space-between; flex-wrap: wrap; }
        .signature div { width: 45%; text-align: center; margin-bottom: 20px; }
        .signature-line { border-top: 1px solid #000; padding-top: 5px; }
        .authorized-signed { color: #1F5497; font-weight: bold; }
        .signed-img { margin-bottom: 5px; height: 40px; }
        @media (max-width: 600px) {
            .header { flex-direction: column; }
            .signature div { width: 100%; }
        }
    </style>
</head>
<body>
<div class="receipt">
    <h2 style="color:#1F5497;">ACKNOWLEDGMENT RECEIPT</h2>
    <div class="header">
        <div class="company-info">
            <strong>Stafffy Inc</strong><br>
            54 Irving Street<br>New Asinan<br>
            Olongapo City, 2200 ZAMBALES<br>
            PHILIPPINES<br>
            staffify@gmail.com
        </div>
        <div>
            <p><strong>Receipt #:</strong> {{ $receipt->Receipt_Id }}</p>
            <p><strong>Date:</strong> {{ $receipt->created_at ? $receipt->created_at->format('F j, Y') : date('F j, Y') }}</p>
        </div>
    </div>
    <hr>

    <div class="receipt-statement">
        <p>I, <span class="highlight">{{ $receipt->Customer_Name }}</span>,
        @if(!isset($receipt->purpose_type) || $receipt->purpose_type == 'payment')
            received from Stafffy Inc the amount of <span class="highlight">₱{{ number_format($receipt->Amount, 2) }}</span>
            @if(isset($receipt->payment_status) && $receipt->payment_status == 'partial')
                (Partial Payment)
            @elseif(isset($receipt->payment_status) && $receipt->payment_status == 'down')
                (Down Payment)
            @else
                (Full Payment)
            @endif
            for <span class="highlight">{{ $receipt->Payment_For ?? '' }}</span>.
        @else
            received from Stafffy Inc the following items: <span class="highlight">{{ $receipt->items_received ?? '' }}</span>.
        @endif
        </p>
        <p>Done, this {{ $receipt->created_at ? $receipt->created_at->format('jS \d\a\y \of F Y') : date('jS \d\a\y \of F Y') }}, at <span class="highlight">{{ $receipt->location ?? 'Olongapo City' }}</span>.</p>
    </div>

    <div class="contact-info" style="margin-top: 20px;">
        <div>
            <strong>Contact Information:</strong><br>
            Email: {{ $receipt->Customer_Email }}<br>
            Phone: {{ $receipt->contact_number ?? 'N/A' }}<br>
            Address: {{ $receipt->Address }}
        </div>
    </div>

    <div class="details">
        <p><strong>Payment Method:</strong> {{ $receipt->paymentMethod->method_name ?? 'N/A' }}</p>
        <p><strong>Reference Number:</strong> {{ $receipt->Reference_Number ?? '' }}</p>
    </div>

    @if(!empty($receipt->Notes))
    <div style="margin-top: 20px;">
        <p><strong>Notes:</strong></p>
        <p>{!! nl2br(e($receipt->Notes)) !!}</p>
    </div>
    @endif

    @if($requestCustomerSignature && $signatureToken)
    <div style="margin-top: 30px; text-align: center; padding: 15px; background-color: #f8f9fa; border-radius: 8px; border: 1px solid #ddd;">
        <div style="font-size: 18px; font-weight: bold; color: #1F5497; margin-bottom: 15px;">
            Your Signature is Required
        </div>
        <p>Please sign this receipt to acknowledge that you have received the payment or items described above.</p>
    </div>
    @endif

    <div class="signature">
        <div>
            @if($receipt->is_signed)
            <div class="signed-img">
                @if(!empty($receipt->signature_image))
                    <img src="{{ $receipt->signature_image }}" alt="Customer Signature" style="max-width: 100%; height: 60px;">
                @else
                    <span class="authorized-signed">✓ Electronically Signed</span>
                @endif
            </div>
            <p style="font-size: 0.8em; color: #666;">
                Signed on: {{ $receipt->signature_date ? $receipt->signature_date->format('F j, Y, g:i a') : ($receipt->created_at ? $receipt->created_at->format('F j, Y, g:i a') : date('F j, Y, g:i a')) }}
            </p>
            @endif
            <div class="signature-line">Customer Signature</div>
        </div>

        <div>
            @if($hasAdminSignature)
            <div class="signed-img">
                <span class="authorized-signed">✓ Digitally Signed</span>
            </div>
            @endif
            <div class="signature-line">Authorized Signature</div>
        </div>
    </div>

    <div style="margin-top: 30px; font-size: 0.8em; color: #666; text-align: center; border-top: 1px solid #eee; padding-top: 15px;">
        This is an electronically generated receipt. For verification, please contact Stafffy Inc.
    </div>
</div>
</body>
</html>
