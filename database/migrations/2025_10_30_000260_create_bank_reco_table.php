<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_reco', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_name', 255);
            $table->string('account_number', 100);
            $table->string('account_type', 50);
            $table->integer('bank_id')->nullable()->unsigned();
            $table->decimal('opening_balance', 10, 2)->default(0.00);
            $table->decimal('current_balance', 10, 2)->default(0.00);
            $table->date('last_reconciled_date')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('bank_id')->references('id')->on('active_banks_ph')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_reco');
    }
};


