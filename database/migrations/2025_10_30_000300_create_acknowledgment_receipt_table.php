<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acknowledgment_receipt', function (Blueprint $table) {
            $table->increments('Receipt_Id');
            $table->string('Customer_Name');
            $table->string('Customer_Email');
            $table->string('contact_number', 20)->nullable();
            $table->string('Address', 500);
            $table->enum('purpose_type', ['payment', 'items'])->default('payment');
            $table->string('Payment_For', 500)->nullable();
            $table->text('items_received')->nullable();
            $table->string('location')->nullable();
            $table->decimal('Amount', 10, 2);
            $table->enum('payment_status', ['full', 'partial', 'down'])->default('full');
            $table->integer('Payment_Method_Id')->unsigned();
            $table->string('Reference_Number', 100)->nullable();
            $table->text('Notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->tinyInteger('is_signed')->default(0);
            $table->string('signature_token', 255)->nullable();
            $table->dateTime('signature_date')->nullable();
            $table->string('signature_ip', 45)->nullable();

            $table->index('Payment_Method_Id');
            $table->foreign('Payment_Method_Id')->references('id')->on('payment_methods');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acknowledgment_receipt');
    }
};


