<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->integer('Department_Id', false, true);
            $table->string('Department', 100);
            $table->primary('Department_Id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};


