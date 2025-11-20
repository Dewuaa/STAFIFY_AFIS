<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AccountController extends Controller
{
    public function indexJson()
    {
        return response()->json(
            ChartOfAccount::orderBy('account_number')->get()
        );
    }

    public function store(Request $request)
    {
        // 1. VALIDATION: Checks DB for duplicates automatically
        // If duplicate, it stops here and sends 422 Error back to JS
        $validated = $this->validatePayload($request);

        try {
            $account = ChartOfAccount::create($validated);
            return response()->json([
                'success' => true, 
                'message' => 'Account created successfully!',
                'account' => $account
            ]);
        } catch (\Exception $e) {
            // 2. SAFETY NET: Catches unexpected DB errors (like connection issues)
            return response()->json([
                'success' => false, 
                'message' => 'Database Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, ChartOfAccount $account)
    {
        // Pass the $account object so validation ignores its own ID
        $validated = $this->validatePayload($request, $account);

        try {
            $account->update($validated);
            return response()->json([
                'success' => true, 
                'message' => 'Account updated successfully!',
                'account' => $account->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Update Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(ChartOfAccount $account)
    {
        // Check if this account acts as a parent to others
        $hasChildren = ChartOfAccount::where('parent_account_number', $account->account_number)->exists();

        if ($hasChildren) {
            return response()->json([
                'success' => false, 
                'message' => 'Cannot delete: This account has sub-accounts. Please move or delete them first.'
            ], 422);
        }

        try {
            $account->delete();
            return response()->json(['success' => true, 'message' => 'Account deleted']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Could not delete: ' . $e->getMessage()], 500);
        }
    }

    public function bulkDestroy(Request $request)
    {
        $ids = collect($request->input('ids', []))->filter()->all();
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No IDs provided'], 422);
        }

        try {
            $deleted = ChartOfAccount::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'deletedCount' => $deleted]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Bulk delete failed: ' . $e->getMessage()], 500);
        }
    }

    public function export(): StreamedResponse
    {
        $filename = 'chart_of_accounts_export.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            // Header
            fputcsv($handle, [
                'id', 'account_group', 'account_type', 'account_number', 'is_parent',
                'parent_account_number', 'description', 'is_active', 'created_at', 'updated_at'
            ]);
            // Rows
            ChartOfAccount::orderBy('account_number')->chunk(500, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $row->id,
                        $row->account_group,
                        $row->account_type,
                        $row->account_number,
                        $row->is_parent,
                        $row->parent_account_number,
                        $row->description,
                        $row->is_active,
                        $row->created_at,
                        $row->updated_at,
                    ]);
                }
            });
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Centralized Validation Logic
     * Accepts optional $account model to handle "ignore ID" logic during updates
     */
    private function validatePayload(Request $request, ?ChartOfAccount $account = null): array
    {
        // Define the unique rule dynamically
        // "unique:table,column"
        // If updating, append ",id" to ignore the current record
        $uniqueRule = Rule::unique('chart_of_accounts', 'account_number');
        
        if ($account) {
            $uniqueRule->ignore($account->id);
        }

        return $request->validate([
            'account_group' => ['required', 'string', 'max:255'],
            'account_type' => ['required', 'string', 'max:255'],
            // This is the CRITICAL fix:
            'account_number' => ['required', 'integer', $uniqueRule],
            'is_parent' => ['required', 'integer', 'in:0,1'],
            'parent_account_number' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'integer', 'in:0,1'],
        ]);
    }
}