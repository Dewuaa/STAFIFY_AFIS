<?php

namespace App\Http\Controllers;

use App\Models\AcknowledgmentReceipt;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AcknowledgmentReceiptController extends Controller
{
    /**
     * Display a listing of receipts or a specific receipt
     */
    public function index(Request $request)
    {
        $viewId = $request->get('view');
        $editId = $request->get('edit');
        $showModal = $request->get('show');
        $showPaymentMethodModal = $request->get('add_payment_method');

        // Initialize default payment methods if none exist
        $this->initializePaymentMethods();

        // Fetch all payment methods
        $paymentMethods = PaymentMethod::orderBy('method_name')->get();

        $receiptData = null;
        if ($viewId || $editId) {
            $id = $viewId ?: $editId;
            $receiptData = AcknowledgmentReceipt::with('paymentMethod')
                ->find($id);
        }

        // Fetch all receipts for listing
        $receipts = AcknowledgmentReceipt::with('paymentMethod')
            ->orderBy('Receipt_Id', 'DESC')
            ->get();

        $currentDate = now()->format('F j, Y');

        // ============================================
        //     FIX: ADDED SUMMARY LOGIC
        // ============================================
        
        // 1. Get total count
        $receiptCount = $receipts->count();

        // 2. Calculate pending amount (is_signed = 0)
        $pendingAmount = $receipts->where('is_signed', 0)->sum('Amount');

        // 3. Calculate received/signed amount (is_signed = 1)
        $receivedAmount = $receipts->where('is_signed', 1)->sum('Amount');

        // ============================================
        //     END OF FIX
        // ============================================

        return view('pages.billing-ack-receipt', [
            'viewId' => $viewId,
            'editId' => $editId,
            'showModal' => $showModal || $editId,
            'showPaymentMethodModal' => $showPaymentMethodModal,
            'receiptData' => $receiptData,
            'receipts' => $receipts,
            'paymentMethods' => $paymentMethods,
            'currentDate' => $currentDate,
            'emailSent' => $request->get('email_sent') == 1,
            
            // --- PASS NEW VARIABLES TO THE VIEW ---
            'receiptCount' => $receiptCount,
            'pendingAmount' => $pendingAmount,
            'receivedAmount' => $receivedAmount,
        ]);
    }

    /**
     * Store a newly created receipt
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'required|string|max:500',
            'purpose_type' => 'required|in:payment,items',
            'payment_for' => 'nullable|string|max:500',
            'items_received' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:full,partial,down',
            'payment_method' => 'required|integer|exists:payment_methods,id',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $receipt = AcknowledgmentReceipt::create([
            'Customer_Name' => $validated['customer_name'],
            'Customer_Email' => $validated['customer_email'],
            'contact_number' => $validated['contact_number'] ?? null,
            'Address' => $validated['address'],
            'purpose_type' => $validated['purpose_type'],
            'Payment_For' => $validated['purpose_type'] == 'payment' ? ($validated['payment_for'] ?? null) : null,
            'items_received' => $validated['purpose_type'] == 'items' ? ($validated['items_received'] ?? null) : null,
            'location' => $validated['location'] ?? null,
            'Amount' => $validated['amount'],
            'payment_status' => $validated['payment_status'],
            'Payment_Method_Id' => $validated['payment_method'],
            'Reference_Number' => $validated['reference_number'] ?? null,
            'Notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('billing.ack.receipt', ['view' => $receipt->Receipt_Id]);
    }

    /**
     * Update an existing receipt
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'required|string|max:500',
            'purpose_type' => 'required|in:payment,items',
            'payment_for' => 'nullable|string|max:500',
            'items_received' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:full,partial,down',
            'payment_method' => 'required|integer|exists:payment_methods,id',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $receipt = AcknowledgmentReceipt::findOrFail($id);
        $receipt->update([
            'Customer_Name' => $validated['customer_name'],
            'Customer_Email' => $validated['customer_email'],
            'contact_number' => $validated['contact_number'] ?? null,
            'Address' => $validated['address'],
            'purpose_type' => $validated['purpose_type'],
            'Payment_For' => $validated['purpose_type'] == 'payment' ? ($validated['payment_for'] ?? null) : null,
            'items_received' => $validated['purpose_type'] == 'items' ? ($validated['items_received'] ?? null) : null,
            'location' => $validated['location'] ?? null,
            'Amount' => $validated['amount'],
            'payment_status' => $validated['payment_status'],
            'Payment_Method_Id' => $validated['payment_method'],
            'Reference_Number' => $validated['reference_number'] ?? null,
            'Notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('billing.ack.receipt', ['view' => $receipt->Receipt_Id]);
    }

    /**
     * Delete a receipt
     */
    public function destroy($id)
    {
        $receipt = AcknowledgmentReceipt::findOrFail($id);
        $receipt->delete();

        return redirect()->route('billing.ack.receipt');
    }

    /**
     * Store a new payment method
     */
    public function storePaymentMethod(Request $request)
    {
        $validated = $request->validate([
            'method_name' => 'required|string|max:100',
        ]);

        PaymentMethod::create($validated);

        return redirect()->route('billing.ack.receipt');
    }

    /**
     * Send receipt via email
     */
    public function sendEmail(Request $request)
    {
        $validated = $request->validate([
            'receipt_id' => 'required|integer|exists:acknowledgment_receipt,Receipt_Id',
            'email_recipient' => 'required|email',
            'email_subject' => 'required|string|max:255',
            'email_message' => 'required|string',
            'admin_signature' => 'nullable|boolean',
            'request_signature' => 'nullable|boolean',
        ]);

        $receipt = AcknowledgmentReceipt::with('paymentMethod')->findOrFail($validated['receipt_id']);

        // Generate signature token if requesting signature
        $signatureToken = null;
        if ($request->has('request_signature') && $request->input('request_signature')) {
            $signatureToken = md5($receipt->Receipt_Id . time() . mt_rand(1000, 9999));
            $receipt->update(['signature_token' => $signatureToken]);
        }

        // Generate HTML email body
        $htmlBody = view('emails.acknowledgment-receipt', [
            'receipt' => $receipt,
            'hasAdminSignature' => $request->has('admin_signature') && $request->input('admin_signature'),
            'requestCustomerSignature' => $request->has('request_signature') && $request->input('request_signature'),
            'signatureToken' => $signatureToken,
        ])->render();

        try {
            // Send email using Laravel Mail
            Mail::send([], [], function ($message) use ($validated, $htmlBody) {
                $message->to($validated['email_recipient'])
                        ->subject($validated['email_subject'])
                        ->from('dimalantajustine8@gmail.com', 'Stafify')
                        ->html($htmlBody);
            });

            return redirect()->route('billing.ack.receipt', [
                'view' => $receipt->Receipt_Id,
                'email_sent' => 1
            ])->with('success', 'Receipt sent successfully!');
        } catch (\Exception $e) {
            return redirect()->route('billing.ack.receipt', [
                'view' => $receipt->Receipt_Id
            ])->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Initialize default payment methods if none exist
     */
    private function initializePaymentMethods()
    {
        if (PaymentMethod::count() == 0) {
            PaymentMethod::insert([
                ['method_name' => 'Cash'],
                ['method_name' => 'Check'],
                ['method_name' => 'Bank Transfer'],
                ['method_name' => 'Credit Card'],
                ['method_name' => 'Debit Card'],
                ['method_name' => 'Mobile Payment'],
            ]);
        }
    }
}
