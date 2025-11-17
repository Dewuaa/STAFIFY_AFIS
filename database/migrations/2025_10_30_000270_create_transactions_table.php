<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->string('reference_number', 100)->nullable();
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('transaction_type', ['deposit', 'withdrawal', 'adjustment']);
            $table->string('method', 50)->nullable();
            $table->string('payee', 255)->nullable();
            $table->integer('bank_account_id')->unsigned();
            $table->enum('status', ['cleared', 'pending', 'outstanding']);
            $table->string('adjustment_type', 50)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->date('transaction_date');

            $table->foreign('bank_account_id')->references('id')->on('bank_reco');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};


