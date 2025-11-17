<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts_receivable', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contact_id')->nullable()->unsigned();
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['unpaid', 'paid'])->nullable();

            $table->index('contact_id');
            $table->foreign('contact_id')->references('id')->on('contacts');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts_receivable');
    }
};


