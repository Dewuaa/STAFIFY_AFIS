<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label', 50)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};


