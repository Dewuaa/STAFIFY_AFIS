<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_presets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->decimal('rate', 10, 2);
            $table->boolean('is_default')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_presets');
    }
};


