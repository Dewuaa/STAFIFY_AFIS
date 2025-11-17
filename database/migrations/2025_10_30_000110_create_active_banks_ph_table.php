<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('active_banks_ph', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bank_name', 100);
            $table->enum('bank_type', ['Universal/Commercial', 'Digital', 'Thrift', 'Rural/Cooperative']);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('active_banks_ph');
    }
};


