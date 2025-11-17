<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('po_number', 100);
            $table->date('order_date')->nullable();
            $table->string('purchase_request_id', 100)->nullable();
            $table->string('delivery_location', 255)->nullable();
            $table->string('asset_type', 100)->nullable();
            $table->string('asset_category', 100)->nullable();
            $table->text('asset_name')->nullable();
            $table->text('asset_description')->nullable();
            $table->integer('asset_quantity')->nullable();
            $table->decimal('cost', 15, 2)->nullable();
            $table->string('supplier_name', 255)->nullable();
            $table->string('preferred_payment_method', 100)->nullable();
            $table->string('requested_payment_terms', 100)->nullable();
            $table->date('expected_delivery_date')->nullable();
            $table->dateTime('order_placed')->nullable();
            $table->dateTime('payment_confirmed')->nullable();
            $table->dateTime('shipped_out')->nullable();
            $table->dateTime('order_received')->nullable();
            $table->dateTime('completed')->nullable();
            $table->dateTime('returned')->nullable();
            $table->string('status', 50)->nullable();
            $table->text('notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};


