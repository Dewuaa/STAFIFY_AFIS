<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_approvals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('po_number', 100);
            $table->string('status', 20);
            $table->text('comment')->nullable();
            $table->text('terms')->nullable();
            $table->dateTime('timestamp')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_approvals');
    }
};


