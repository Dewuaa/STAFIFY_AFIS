<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->increments('Invoice_Id');
            $table->text('Customer_Name');
            $table->string('Customer_Email', 255);
            $table->text('Billing_Address')->nullable();
            $table->text('Item_Name');
            $table->decimal('Price', 10, 2);
            $table->integer('Quantity');
            $table->string('Discount', 50)->default('0');
            $table->decimal('Tax', 10, 2)->default(0);
            $table->text('Terms')->nullable();
            $table->string('invoice_mode', 20)->default('VAT');
            $table->string('tax_type_at_creation', 50)->default('VAT (12%)');
            $table->integer('tax_id')->nullable();
            $table->integer('discount_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};


