<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('petty_cash_voucher', function (Blueprint $table) {
            $table->increments('voucher_id');
            $table->string('voucher_number', 50);
            $table->date('date_issued');
            $table->string('payee_name');
            $table->string('payee_email');
            $table->string('contact_number', 20)->nullable();
            $table->string('department', 100)->nullable();
            $table->string('position', 100)->nullable();
            $table->text('purpose');
            $table->decimal('amount', 10, 2);
            $table->integer('category_id')->unsigned();
            $table->string('payment_method', 50)->default('Cash');
            $table->string('approved_by')->nullable();
            $table->string('received_by')->nullable();
            $table->tinyInteger('receipt_attached')->default(0);
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('created_at')->useCurrent();
            $table->tinyInteger('is_signed')->default(0);
            $table->string('signature_token', 255)->nullable();
            $table->dateTime('signature_date')->nullable();
            $table->string('signature_ip', 45)->nullable();
            $table->longText('signature_image')->nullable();

            $table->index('category_id');
            $table->foreign('category_id')->references('id')->on('expense_categories');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petty_cash_voucher');
    }
};


