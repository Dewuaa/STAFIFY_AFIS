<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bank_name', 100);
            $table->string('account_name', 100);
            $table->string('account_no', 50);
            $table->enum('account_type', ['Savings - PHP', 'Current - PHP', 'Savings - Dollar', 'Current - Dollar']);
            $table->enum('currency', ['PHP', 'USD']);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};


