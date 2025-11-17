<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->integer('Category_Id', false, true);
            $table->text('Category');
            $table->primary('Category_Id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};


