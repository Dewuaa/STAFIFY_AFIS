<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('email', 100);
            $table->string('location', 150)->nullable();
            $table->integer('orders')->default(0);
            $table->decimal('spent', 10, 2)->default(0.00);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};


