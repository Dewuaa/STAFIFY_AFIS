<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->Invoice_Id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .invoice-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .company-details, .invoice-details {
            width: 48%;
        }
        .invoice-title {
            font-size: 24px;
            color: #1F5497;
            margin-bottom: 20px;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .client-details, .payment-details {
            width: 48%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
        }
        .totals {
            margin-top: 20px;
            text-align: right;
        }
        .totals p {
            margin: 5px 0;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
        }
        .terms {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-title">INVOICE</div>
        
        <div class="invoice-header">
            <div class="company-details">
                <strong>Stafffy Inc</strong><br>
                54 Irving Street<br>
                New Asinan<br>
                Olongapo City, 2200 ZAMBALES<br>
                PHILIPPINES<br>
                staffify@gmail.com
            </div>
            <div class="invoice-details">
                <p><strong>Invoice #:</strong> {{ $invoice->Invoice_Id }}</p>
                <p><strong>Date:</strong> {{ $invoice->created_at ? $invoice->created_at->format('F j, Y') : date('F j, Y') }}</p>
                <p><strong>Due Date:</strong> {{ $invoice->created_at ? $invoice->created_at->copy()->addDays(7)->format('F j, Y') : date('F j, Y', strtotime('+7 days')) }}</p>
            </div>
        </div>
        
        <hr>
        
        <div class="invoice-info">
            <div class="client-details">
                <strong>Bill To:</strong><br>
                {{ $invoice->Customer_Name }}<br>
                Email: {{ $invoice->Customer_Email }}<br>
                Address: {{ $invoice->Billing_Address }}
            </div>
            <div class="payment-details">
                <strong>Payment Details:</strong><br>
                Status: Pending<br>
                Due: Upon receipt<br>
                Method: Bank Transfer
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $invoice->Item_Name }}</td>
                    <td>₱{{ number_format($invoice->Price, 2) }}</td>
                    <td>{{ $invoice->Quantity }}</td>
                    <td>₱{{ number_format($calculations['subtotal'], 2) }}</td>
                </tr>
            </tbody>
        </table>
        
        <div class="totals">
            @if($isVat)
                <p>Subtotal: ₱{{ number_format($calculations['subtotal'], 2) }}</p>
                <p>Discount: ₱{{ number_format($calculations['discount'], 2) }}</p>
                <p>Tax ({{ $invoice->Tax }}%): ₱{{ number_format($calculations['tax'], 2) }}</p>
            @else
                <p>Subtotal: ₱{{ number_format($calculations['subtotal'], 2) }}</p>
                <p>Discount: ₱{{ number_format($calculations['discount'], 2) }}</p>
                <p>Withholding Tax (3%): ₱{{ number_format($calculations['tax'], 2) }}</p>
            @endif
            <p class="total">Total: ₱{{ number_format($calculations['total'], 2) }}</p>
        </div>
        
        @if(!empty($invoice->Terms))
        <div class="terms">
            <h4>Terms & Conditions</h4>
            <p>{!! nl2br(e($invoice->Terms)) !!}</p>
        </div>
        @endif
        
        <p style="text-align: center; margin-top: 40px;">
            Thank you for your business!
        </p>
    </div>
</body>
</html>

