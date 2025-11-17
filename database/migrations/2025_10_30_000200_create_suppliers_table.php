<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('numbering');
            $table->string('supplier_type', 50)->nullable();
            $table->string('materials_type', 50)->nullable();
            $table->text('name');
            $table->string('address', 255)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('contact', 20)->nullable();
            $table->text('contact_person')->nullable();
            $table->text('bank_name')->nullable();
            $table->text('bank_type')->nullable();
            $table->text('account_name')->nullable();
            $table->string('account_number', 50)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};


