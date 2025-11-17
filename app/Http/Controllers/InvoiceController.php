<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\TaxRate;
use App\Models\DiscountRate;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices or a specific invoice
     */
    /**
     * Display a listing of invoices or a specific invoice
     */
    public function index(Request $request)
    {
        $viewId = $request->get('view');
        $editId = $request->get('edit');
        $showModal = $request->get('show');
        $showTaxModal = $request->get('add_tax');
        $showDiscountModal = $request->get('add_discount');

        // Get business tax settings
        $businessSettings = BusinessSetting::first();
        $isVat = true;
        $taxType = 'VAT (12%)';

        if ($businessSettings && $businessSettings->tax_type) {
            $taxType = $businessSettings->tax_type;
            $isVat = (strpos($businessSettings->tax_type, 'VAT') !== false && 
                      strpos($businessSettings->tax_type, 'Non-VAT') === false);
        }

        // Fetch tax rates and discount rates
        $taxRates = TaxRate::orderBy('tax_name')->get();
        $discountRates = DiscountRate::orderBy('discount_name')->get();

        // Initialize defaults if tables are empty
        $this->initializeDefaults($taxRates, $discountRates, $businessSettings);

        // Refresh after initialization
        $taxRates = TaxRate::orderBy('tax_name')->get();
        $discountRates = DiscountRate::orderBy('discount_name')->get();

        $invoiceData = null;
        $invoiceIsVat = $isVat;
        $invoiceTaxType = $taxType;

        if ($viewId || $editId) {
            $id = $viewId ?: $editId;
            $invoiceData = Invoice::find($id);
            
            if ($invoiceData) {
                if (isset($invoiceData->invoice_mode) && !empty($invoiceData->invoice_mode)) {
                    $invoiceIsVat = ($invoiceData->invoice_mode === 'VAT');
                }
                if (isset($invoiceData->tax_type_at_creation) && !empty($invoiceData->tax_type_at_creation)) {
                    $invoiceTaxType = $invoiceData->tax_type_at_creation;
                }
            }
        }

        $invoices = Invoice::orderBy('Invoice_Id', 'DESC')->get();
        
        // ============================================
        //     NEW: ADDED SUMMARY LOGIC
        // ============================================
        
        $totalInvoiced = 0;
        $totalVat = 0;
        $totalNonVat = 0;

        foreach ($invoices as $invoice) {
            // Determine if this invoice was VAT or NON-VAT at creation
            $listInvoiceIsVat = true;
            if (isset($invoice->invoice_mode) && !empty($invoice->invoice_mode)) {
                $listInvoiceIsVat = ($invoice->invoice_mode === 'VAT');
            } elseif (isset($invoice->tax_type_at_creation) && !empty($invoice->tax_type_at_creation)) {
                $listInvoiceIsVat = (strpos($invoice->tax_type_at_creation, 'Non-VAT') === false);
            }
            
            // Use your existing helper function to get the totals
            $calculations = $this->calculateInvoiceTotals(
                $invoice->Price,
                $invoice->Quantity,
                $invoice->Discount,
                $invoice->Tax,
                $listInvoiceIsVat
            );

            $totalInvoiced += $calculations['total'];
            
            if ($listInvoiceIsVat) {
                $totalVat += $calculations['total'];
            } else {
                $totalNonVat += $calculations['total'];
            }
        }
        
        // ============================================
        //     END OF NEW LOGIC
        // ============================================


        return view('pages.billing-e-invoice', [
            'viewId' => $viewId,
            'editId' => $editId,
            'showModal' => $showModal || $editId,
            'showTaxModal' => $showTaxModal,
            'showDiscountModal' => $showDiscountModal,
            'invoiceData' => $invoiceData,
            'invoices' => $invoices,
            'taxRates' => $taxRates,
            'discountRates' => $discountRates,
            'isVat' => $isVat,
            'taxType' => $taxType,
            'invoiceIsVat' => $invoiceIsVat,
            'invoiceTaxType' => $invoiceTaxType,
            'emailSent' => $request->get('email_sent') == 1,

            // --- PASS NEW VARIABLES TO THE VIEW ---
            'totalInvoiced' => $totalInvoiced,
            'totalVat' => $totalVat,
            'totalNonVat' => $totalNonVat,
        ]);
    }

    /**
     * Store a newly created invoice
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'billing_address' => 'nullable|string',
            'item_name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'terms' => 'nullable|string',
            'tax_option' => 'required|integer',
            'discount_option' => 'required|integer',
        ]);

        // Get business tax settings
        $businessSettings = BusinessSetting::first();
        $isVat = true;
        $taxType = 'VAT (12%)';

        if ($businessSettings && $businessSettings->tax_type) {
            $taxType = $businessSettings->tax_type;
            $isVat = (strpos($businessSettings->tax_type, 'VAT') !== false && 
                     strpos($businessSettings->tax_type, 'Non-VAT') === false);
        }

        // Get tax and discount information
        $taxRate = TaxRate::find($validated['tax_option']);
        $discountRate = DiscountRate::find($validated['discount_option']);

        $tax = $taxRate ? $taxRate->tax_rate : 0;
        $discount = $discountRate ? $discountRate->discount_value : '0';
        $invoiceMode = $isVat ? 'VAT' : 'NON-VAT';

        $invoice = Invoice::create([
            'Customer_Name' => $validated['customer_name'],
            'Customer_Email' => $validated['customer_email'],
            'Billing_Address' => $validated['billing_address'] ?? '',
            'Item_Name' => $validated['item_name'],
            'Price' => $validated['price'],
            'Quantity' => $validated['quantity'],
            'Discount' => $discount,
            'Tax' => $tax,
            'Terms' => $validated['terms'] ?? '',
            'tax_id' => $validated['tax_option'],
            'discount_id' => $validated['discount_option'],
            'invoice_mode' => $invoiceMode,
            'tax_type_at_creation' => $taxType,
        ]);

        return redirect()->route('billing.invoice', ['view' => $invoice->Invoice_Id]);
    }

    /**
     * Update an existing invoice
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'billing_address' => 'nullable|string',
            'item_name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'terms' => 'nullable|string',
            'tax_option' => 'required|integer',
            'discount_option' => 'required|integer',
        ]);

        // Get business tax settings
        $businessSettings = BusinessSetting::first();
        $isVat = true;
        $taxType = 'VAT (12%)';

        if ($businessSettings && $businessSettings->tax_type) {
            $taxType = $businessSettings->tax_type;
            $isVat = (strpos($businessSettings->tax_type, 'VAT') !== false && 
                     strpos($businessSettings->tax_type, 'Non-VAT') === false);
        }

        // Get tax and discount information
        $taxRate = TaxRate::find($validated['tax_option']);
        $discountRate = DiscountRate::find($validated['discount_option']);

        $tax = $taxRate ? $taxRate->tax_rate : 0;
        $discount = $discountRate ? $discountRate->discount_value : '0';
        $invoiceMode = $isVat ? 'VAT' : 'NON-VAT';

        $invoice = Invoice::findOrFail($id);
        $invoice->update([
            'Customer_Name' => $validated['customer_name'],
            'Customer_Email' => $validated['customer_email'],
            'Billing_Address' => $validated['billing_address'] ?? '',
            'Item_Name' => $validated['item_name'],
            'Price' => $validated['price'],
            'Quantity' => $validated['quantity'],
            'Discount' => $discount,
            'Tax' => $tax,
            'Terms' => $validated['terms'] ?? '',
            'tax_id' => $validated['tax_option'],
            'discount_id' => $validated['discount_option'],
            'invoice_mode' => $invoiceMode,
            'tax_type_at_creation' => $taxType,
        ]);

        return redirect()->route('billing.invoice', ['view' => $invoice->Invoice_Id]);
    }

    /**
     * Delete an invoice
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('billing.invoice');
    }

    /**
     * Store a new tax rate
     */
    public function storeTaxRate(Request $request)
    {
        $validated = $request->validate([
            'tax_name' => 'required|string|max:100',
            'tax_rate' => 'required|numeric|min:0|max:100',
        ]);

        TaxRate::create($validated);

        return redirect()->route('billing.invoice');
    }

    /**
     * Store a new discount rate
     */
    public function storeDiscountRate(Request $request)
    {
        $validated = $request->validate([
            'discount_name' => 'required|string|max:100',
            'discount_value' => 'required|string|max:50',
        ]);

        DiscountRate::create($validated);

        return redirect()->route('billing.invoice');
    }

    /**
     * Send invoice via email
     */
    public function sendEmail(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|integer|exists:invoice,Invoice_Id',
            'email_recipient' => 'required|email',
            'email_subject' => 'required|string|max:255',
            'email_message' => 'required|string',
        ]);

        $invoice = Invoice::findOrFail($validated['invoice_id']);

        // Get business tax settings for calculation
        $businessSettings = BusinessSetting::first();
        $isVat = true;
        if ($businessSettings && $businessSettings->tax_type) {
            $isVat = (strpos($businessSettings->tax_type, 'VAT') !== false && 
                     strpos($businessSettings->tax_type, 'Non-VAT') === false);
        }

        // Use invoice's stored mode if available
        if (isset($invoice->invoice_mode) && !empty($invoice->invoice_mode)) {
            $isVat = ($invoice->invoice_mode === 'VAT');
        }

        // Calculate totals
        $calculations = $this->calculateInvoiceTotals(
            $invoice->Price,
            $invoice->Quantity,
            $invoice->Discount,
            $invoice->Tax,
            $isVat
        );

        // Generate HTML email body
        $htmlBody = view('emails.invoice', [
            'invoice' => $invoice,
            'calculations' => $calculations,
            'isVat' => $isVat,
        ])->render();

        try {
            // Send email using Laravel Mail
            Mail::send([], [], function ($message) use ($validated, $htmlBody) {
                $message->to($validated['email_recipient'])
                        ->subject($validated['email_subject'])
                        ->from('dimalantajustine8@gmail.com', 'Stafify')
                        ->html($htmlBody);
            });

            return redirect()->route('billing.invoice', [
                'view' => $invoice->Invoice_Id,
                'email_sent' => 1
            ])->with('success', 'Invoice sent successfully!');
        } catch (\Exception $e) {
            return redirect()->route('billing.invoice', [
                'view' => $invoice->Invoice_Id
            ])->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Calculate invoice totals based on mode
     */
    private function calculateInvoiceTotals($price, $quantity, $discount, $taxRate, $isVatMode)
    {
        $subtotal = $price * $quantity;

        // Calculate discount
        $discountAmount = 0;
        if (strpos($discount, '%') !== false) {
            $discountAmount = $subtotal * (floatval($discount) / 100);
        } else {
            $discountAmount = floatval($discount);
        }

        $taxableAmount = $subtotal - $discountAmount;

        if ($isVatMode) {
            // VAT mode: add tax to total
            $tax = $taxableAmount * ($taxRate / 100);
            $total = $taxableAmount + $tax;
        } else {
            // Non-VAT mode: deduct withholding tax (3%)
            $tax = $taxableAmount * 0.03;
            $total = $taxableAmount - $tax;
        }

        return [
            'subtotal' => $subtotal,
            'discount' => $discountAmount,
            'tax' => $tax,
            'total' => $total,
            'taxable_amount' => $taxableAmount
        ];
    }

    /**
     * Initialize default data if tables are empty
     */
    private function initializeDefaults($taxRates, $discountRates, $businessSettings)
    {
        if ($taxRates->isEmpty()) {
            TaxRate::insert([
                ['tax_name' => 'No Tax', 'tax_rate' => 0],
                ['tax_name' => 'Standard VAT', 'tax_rate' => 12],
                ['tax_name' => 'Reduced VAT', 'tax_rate' => 5],
            ]);
        }

        if ($discountRates->isEmpty()) {
            DiscountRate::insert([
                ['discount_name' => 'No Discount', 'discount_value' => '0'],
                ['discount_name' => 'Standard Discount', 'discount_value' => '10%'],
                ['discount_name' => 'Special Offer', 'discount_value' => '100'],
            ]);
        }

        if (!$businessSettings) {
            BusinessSetting::create(['tax_type' => 'VAT (12%)']);
        }
    }
}

