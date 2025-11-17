<?php

namespace App\Http\Controllers;

use App\Models\PettyCashVoucher;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PettyCashVoucherController extends Controller
{
    /**
     * Display a listing of vouchers or a specific voucher
     */
    /**
     * Display a listing of vouchers or a specific voucher
     */
    public function index(Request $request)
    {
        $viewId = $request->get('view');
        $editId = $request->get('edit');
        $showModal = $request->get('show');
        $showCategoryModal = $request->get('add_category');

        // Initialize default expense categories if none exist
        $this->initializeExpenseCategories();

        // Fetch all expense categories
        $categories = ExpenseCategory::orderBy('category_name')->get();

        $voucherData = null;
        if ($viewId || $editId) {
            $id = $viewId ?: $editId;
            $voucherData = PettyCashVoucher::with('category')
                ->find($id);
        }

        // Fetch all vouchers for listing
        $vouchers = PettyCashVoucher::with('category')
            ->orderBy('voucher_id', 'DESC')
            ->get();

        // ============================================
        //     NEW: ADD THIS SUMMARY LOGIC
        // ============================================
        
        $totalDisbursed = $vouchers->sum('amount');
        $totalPending = $vouchers->where('status', 'pending')->sum('amount');
        $totalApproved = $vouchers->where('status', 'approved')->sum('amount');
        
        // ============================================
        //     END OF NEW LOGIC
        // ============================================

        return view('pages.billing-petty-cash', [
            'viewId' => $viewId,
            'editId' => $editId,
            'showModal' => $showModal || $editId,
            'showCategoryModal' => $showCategoryModal,
            'voucherData' => $voucherData,
            'vouchers' => $vouchers,
            'categories' => $categories,
            'emailSent' => $request->get('email_sent') == 1,
            'statusUpdated' => $request->get('status_updated') == 1,
            
            // --- PASS NEW VARIABLES TO THE VIEW ---
            'totalDisbursed' => $totalDisbursed,
            'totalPending' => $totalPending,
            'totalApproved' => $totalApproved,
        ]);
    }

    /**
     * Get voucher by ID (for AJAX requests)
     */
    public function show($id)
    {
        $voucher = PettyCashVoucher::with('category')->find($id);
        
        if ($voucher) {
            return response()->json(['success' => true, 'voucher' => $voucher]);
        }
        
        return response()->json(['success' => false]);
    }

    /**
     * Store a newly created voucher
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_issued' => 'required|date',
            'payee_name' => 'required|string|max:255',
            'payee_email' => 'required|email|max:255',
            'contact_number' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'purpose' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'category_id' => 'required|integer|exists:expense_categories,id',
            'payment_method' => 'required|string|max:50',
            'approved_by' => 'nullable|string|max:255',
            'received_by' => 'nullable|string|max:255',
            'receipt_attached' => 'nullable|boolean',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $voucherNumber = $this->generateVoucherNumber();

        $voucher = PettyCashVoucher::create([
            'voucher_number' => $voucherNumber,
            'date_issued' => $validated['date_issued'],
            'payee_name' => $validated['payee_name'],
            'payee_email' => $validated['payee_email'],
            'contact_number' => $validated['contact_number'] ?? null,
            'department' => $validated['department'] ?? null,
            'position' => $validated['position'] ?? null,
            'purpose' => $validated['purpose'],
            'amount' => $validated['amount'],
            'category_id' => $validated['category_id'],
            'payment_method' => $validated['payment_method'],
            'approved_by' => $validated['approved_by'] ?? null,
            'received_by' => $validated['received_by'] ?? null,
            'receipt_attached' => $validated['receipt_attached'] ?? false,
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('billing.petty.cash', ['view' => $voucher->voucher_id]);
    }

    /**
     * Update an existing voucher
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'date_issued' => 'required|date',
            'payee_name' => 'required|string|max:255',
            'payee_email' => 'required|email|max:255',
            'contact_number' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'purpose' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'category_id' => 'required|integer|exists:expense_categories,id',
            'payment_method' => 'required|string|max:50',
            'approved_by' => 'nullable|string|max:255',
            'received_by' => 'nullable|string|max:255',
            'receipt_attached' => 'nullable|boolean',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $voucher = PettyCashVoucher::findOrFail($id);
        $voucher->update([
            'date_issued' => $validated['date_issued'],
            'payee_name' => $validated['payee_name'],
            'payee_email' => $validated['payee_email'],
            'contact_number' => $validated['contact_number'] ?? null,
            'department' => $validated['department'] ?? null,
            'position' => $validated['position'] ?? null,
            'purpose' => $validated['purpose'],
            'amount' => $validated['amount'],
            'category_id' => $validated['category_id'],
            'payment_method' => $validated['payment_method'],
            'approved_by' => $validated['approved_by'] ?? null,
            'received_by' => $validated['received_by'] ?? null,
            'receipt_attached' => $validated['receipt_attached'] ?? false,
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('billing.petty.cash', ['view' => $voucher->voucher_id]);
    }

    /**
     * Delete a voucher
     */
    public function destroy($id)
    {
        $voucher = PettyCashVoucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('billing.petty.cash');
    }

    /**
     * Store a new expense category
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:100',
        ]);

        ExpenseCategory::create($validated);

        return redirect()->route('billing.petty.cash');
    }

    /**
     * Update voucher status
     */
    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'voucher_id' => 'required|integer|exists:petty_cash_voucher,voucher_id',
            'new_status' => 'required|in:pending,approved,rejected',
        ]);

        $voucher = PettyCashVoucher::findOrFail($validated['voucher_id']);
        $voucher->update(['status' => $validated['new_status']]);

        return redirect()->route('billing.petty.cash', [
            'view' => $voucher->voucher_id,
            'status_updated' => 1
        ]);
    }

    /**
     * Send voucher via email
     */
    public function sendEmail(Request $request)
    {
        $validated = $request->validate([
            'voucher_id' => 'required|integer|exists:petty_cash_voucher,voucher_id',
            'email_recipient' => 'required|email',
            'email_subject' => 'required|string|max:255',
            'email_message' => 'required|string',
            'admin_signature' => 'nullable|boolean',
            'request_signature' => 'nullable|boolean',
        ]);

        $voucher = PettyCashVoucher::with('category')->findOrFail($validated['voucher_id']);

        // Generate signature token if requesting signature
        $signatureToken = null;
        if ($request->has('request_signature') && $request->input('request_signature')) {
            $signatureToken = md5($voucher->voucher_id . time() . mt_rand(1000, 9999));
            $voucher->update(['signature_token' => $signatureToken]);
        }

        // Generate HTML email body
        $htmlBody = view('emails.petty-cash-voucher', [
            'voucher' => $voucher,
            'hasAdminSignature' => $request->has('admin_signature') && $request->input('admin_signature'),
            'requestPayeeSignature' => $request->has('request_signature') && $request->input('request_signature'),
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

            return redirect()->route('billing.petty.cash', [
                'view' => $voucher->voucher_id,
                'email_sent' => 1
            ])->with('success', 'Voucher sent successfully!');
        } catch (\Exception $e) {
            return redirect()->route('billing.petty.cash', [
                'view' => $voucher->voucher_id
            ])->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Generate a new voucher number based on the current date and a sequential number
     */
    private function generateVoucherNumber()
    {
        $today = date('Ymd');
        
        // Get the latest voucher number that starts with today's date
        $lastVoucher = PettyCashVoucher::where('voucher_number', 'like', $today . '%')
            ->orderBy('voucher_id', 'DESC')
            ->first();
        
        if ($lastVoucher) {
            // Extract the sequential part and increment it
            $lastNumber = $lastVoucher->voucher_number;
            $sequentialPart = intval(substr($lastNumber, 8));
            $newSequentialPart = $sequentialPart + 1;
        } else {
            // No vouchers today yet, start with 1
            $newSequentialPart = 1;
        }
        
        // Format the new voucher number: YYYYMMDD-XXX
        return $today . '-' . str_pad($newSequentialPart, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Initialize default expense categories if none exist
     */
    private function initializeExpenseCategories()
    {
        if (ExpenseCategory::count() == 0) {
            ExpenseCategory::insert([
                ['category_name' => 'Office Supplies'],
                ['category_name' => 'Transportation'],
                ['category_name' => 'Meals and Entertainment'],
                ['category_name' => 'Utilities'],
                ['category_name' => 'Postage and Shipping'],
                ['category_name' => 'Repairs and Maintenance'],
                ['category_name' => 'Miscellaneous'],
            ]);
        }
    }
}

