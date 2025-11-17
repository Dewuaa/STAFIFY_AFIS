@extends('layouts.app')

@section('title', 'E-Commerce')
@section('description', 'Launch your E-Commerce store that sells more - Everything you need to Start, Run, and Grow your online business')

@section('content')
<div class="p-6">
    <!-- Main Description -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-800 mb-6">Launch your E-Commerce store that sells more</h1>
        <p class="text-xl text-gray-600 mb-4">Everything you need to Start, Run, and Grow your online business in one place</p>
        <p class="text-lg text-gray-600">From product listings to payments, fulfillment, and growth tools</p>
    </div>

    <!-- Contact Sales Button -->
    <div class="text-center mb-12">
        <button onclick="openContactSalesModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-semibold">
            Contact Sales
        </button>
        <p class="text-sm text-gray-500 mt-2">Sold separately</p>
    </div>

    <!-- Feature Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Card 1 -->
        <div class="bg-white p-6 rounded-lg shadow-md text-center">
            <div class="text-blue-500 text-4xl mb-4">
                <i class="fas fa-box-open"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Create</h3>
            <p class="text-gray-600">Collections, Products, Variants</p>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-6 rounded-lg shadow-md text-center">
            <div class="text-blue-500 text-4xl mb-4">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Manage</h3>
            <p class="text-gray-600">Orders & Customers</p>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-6 rounded-lg shadow-md text-center">
            <div class="text-blue-500 text-4xl mb-4">
                <i class="fas fa-warehouse"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Manage</h3>
            <p class="text-gray-600">Inventory, Locations, Transfers, Purchase Orders</p>
        </div>
    </div>
</div>

<!-- Contact Sales Modal -->
<div id="contactSalesModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative min-h-screen px-4 text-center">
        <div class="fixed inset-0" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="inline-block h-screen align-middle" aria-hidden="true">&#8203;</span>
        <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl relative">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-medium text-gray-800">Contact Sales</h3>
                <button onclick="closeContactSalesModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-4">
                <p class="text-gray-600 mb-4">You may reach us out:</p>
                <div class="space-y-3">
                    <p class="text-gray-800"><i class="fas fa-phone mr-2"></i>+639171022610</p>
                    <p class="text-gray-800"><i class="fas fa-phone mr-2"></i>+63476030032</p>
                    <p class="text-gray-800"><i class="fas fa-envelope mr-2"></i>Sales@stafify.com</p>
                    <p class="text-gray-800"><i class="fas fa-globe mr-2"></i>www.Stafify.com</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openContactSalesModal() {
    document.getElementById('contactSalesModal').classList.remove('hidden');
}

function closeContactSalesModal() {
    document.getElementById('contactSalesModal').classList.add('hidden');
}
</script>
@endpush
