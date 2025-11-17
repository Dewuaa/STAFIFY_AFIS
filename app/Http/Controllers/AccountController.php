<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
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
        $validated = $this->validatePayload($request);
        $account = ChartOfAccount::create($validated);
        return response()->json(['success' => true, 'account' => $account]);
    }

    public function update(Request $request, ChartOfAccount $account)
    {
        $validated = $this->validatePayload($request, updating: true);
        $account->update($validated);
        return response()->json(['success' => true, 'account' => $account->fresh()]);
    }

    public function destroy(ChartOfAccount $account)
    {
        $account->delete();
        return response()->json(['success' => true]);
    }

    public function bulkDestroy(Request $request)
    {
        $ids = collect($request->input('ids', []))->filter()->all();
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No IDs provided'], 422);
        }
        $deleted = ChartOfAccount::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'deletedCount' => $deleted]);
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

    private function validatePayload(Request $request, bool $updating = false): array
    {
        return $request->validate([
            'account_group' => ['required', 'string', 'max:255'],
            'account_type' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'integer'],
            'is_parent' => ['required', 'integer', 'in:0,1'],
            'parent_account_number' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'integer', 'in:0,1'],
        ]);
    }
}


