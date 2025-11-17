<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->integer('Location_Id', false, true);
            $table->text('Location');
            $table->primary('Location_Id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};


