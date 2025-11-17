<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankReconciliationController extends Controller
{
    public function index(Request $request)
    {
        $page = max(1, (int) $request->get('page', 1));
        $perPage = 10;
        $selectedBank = $request->get('bank_id') ? (int) $request->get('bank_id') : null;

        // Get active banks
        $activeBanks = DB::table('active_banks_ph')
            ->orderBy('bank_name')
            ->get()
            ->map(function ($bank) {
                return [
                    'id' => $bank->id,
                    'bank_name' => $bank->bank_name
                ];
            })
            ->toArray();

        // Get reconciliation summary
        $summary = $this->getReconciliationSummary($selectedBank);

        // Get transactions with pagination
        $totalTransactions = $this->countTransactions($selectedBank);
        $totalPages = max(1, ceil($totalTransactions / $perPage));
        $transactions = $this->getTransactions($page, $perPage, $selectedBank);

        // Get bank accounts for linking
        $bankAccounts = DB::table('bank_reco')
            ->leftJoin('active_banks_ph', 'bank_reco.bank_id', '=', 'active_banks_ph.id')
            ->select('bank_reco.*', 'active_banks_ph.bank_name')
            ->orderBy('active_banks_ph.bank_name')
            ->get()
            ->map(function ($account) {
                return (array) $account;
            })
            ->toArray();

        // Handle bank_id mapping - need to find bank_account_id from bank_reco based on bank_id
        // The form submits bank_id (from active_banks_ph), but transactions need bank_account_id (from bank_reco)
        // For now, we'll get the first matching bank_reco account for each bank_id

        return view('pages.bank-reconciliation', [
            'summary' => $summary,
            'transactions' => $transactions,
            'totalTransactions' => $totalTransactions,
            'totalPages' => $totalPages,
            'page' => $page,
            'selectedBank' => $selectedBank,
            'activeBanks' => $activeBanks,
            'bankAccounts' => $bankAccounts,
            'flashMessage' => session('flash_message'),
        ]);
    }

    public function deposit(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'bank_id' => 'required|integer',
            'status' => 'required|in:cleared,pending,outstanding',
            'reference' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'deposit_method' => 'nullable|string|max:50',
        ]);

        // Convert bank_id (from active_banks_ph) to bank_account_id (from bank_reco)
        $bankAccount = DB::table('bank_reco')
            ->where('bank_id', $validated['bank_id'])
            ->first();

        if (!$bankAccount) {
            return redirect()->route('bank.reconciliation')
                ->with('flash_message', [
                    'message' => 'Bank account not found',
                    'type' => 'error'
                ]);
        }

        $bankAccountId = $bankAccount->id;

        // Insert transaction
        $transactionId = DB::table('transactions')->insertGetId([
            'transaction_date' => $validated['date'],
            'date' => $validated['date'],
            'reference_number' => $validated['reference'] ?? null,
            'description' => $validated['description'] ?? null,
            'amount' => $validated['amount'],
            'transaction_type' => 'deposit',
            'method' => $validated['deposit_method'] ?? 'Cash',
            'bank_account_id' => $bankAccountId,
            'status' => $validated['status'],
            'created_at' => now(),
        ]);

        // Update account balance
        $this->updateAccountBalance($bankAccountId, $validated['amount'], 'deposit');

        return redirect()->route('bank.reconciliation')
            ->with('flash_message', [
                'message' => 'Deposit added successfully',
                'type' => 'success'
            ]);
    }

    public function withdrawal(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'bank_id' => 'required|integer',
            'status' => 'required|in:cleared,pending,outstanding',
            'reference' => 'nullable|string|max:100',
            'payee' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'withdrawal_method' => 'nullable|string|max:50',
        ]);

        // Convert bank_id (from active_banks_ph) to bank_account_id (from bank_reco)
        $bankAccount = DB::table('bank_reco')
            ->where('bank_id', $validated['bank_id'])
            ->first();

        if (!$bankAccount) {
            return redirect()->route('bank.reconciliation')
                ->with('flash_message', [
                    'message' => 'Bank account not found',
                    'type' => 'error'
                ]);
        }

        $bankAccountId = $bankAccount->id;

        // Insert transaction
        DB::table('transactions')->insert([
            'transaction_date' => $validated['date'],
            'date' => $validated['date'],
            'reference_number' => $validated['reference'] ?? null,
            'payee' => $validated['payee'] ?? null,
            'description' => $validated['description'] ?? null,
            'amount' => $validated['amount'],
            'transaction_type' => 'withdrawal',
            'method' => $validated['withdrawal_method'] ?? 'Check',
            'bank_account_id' => $bankAccountId,
            'status' => $validated['status'],
            'created_at' => now(),
        ]);

        // Update account balance
        $this->updateAccountBalance($bankAccountId, $validated['amount'], 'withdrawal');

        return redirect()->route('bank.reconciliation')
            ->with('flash_message', [
                'message' => 'Withdrawal added successfully',
                'type' => 'success'
            ]);
    }

    public function adjustment(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'bank_id' => 'required|integer',
            'adjustment_type' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        // Convert bank_id (from active_banks_ph) to bank_account_id (from bank_reco)
        $bankAccount = DB::table('bank_reco')
            ->where('bank_id', $validated['bank_id'])
            ->first();

        if (!$bankAccount) {
            return redirect()->route('bank.reconciliation')
                ->with('flash_message', [
                    'message' => 'Bank account not found',
                    'type' => 'error'
                ]);
        }

        $bankAccountId = $bankAccount->id;

        // Insert transaction
        DB::table('transactions')->insert([
            'transaction_date' => $validated['date'],
            'date' => $validated['date'],
            'description' => $validated['description'] ?? null,
            'amount' => $validated['amount'],
            'transaction_type' => 'adjustment',
            'adjustment_type' => $validated['adjustment_type'] ?? 'Service charge',
            'bank_account_id' => $bankAccountId,
            'status' => 'cleared',
            'created_at' => now(),
        ]);

        // Update account balance
        $this->updateAccountBalance($bankAccountId, $validated['amount'], 'adjustment');

        return redirect()->route('bank.reconciliation')
            ->with('flash_message', [
                'message' => 'Balance adjustment added successfully',
                'type' => 'success'
            ]);
    }

    public function delete(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|integer',
        ]);

        // Get transaction info before deleting
        $transaction = DB::table('transactions')
            ->where('id', $validated['transaction_id'])
            ->first();

        if (!$transaction) {
            return redirect()->route('bank.reconciliation')
                ->with('flash_message', [
                    'message' => 'Transaction not found',
                    'type' => 'error'
                ]);
        }

        $amount = $transaction->amount;
        $type = $transaction->transaction_type;
        $accountId = $transaction->bank_account_id;

        // Reverse the balance change
        if ($type === 'deposit') {
            DB::table('bank_reco')
                ->where('id', $accountId)
                ->decrement('current_balance', $amount);
        } elseif ($type === 'withdrawal') {
            DB::table('bank_reco')
                ->where('id', $accountId)
                ->increment('current_balance', $amount);
        } elseif ($type === 'adjustment') {
            DB::table('bank_reco')
                ->where('id', $accountId)
                ->decrement('current_balance', $amount);
        }

        // Delete the transaction
        DB::table('transactions')->where('id', $validated['transaction_id'])->delete();

        return redirect()->route('bank.reconciliation')
            ->with('flash_message', [
                'message' => 'Transaction deleted successfully',
                'type' => 'success'
            ]);
    }

    private function getReconciliationSummary($bankId = null)
    {
        $summary = [
            'deposits' => 0,
            'withdrawals' => 0,
            'balance' => 0
        ];

        $query = DB::table('transactions');

        if ($bankId) {
            // Need to get bank_account_id from bank_id
            $bankAccountIds = DB::table('bank_reco')
                ->where('bank_id', $bankId)
                ->pluck('id')
                ->toArray();

            if (!empty($bankAccountIds)) {
                $query->whereIn('bank_account_id', $bankAccountIds);
            } else {
                return $summary; // No accounts found for this bank
            }
        }

        // Get total deposits
        $deposits = (clone $query)
            ->where('transaction_type', 'deposit')
            ->sum('amount');
        $summary['deposits'] = $deposits ? (float) $deposits : 0;

        // Get total withdrawals
        $withdrawals = (clone $query)
            ->where('transaction_type', 'withdrawal')
            ->sum('amount');
        $summary['withdrawals'] = $withdrawals ? (float) $withdrawals : 0;

        // Get current balance from bank_reco table
        if ($bankId) {
            $balance = DB::table('bank_reco')
                ->where('bank_id', $bankId)
                ->sum('current_balance');
            $summary['balance'] = $balance ? (float) $balance : 0;
        } else {
            $balance = DB::table('bank_reco')->sum('current_balance');
            $summary['balance'] = $balance ? (float) $balance : 0;
        }

        return $summary;
    }

    private function getTransactions($page = 1, $perPage = 10, $bankId = null)
    {
        $offset = ($page - 1) * $perPage;

        $query = DB::table('transactions as t')
            ->join('bank_reco as br', 't.bank_account_id', '=', 'br.id')
            ->leftJoin('active_banks_ph as abp', 'br.bank_id', '=', 'abp.id')
            ->select(
                't.*',
                'br.account_name',
                'br.account_type',
                'abp.bank_name'
            );

        if ($bankId) {
            $bankAccountIds = DB::table('bank_reco')
                ->where('bank_id', $bankId)
                ->pluck('id')
                ->toArray();

            if (!empty($bankAccountIds)) {
                $query->whereIn('t.bank_account_id', $bankAccountIds);
            } else {
                return [];
            }
        }

        $transactions = $query
            ->orderBy('t.transaction_date', 'desc')
            ->orderBy('t.id', 'desc')
            ->offset($offset)
            ->limit($perPage)
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'transaction_date' => $transaction->transaction_date,
                    'reference_number' => $transaction->reference_number,
                    'payee' => $transaction->payee,
                    'description' => $transaction->description,
                    'amount' => $transaction->amount,
                    'transaction_type' => $transaction->transaction_type,
                    'status' => $transaction->status,
                    'account_name' => $transaction->account_name,
                    'bank_name' => $transaction->bank_name,
                ];
            })
            ->toArray();

        return $transactions;
    }

    private function countTransactions($bankId = null)
    {
        $query = DB::table('transactions');

        if ($bankId) {
            $bankAccountIds = DB::table('bank_reco')
                ->where('bank_id', $bankId)
                ->pluck('id')
                ->toArray();

            if (!empty($bankAccountIds)) {
                $query->whereIn('bank_account_id', $bankAccountIds);
            } else {
                return 0;
            }
        }

        return $query->count();
    }

    private function updateAccountBalance($accountId, $amount, $transactionType)
    {
        if ($transactionType === 'deposit') {
            DB::table('bank_reco')
                ->where('id', $accountId)
                ->increment('current_balance', $amount);
        } elseif ($transactionType === 'withdrawal') {
            DB::table('bank_reco')
                ->where('id', $accountId)
                ->decrement('current_balance', $amount);
        } elseif ($transactionType === 'adjustment') {
            // Adjustments can be positive or negative, so we add the amount (which could be negative)
            DB::table('bank_reco')
                ->where('id', $accountId)
                ->increment('current_balance', $amount);
        }

        // Update the updated_at timestamp
        DB::table('bank_reco')
            ->where('id', $accountId)
            ->update(['updated_at' => now()]);
    }
}

