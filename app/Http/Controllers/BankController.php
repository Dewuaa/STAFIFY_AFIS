<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BankController extends Controller
{
    public function index()
    {
        $banks = DB::table('bank_accounts')->orderBy('bank_name')->get();

        $savingsPhpCount = 0;
        $currentPhpCount = 0;
        $savingsDollarCount = 0;
        $currentDollarCount = 0;

        foreach ($banks as $bank) {
            $accountType = (string) ($bank->account_type ?? '');
            $currency = (string) ($bank->currency ?? '');

            if ($accountType === 'Savings - PHP' || ($accountType === 'Savings' && $currency === 'PHP')) {
                $savingsPhpCount++;
            } elseif ($accountType === 'Current - PHP' || ($accountType === 'Current' && $currency === 'PHP')) {
                $currentPhpCount++;
            } elseif ($accountType === 'Savings - Dollar' || ($accountType === 'Savings' && $currency === 'USD')) {
                $savingsDollarCount++;
            } elseif ($accountType === 'Current - Dollar' || ($accountType === 'Current' && $currency === 'USD')) {
                $currentDollarCount++;
            } elseif ($accountType === 'Dollar' || $accountType === 'Dollar - USD') {
                // Default old dollar accounts to savings dollar
                $savingsDollarCount++;
            }
        }

        return view('pages.bank-enrollment', [
            'banks' => $banks,
            'savingsPhpCount' => $savingsPhpCount,
            'currentPhpCount' => $currentPhpCount,
            'savingsDollarCount' => $savingsDollarCount,
            'currentDollarCount' => $currentDollarCount,
            'message' => session('message'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => ['required', 'string', 'max:255'],
            'account_name' => ['required', 'string', 'max:255'],
            'account_no' => ['required', 'string', 'max:255'],
            'account_type' => ['required', 'string', 'max:255'],
        ]);

        $accountType = $validated['account_type'];
        $currency = Str::contains($accountType, 'Dollar') ? 'USD' : 'PHP';

        DB::table('bank_accounts')->insert([
            'bank_name' => $validated['bank_name'],
            'account_name' => $validated['account_name'],
            'account_no' => $validated['account_no'],
            'account_type' => $accountType,
            'currency' => $currency,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('bank.enrollment')->with('message', 'New bank account added successfully');
    }

    public function show($id)
    {
        $bank = DB::table('bank_accounts')->where('id', $id)->first();
        
        if (!$bank) {
            return redirect()->route('bank.enrollment')->with('message', 'Bank account not found');
        }

        $banks = DB::table('bank_accounts')->orderBy('bank_name')->get();

        $savingsPhpCount = 0;
        $currentPhpCount = 0;
        $savingsDollarCount = 0;
        $currentDollarCount = 0;

        foreach ($banks as $b) {
            $accountType = (string) ($b->account_type ?? '');
            $currency = (string) ($b->currency ?? '');

            if ($accountType === 'Savings - PHP' || ($accountType === 'Savings' && $currency === 'PHP')) {
                $savingsPhpCount++;
            } elseif ($accountType === 'Current - PHP' || ($accountType === 'Current' && $currency === 'PHP')) {
                $currentPhpCount++;
            } elseif ($accountType === 'Savings - Dollar' || ($accountType === 'Savings' && $currency === 'USD')) {
                $savingsDollarCount++;
            } elseif ($accountType === 'Current - Dollar' || ($accountType === 'Current' && $currency === 'USD')) {
                $currentDollarCount++;
            } elseif ($accountType === 'Dollar' || $accountType === 'Dollar - USD') {
                $savingsDollarCount++;
            }
        }

        return view('pages.bank-enrollment', [
            'banks' => $banks,
            'bankData' => $bank,
            'viewId' => $id,
            'savingsPhpCount' => $savingsPhpCount,
            'currentPhpCount' => $currentPhpCount,
            'savingsDollarCount' => $savingsDollarCount,
            'currentDollarCount' => $currentDollarCount,
            'message' => session('message'),
        ]);
    }

    public function edit($id)
    {
        $bank = DB::table('bank_accounts')->where('id', $id)->first();
        
        if (!$bank) {
            return redirect()->route('bank.enrollment')->with('message', 'Bank account not found');
        }

        $banks = DB::table('bank_accounts')->orderBy('bank_name')->get();

        $savingsPhpCount = 0;
        $currentPhpCount = 0;
        $savingsDollarCount = 0;
        $currentDollarCount = 0;

        foreach ($banks as $b) {
            $accountType = (string) ($b->account_type ?? '');
            $currency = (string) ($b->currency ?? '');

            if ($accountType === 'Savings - PHP' || ($accountType === 'Savings' && $currency === 'PHP')) {
                $savingsPhpCount++;
            } elseif ($accountType === 'Current - PHP' || ($accountType === 'Current' && $currency === 'PHP')) {
                $currentPhpCount++;
            } elseif ($accountType === 'Savings - Dollar' || ($accountType === 'Savings' && $currency === 'USD')) {
                $savingsDollarCount++;
            } elseif ($accountType === 'Current - Dollar' || ($accountType === 'Current' && $currency === 'USD')) {
                $currentDollarCount++;
            } elseif ($accountType === 'Dollar' || $accountType === 'Dollar - USD') {
                $savingsDollarCount++;
            }
        }

        return view('pages.bank-enrollment', [
            'banks' => $banks,
            'bankData' => $bank,
            'editId' => $id,
            'showModal' => true,
            'savingsPhpCount' => $savingsPhpCount,
            'currentPhpCount' => $currentPhpCount,
            'savingsDollarCount' => $savingsDollarCount,
            'currentDollarCount' => $currentDollarCount,
            'message' => session('message'),
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'bank_name' => ['required', 'string', 'max:255'],
            'account_name' => ['required', 'string', 'max:255'],
            'account_no' => ['required', 'string', 'max:255'],
            'account_type' => ['required', 'string', 'max:255'],
        ]);

        $accountType = $validated['account_type'];
        $currency = Str::contains($accountType, 'Dollar') ? 'USD' : 'PHP';

        DB::table('bank_accounts')
            ->where('id', $id)
            ->update([
                'bank_name' => $validated['bank_name'],
                'account_name' => $validated['account_name'],
                'account_no' => $validated['account_no'],
                'account_type' => $accountType,
                'currency' => $currency,
                'updated_at' => now(),
            ]);

        return redirect()->route('bank.enrollment')->with('message', 'Bank account updated successfully');
    }

    public function destroy($id)
    {
        DB::table('bank_accounts')->where('id', $id)->delete();
        return redirect()->route('bank.enrollment')->with('message', 'Bank account deleted successfully');
    }
}


