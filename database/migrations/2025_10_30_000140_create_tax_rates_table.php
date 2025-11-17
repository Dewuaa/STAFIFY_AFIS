<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tax_name', 50)->nullable();
            $table->decimal('tax_rate', 5, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_rates');
    }
};


