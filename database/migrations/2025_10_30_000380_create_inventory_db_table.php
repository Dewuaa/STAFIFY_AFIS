<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_db', function (Blueprint $table) {
            $table->string('Title', 255);
            $table->text('Description');
            $table->string('Category', 100);
            $table->decimal('Price', 10, 2);
            $table->integer('Qty');
            $table->string('Media', 255);
            $table->integer('Item ID');
            $table->enum('Status', ['pending', 'approved', 'rejected']);
            $table->decimal('Cost', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_db');
    }
};


