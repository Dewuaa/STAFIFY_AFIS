<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
	public function index()
	{
		$categoryRows = DB::table('products')
			->select('category', DB::raw('COUNT(*) as total'))
			->whereNotNull('category')
			->groupBy('category')
			->get();

		$categories = $categoryRows->pluck('category');
		$totals = $categoryRows->pluck('total');

		$inventoryValue = (float) DB::table('products')
			->select(DB::raw('COALESCE(SUM(price * qty), 0) as total_inventory'))
			->value('total_inventory');

		$metrics = [
			'accounts_payable' => 145780.50,
			'accounts_receivable' => 98650.25,
			'invoice_worth' => 215320.75,
			'inventory_value' => $inventoryValue,
			'ap_change' => 12.5,
			'ar_change' => -5.3,
			'invoice_change' => 8.7,
			'inventory_change' => 3.2,
		];

		return view('dashboard', [
			'categories' => $categories,
			'totals' => $totals,
			'metrics' => $metrics,
		]);
	}
}





