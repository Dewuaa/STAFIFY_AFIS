@extends('layouts.app')

@section('title', 'Capitalization Management')

@push('styles')
<style>
    /* --- 1. Root Variables (Consistent) --- */
    :root {
        --primary-color: #3B82F6;
        --primary-color-hover: #184278;
        --primary-light: #e6ecfe; 
        --secondary-color: #f1f5f9;
        --secondary-color-hover: #e2e8f0;
        --danger-color: #e74c3c;
        --danger-color-hover: #c0392b;
        --warning-color: #f39c12;
        --success-color: #22c55e;
        --success-light: #dcfce7;
        --danger-light: #fee2e2;
        --text-color: #334155;
        --text-color-light: #64748b;
        --border-color: #e2e8f0;
        --white: #ffffff;
        --border-radius: 6px;
        --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.07);
        --transition: all 0.2s ease-in-out;
    }
    
    /* --- 2. General Layout --- */
    .bank-reconciliation-wrapper {
        padding: 0;
        max-width: 100%;
        margin: 0 auto;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .filter-card {
        background: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 20px; /* Reduced padding slightly */
        margin-bottom: 24px;
    }

    /* Responsive Filter Header */
    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 20px;
        flex-wrap: wrap; /* Allow wrapping */
    }
    
    .filter-form-wrapper {
        flex-grow: 1;
        max-width: 100%;
    }

    .filter-form-group {
        display: flex;
        align-items: flex-end;
        gap: 10px;
        flex-wrap: wrap; /* Important for mobile */
    }
    
    .filter-form-group .form-group {
        margin-bottom: 0;
        flex-grow: 1;
    }

    /* Make select responsive instead of fixed 250px */
    #bank-filter {
        width: 250px;
        max-width: 100%; 
    }

    .filter-actions {
        text-align: right;
        white-space: nowrap; /* Keep button text on one line */
    }

    /* --- 3. Button Styles --- */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: var(--white);
        cursor: pointer;
        border: 1px solid transparent;
        padding: 10px 16px;
        font-size: 0.95rem;
        border-radius: var(--border-radius);
        transition: var(--transition);
        text-decoration: none;
        height: 42px; /* Enforce consistent height */
    }
    .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); }
    .btn-primary:hover { background-color: var(--primary-color-hover); }
    .btn-secondary { background-color: var(--danger-color); border-color: var(--danger-color); }
    .btn-secondary:hover { background-color: var(--danger-color-hover); }
    .btn-light { background-color: var(--secondary-color); color: var(--text-color); border-color: var(--border-color); }
    .btn-light:hover { background-color: var(--secondary-color-hover); }

    .btn-icon-delete {
        padding: 8px; width: 36px; height: 36px; border-radius: 50%;
        background-color: var(--secondary-color); color: var(--text-color-light);
        border: none; cursor: pointer; transition: var(--transition);
        display: flex; align-items: center; justify-content: center;
    }
    .btn-icon-delete:hover { background-color: var(--danger-color); color: var(--white); }

    /* --- 4. Summary Cards --- */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }
    .summary-card-item {
        padding: 20px;
        border-radius: var(--border-radius);
        background-color: var(--white);
        border: 1px solid var(--border-color);
        box-shadow: var(--box-shadow);
        text-align: center;
    }
    .stat-icon-wrapper {
        width: 48px; height: 48px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 12px auto;
    }
    .stat-icon-wrapper svg { width: 24px; height: 24px; }
    .icon-deposits { background-color: var(--success-light); color: var(--success-color); }
    .icon-withdrawals { background-color: var(--danger-light); color: var(--danger-color); }
    .icon-balance { background-color: var(--primary-light); color: var(--primary-color); }
    .summary-label { font-size: 13px; font-weight: 600; text-transform: uppercase; color: var(--text-color-light); margin-bottom: 4px; }
    .summary-value { font-size: 22px; font-weight: 700; }
    .text-success { color: var(--success-color); }
    .text-danger { color: var(--danger-color); }
    .text-primary-custom { color: var(--primary-color); }

    /* --- 5. Table Styles --- */
    .content-card { background: var(--white); border-radius: var(--border-radius); box-shadow: var(--box-shadow); overflow: hidden; }
    .content-card-body { padding: 20px; }
    .content-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px; }
    
    .table-responsive-wrapper { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .transaction-table { width: 100%; border-collapse: collapse; font-size: 14px; min-width: 800px; /* Force scroll on small screens */ }
    .transaction-table th { background-color: #f8f9fa; padding: 12px 16px; text-align: left; font-weight: 600; color: var(--text-color-light); text-transform: uppercase; font-size: 12px; border-bottom: 1px solid var(--border-color); }
    .transaction-table td { padding: 12px 16px; border-bottom: 1px solid var(--border-color); color: var(--text-color); vertical-align: middle; }
    .transaction-table tr:hover { background-color: #f8fafc; }
    
    .badge-pending { background-color: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 12px; font-weight: 600; font-size: 11px; }
    .badge-cleared { background-color: var(--success-light); color: var(--success-color); padding: 4px 10px; border-radius: 12px; font-weight: 600; font-size: 11px; }
    .badge-outstanding { background-color: var(--danger-light); color: var(--danger-color); padding: 4px 10px; border-radius: 12px; font-weight: 600; font-size: 11px; }

    /* --- 6. Modal & Forms --- */
    .custom-modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 1050; opacity: 0; transition: opacity 0.2s; }
    .custom-modal-overlay.active { display: flex; opacity: 1; }
    .custom-modal-content { background: var(--white); border-radius: 12px; width: 95%; max-width: 600px; max-height: 90vh; display: flex; flex-direction: column; transform: scale(0.95); transition: transform 0.2s; }
    .custom-modal-overlay.active .custom-modal-content { transform: scale(1); }
    
    .custom-modal-header { padding: 15px 20px; border-bottom: 1px solid var(--border-color); }
    .custom-modal-title { font-size: 18px; font-weight: 600; margin: 0; }
    .custom-modal-body { padding: 20px; overflow-y: auto; }

    .modal-tabs-nav { display: flex; gap: 20px; border-bottom: 1px solid var(--border-color); margin-bottom: 20px; }
    .modal-tab-link { background: none; border: none; padding: 10px 0; cursor: pointer; color: var(--text-color-light); font-weight: 500; border-bottom: 2px solid transparent; transition: all 0.2s; }
    .modal-tab-link.active { color: var(--primary-color); border-bottom-color: var(--primary-color); font-weight: 600; }
    .modal-tab-pane { display: none; }
    .modal-tab-pane.active { display: block; }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    .form-group { margin-bottom: 15px; }
    .form-group-full { grid-column: 1 / -1; }
    .form-label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 13px; }
    .form-field { width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 14px; background-color: #fff; box-sizing: border-box; }
    .form-field:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 2px var(--primary-light); }
    .form-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; padding-top: 15px; border-top: 1px solid var(--border-color); }

    /* --- 7. Pagination --- */
    .pagination-container { display: flex; justify-content: center; margin-top: 20px; }
    .pagination-list { list-style: none; padding: 0; display: flex; }
    .pagination-link { padding: 8px 12px; border: 1px solid var(--border-color); background: #fff; color: var(--primary-color); text-decoration: none; margin-left: -1px; }
    .pagination-item:first-child .pagination-link { border-radius: 4px 0 0 4px; }
    .pagination-item:last-child .pagination-link { border-radius: 0 4px 4px 0; }
    .pagination-item.active .pagination-link { background: var(--primary-color); color: #fff; border-color: var(--primary-color); }

    /* --- 8. MEDIA QUERIES (Mobile Responsiveness) --- */
    @media (max-width: 768px) {
        /* Stack filter controls */
        .filter-header {
            flex-direction: column;
            align-items: stretch;
            gap: 15px;
        }
        
        .filter-form-group {
            flex-direction: column;
            align-items: stretch;
            width: 100%;
        }

        /* Make select full width */
        #bank-filter {
            width: 100% !important;
        }

        /* Buttons stack full width */
        .filter-form-group .btn {
            width: 100%;
            margin-top: 10px;
        }

        /* Add Transaction button full width */
        .filter-actions {
            text-align: center;
            width: 100%;
        }
        .filter-actions .btn {
            width: 100%;
        }

        /* Stack Summary Cards */
        .summary-grid {
            grid-template-columns: 1fr; /* 1 card per row */
            gap: 15px;
        }

        /* Form inside modal stacks */
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        /* Adjust table header/pagination */
        .content-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .page-info {
            font-size: 12px;
        }
    }
</style>
@endpush

@section('content')
<div class="bank-reconciliation-wrapper">
    @if (!empty($flashMessage))
        <div class="alert alert-{{ ($flashMessage['type'] ?? '') === 'error' ? 'danger' : ($flashMessage['type'] ?? 'info') }}">
            {{ $flashMessage['message'] ?? '' }}
        </div>
    @endif

    {{-- Filter Card (Now includes Add Transaction Button on the right) --}}
    <div class="filter-card">
        <div class="filter-header">
            
            {{-- Left Side: Filter Options --}}
            <div class="filter-form-wrapper">
                <h5 class="card-title" style="color: var(--text-color); font-weight: 600; font-size: 1.1rem; margin-bottom: 15px;">Filter by Bank</h5>
                <form method="GET" class="filter-form-group">
                    <div class="form-group">
                        <select name="bank_id" style="width: 250px;" class="form-field form-select" id="bank-filter">
                            <option value="">All Banks</option>
                            @isset($activeBanks)
                                @foreach($activeBanks as $bank)
                                    <option value="{{ $bank['id'] ?? $bank->id }}" {{ (isset($selectedBank) && (string)$selectedBank === (string)($bank['id'] ?? $bank->id)) ? 'selected' : '' }}>
                                        {{ $bank['bank_name'] ?? $bank->bank_name }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                    @if (!empty($selectedBank))
                        <a href="{{ url()->current() }}" class="btn btn-light">Clear Filter</a>
                    @endif
                </form>
            </div>
            
            {{-- Right Side: Add Transaction Button --}}
            <div class="filter-actions">
                <button type="button" class="btn btn-primary" id="open-modal-btn">
                    <i class="fas fa-plus me-2"></i>Add Transaction
                </button>
            </div>
        </div>
    </div>

    {{-- Summary Grid --}}
    <div class="summary-grid">
        <div class="summary-card-item">
            <div class="stat-icon-wrapper icon-deposits">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div class="summary-label">IN</div>
            <div class="summary-value text-success">₱{{ number_format($summary['deposits'] ?? 0, 2) }}</div>
        </div>
        <div class="summary-card-item">
            <div class="stat-icon-wrapper icon-withdrawals">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                 </svg>
            </div>
            <div class="summary-label">OUT</div>
            <div class="summary-value text-danger">₱{{ number_format($summary['withdrawals'] ?? 0, 2) }}</div>
        </div>
        <div class="summary-card-item">
            <div class="stat-icon-wrapper icon-balance">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 12m18 0v6.248a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18.248V12m18 0c0-5.968-4.03-10.9-9-10.9S3 6.032 3 12m18 0c0 .377-.021.75-.062 1.121" />
                </svg>
            </div>
            <div class="summary-label">Balance</div>
            <div class="summary-value {{ ($summary['balance'] ?? 0) >= 0 ? 'text-primary-custom' : 'text-danger' }}">
                ₱{{ number_format($summary['balance'] ?? 0, 2) }}
            </div>
        </div>
    </div>

    {{-- Transactions Table Section --}}
    <div class="content-card">
        <div class="content-card-body">
            <div class="content-header">
                <h2 style="font-size: 1.5rem; color: var(--text-color); font-weight: 600;">Transactions</h2>
                <div class="page-info">Page {{ $page ?? 1 }} of {{ max(1, $totalPages ?? 1) }}</div>
            </div>
            <div class="entries-info">
                Showing {{ isset($transactions) ? count($transactions) : 0 }} of {{ $totalTransactions ?? 0 }} entries
            </div>

            <div class="table-responsive-wrapper">
                <table class="transaction-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Reference</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Account</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @if (empty($transactions))
                            <tr>
                                <td colspan="7" class="text-center py-4" style="text-align: center; padding: 1rem 0;">No transactions found</td>
                            </tr>
                        @else
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($transaction['transaction_date'] ?? $transaction->transaction_date)->format('M j, Y') }}</td>
                                    <td>{{ $transaction['reference_number'] ?? $transaction['payee'] ?? $transaction->reference_number ?? $transaction->payee ?? '-' }}</td>
                                    <td>{{ $transaction['description'] ?? $transaction->description ?? '-' }}</td>
                                    @php
                                        $type = $transaction['transaction_type'] ?? $transaction->transaction_type;
                                        $amount = (float)($transaction['amount'] ?? $transaction->amount ?? 0);
                                    @endphp
                                    <td class="{{ $type === 'withdrawal' ? 'text-danger' : ($type === 'adjustment' ? 'text-warning' : 'text-success') }}">
                                        @if ($type === 'withdrawal')
                                            -₱{{ number_format($amount, 2) }}
                                        @else
                                            ₱{{ number_format($amount, 2) }}
                                        @endif
                                    </td>
                                    <td>{{ $transaction['bank_name'] ?? $transaction->bank_name ?? $transaction['account_name'] ?? $transaction->account_name }}</td>
                                    <td>
                                        @php $status = $transaction['status'] ?? $transaction->status ?? 'pending'; @endphp
                                        @if ($status === 'cleared')
                                            <span class="badge-cleared">Cleared</span>
                                        @elseif ($status === 'pending')
                                            <span class="badge-pending">Pending</span>
                                        @else
                                            <span class="badge-outstanding">Outstanding</span>
                                        @endif
                                    </td>
                                    <td> 
                                        <div class="action-container">
                                            <form method="POST" action="{{ route('bank-reconciliation.delete') }}" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="transaction_id" value="{{ $transaction['id'] ?? $transaction->id }}">
                                                <button type="submit" class="btn-icon-delete" onclick="return confirm('Are you sure you want to delete this transaction?')" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        @if (($totalPages ?? 1) > 1)
            <div class="pagination-container">
                <nav aria-label="Page navigation">
                    <ul class="pagination-list">
                        @if ($page > 1)
                            <li class="pagination-item">
                                <a class="pagination-link" href="?page=1{{ $selectedBank ? '&bank_id='.$selectedBank : '' }}">
                                    <i class="fas fa-angle-double-left"></i>
                                </a>
                            </li>
                            <li class="pagination-item">
                                <a class="pagination-link" href="?page={{ $page - 1 }}{{ $selectedBank ? '&bank_id='.$selectedBank : '' }}">
                                    <i class="fas fa-angle-left"></i>
                                </a>
                            </li>
                        @endif
                        
                        @php
                            $startPage = max(1, $page - 2);
                            $endPage = min($totalPages, $startPage + 4);
                            if ($endPage - $startPage < 4) {
                                $startPage = max(1, $endPage - 4);
                            }
                        @endphp
                        
                        @for ($i = $startPage; $i <= $endPage; $i++)
                            <li class="pagination-item {{ $i == $page ? 'active' : '' }}">
                                <a class="pagination-link" href="?page={{ $i }}{{ $selectedBank ? '&bank_id='.$selectedBank : '' }}">{{ $i }}</a>
                            </li>
                        @endfor
                        
                        @if ($page < $totalPages)
                            <li class="pagination-item">
                                <a class="pagination-link" href="?page={{ $page + 1 }}{{ $selectedBank ? '&bank_id='.$selectedBank : '' }}">
                                    <i class="fas fa-angle-right"></i>
                                </a>
                            </li>
                            <li class="pagination-item">
                                <a class="pagination-link" href="?page={{ $totalPages }}{{ $selectedBank ? '&bank_id='.$selectedBank : '' }}">
                                    <i class="fas fa-angle-double-right"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        @endif
    </div>

    {{-- MODAL: Custom Implementation --}}
    <div class="custom-modal-overlay" id="addTransactionModal" role="dialog" aria-modal="true" aria-labelledby="addTransactionModalLabel">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h5 class="custom-modal-title" id="addTransactionModalLabel">Add Transaction</h5>
                {{-- 'x' BUTTON HTML REMOVED --}}
            </div>
            <div class="custom-modal-body">
                
                {{-- New "Business Settings" Style Tabs --}}
                <div class="modal-tabs-nav">
                    <button class="modal-tab-link active" data-tab="deposit" type="button" role="tab">Deposit</button>
                    <button class="modal-tab-link" data-tab="withdrawal" type="button" role="tab">Withdrawal</button>
                    <button class="modal-tab-link" data-tab="adjustment" type="button" role="tab">Balance Adjustment</button>
                </div>

                <div class="modal-tabs-content">
                    
                    {{-- Deposit Tab --}}
                    <div class="modal-tab-pane active" id="deposit-tab-content" role="tabpanel">
                        <form method="POST" action="{{ route('bank-reconciliation.deposit') }}">
                            @csrf
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="deposit-date" class="form-label">Date *</label>
                                    <input type="date" class="form-field form-input" id="deposit-date" name="date" required value="{{ now()->toDateString() }}">
                                </div>
                                <div class="form-group">
                                    <label for="deposit-amount" class="form-label">Amount *</label>
                                    <input type="text" class="form-field form-input" id="deposit-amount" name="amount" placeholder="0.00" required>
                                </div>
                                <div class="form-group">
                                    <label for="deposit-reference" class="form-label">Reference Number</label>
                                    <input type="text" class="form-field form-input" id="deposit-reference" name="reference">
                                </div>
                                <div class="form-group">
                                    <label for="deposit-method" class="form-label">Deposit Method</label>
                                    <select class="form-field form-select" id="deposit-method" name="deposit_method">
                                        <option value="Cash">Cash</option>
                                        <option value="Check">Check</option>
                                        <option value="Bank transfer">Bank transfer</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="deposit-bank-id" class="form-label">Bank *</label>
                                    <select class="form-field form-select" id="deposit-bank-id" name="bank_id" required>
                                        <option value="">Select Bank</option>
                                        @isset($activeBanks)
                                            @foreach($activeBanks as $bank)
                                                <option value="{{ $bank['id'] ?? $bank->id }}">{{ $bank['bank_name'] ?? $bank->bank_name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="deposit-status" class="form-label">Status *</label>
                                    <select class="form-field form-select" id="deposit-status" name="status" required>
                                        <option value="cleared">Cleared</option>
                                        <option value="pending">Pending</option>
                                        <option value="outstanding">Outstanding</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-group-full">
                                <label for="deposit-description" class="form-label">Description</label>
                                <textarea class="form-field form-textarea" id="deposit-description" name="description" rows="2"></textarea>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn btn-secondary btn-modal-cancel">Cancel</button>
                                <button type="submit" class="btn btn-primary">Add Deposit</button>
                            </div>
                        </form>
                    </div>

                    {{-- Withdrawal Tab --}}
                    <div class="modal-tab-pane" id="withdrawal-tab-content" role="tabpanel">
                        <form method="POST" action="{{ route('bank-reconciliation.withdrawal') }}">
                            @csrf
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="withdrawal-date" class="form-label">Date *</label>
                                    <input type="date" class="form-field form-input" id="withdrawal-date" name="date" required value="{{ now()->toDateString() }}">
                                </div>
                                <div class="form-group">
                                    <label for="withdrawal-amount" class="form-label">Amount *</label>
                                    <input type="text" class="form-field form-input" id="withdrawal-amount" name="amount" placeholder="0.00" required>
                                </div>
                                <div class="form-group">
                                    <label for="withdrawal-reference" class="form-label">Check/Ref Number</label>
                                    <input type="text" class="form-field form-input" id="withdrawal-reference" name="reference">
                                </div>
                                <div class="form-group">
                                    <label for="withdrawal-payee" class="form-label">Payee</label>
                                    <input type="text" class="form-field form-input" id="withdrawal-payee" name="payee">
                                </div>
                                <div class="form-group">
                                    <label for="withdrawal-method" class="form-label">Withdrawal Method</label>
                                    <select class="form-field form-select" id="withdrawal-method" name="withdrawal_method">
                                        <option value="Check">Check</option>
                                        <option value="EFT">EFT</option>
                                        <option value="Cash">Cash</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="withdrawal-bank-id" class="form-label">Bank *</label>
                                    <select class="form-field form-select" id="withdrawal-bank-id" name="bank_id" required>
                                        <option value="">Select Bank</option>
                                        @isset($activeBanks)
                                            @foreach($activeBanks as $bank)
                                                <option value="{{ $bank['id'] ?? $bank->id }}">{{ $bank['bank_name'] ?? $bank->bank_name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="withdrawal-status" class="form-label">Status *</label>
                                    <select class="form-field form-select" id="withdrawal-status" name="status" required>
                                        <option value="cleared">Cleared</option>
                                        <option value="pending">Pending</option>
                                        <option value="outstanding">Outstanding</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-group-full">
                                <label for="withdrawal-description" class="form-label">Description</label>
                                <textarea class="form-field form-textarea" id="withdrawal-description" name="description" rows="2"></textarea>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn btn-secondary btn-modal-cancel">Cancel</button>
                                <button type="submit" class="btn btn-primary">Add Withdrawal</button>
                            </div>
                        </form>
                    </div>

                    {{-- Adjustment Tab --}}
                    <div class="modal-tab-pane" id="adjustment-tab-content" role="tabpanel">
                        <form method="POST" action="{{ route('bank-reconciliation.adjustment') }}">
                            @csrf
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="adjustment-date" class="form-label">Date *</label>
                                    <input type="date" class="form-field form-input" id="adjustment-date" name="date" required value="{{ now()->toDateString() }}">
                                </div>
                                <div class="form-group">
                                    <label for="adjustment-amount" class="form-label">Amount *</label>
                                    <input type="text" class="form-field form-input" id="adjustment-amount" name="amount" placeholder="0.00" required>
                                    <small class="form-text">Use negative values for charges or losses.</small>
                                </div>
                                <div class="form-group">
                                    <label for="adjustment-type" class="form-label">Adjustment Type</label>
                                    <select class="form-field form-select" id="adjustment-type" name="adjustment_type">
                                        <option value="Service charge">Service charge</option>
                                        <option value="Bank error">Bank error</option>
                                        <option value="FX gain-loss">FX gain-loss</option>
                                        <option value="Interest earned">Interest earned</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="adjustment-bank-id" class="form-label">Bank *</label>
                                    <select class="form-field form-select" id="adjustment-bank-id" name="bank_id" required>
                                        <option value="">Select Bank</option>
                                        @isset($activeBanks)
                                            @foreach($activeBanks as $bank)
                                                <option value="{{ $bank['id'] ?? $bank->id }}">{{ $bank['bank_name'] ?? $bank->bank_name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-group-full">
                                <label for="adjustment-description" class="form-label">Description</label>
                                <textarea class="form-field form-textarea" id="adjustment-description" name="description" rows="2"></textarea>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn btn-secondary btn-modal-cancel">Cancel</button>
                                <button type="submit" class="btn btn-primary">Add Adjustment</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Removed Bootstrap JS --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- Set today's date as default for all date inputs ---
        const today = new Date().toISOString().split('T')[0];
        const d = document.getElementById('deposit-date');
        const w = document.getElementById('withdrawal-date');
        const a = document.getElementById('adjustment-date');
        if (d && !d.value) d.value = today;
        if (w && !w.value) w.value = today;
        if (a && !a.value) a.value = today;

        // --- Number formatting with commas ---
        function formatNumberWithCommas(value) {
            // Remove all non-digit and non-decimal characters
            let cleaned = value.replace(/[^\d.-]/g, '');
            
            // Handle negative sign
            const isNegative = cleaned.startsWith('-');
            if (isNegative) {
                cleaned = cleaned.substring(1);
            }
            
            // Split by decimal point
            const parts = cleaned.split('.');
            let integerPart = parts[0];
            const decimalPart = parts[1];
            
            // Add commas to integer part
            integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            
            // Reconstruct the number
            let formatted = integerPart;
            if (decimalPart !== undefined) {
                formatted += '.' + decimalPart.substring(0, 2); // Limit to 2 decimal places
            }
            
            if (isNegative) {
                formatted = '-' + formatted;
            }
            
            return formatted;
        }

        function setupAmountFormatting(inputId) {
            const input = document.getElementById(inputId);
            if (!input) return;
            
            // Store the actual numeric value
            let actualValue = '';
            
            input.addEventListener('input', function(e) {
                const cursorPosition = this.selectionStart;
                const oldLength = this.value.length;
                
                // Format the value
                const formatted = formatNumberWithCommas(this.value);
                this.value = formatted;
                
                // Adjust cursor position
                const newLength = formatted.length;
                const diff = newLength - oldLength;
                this.setSelectionRange(cursorPosition + diff, cursorPosition + diff);
            });
            
            // On form submit, remove commas for server processing
            const form = input.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Create a hidden input with the clean value
                    const cleanValue = input.value.replace(/,/g, '');
                    input.setAttribute('data-formatted', input.value);
                    input.value = cleanValue;
                });
            }
        }

        // Apply formatting to all amount fields
        setupAmountFormatting('deposit-amount');
        setupAmountFormatting('withdrawal-amount');
        setupAmountFormatting('adjustment-amount');
        
        // --- Custom Modal Logic ---
        const modal = document.getElementById('addTransactionModal');
        const openBtn = document.getElementById('open-modal-btn');
        // 'closeBtn' const REMOVED
        const cancelBtns = modal.querySelectorAll('.btn-modal-cancel');

        const openModal = () => {
            modal.classList.add('active');
        };

        const closeModal = () => {
            modal.classList.remove('active');
        };

        // 'closeBtn' reference REMOVED from if statement
        if (modal && openBtn) { 
            openBtn.addEventListener('click', openModal);
            // 'closeBtn' event listener REMOVED
            
            // Close when clicking the overlay (background)
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Add listener to all "Cancel" buttons
            cancelBtns.forEach(btn => {
                btn.addEventListener('click', closeModal);
            });
        }
        
        // --- Custom Tab Switching Logic ---
        const tabLinks = modal.querySelectorAll('.modal-tab-link');
        const tabPanes = modal.querySelectorAll('.modal-tab-pane');
        
        tabLinks.forEach(link => {
            link.addEventListener('click', () => {
                const targetTab = link.dataset.tab;
                
                // 1. Deactivate all links and panes
                tabLinks.forEach(l => l.classList.remove('active'));
                tabPanes.forEach(p => p.classList.remove('active'));
                
                // 2. Activate the clicked link
                link.classList.add('active');
                
                // 3. Activate the corresponding pane
                const targetPane = modal.querySelector(`#${targetTab}-tab-content`);
                if (targetPane) {
                    targetPane.classList.add('active');
                }
            });
        });

    });
</script>
@endpush