<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('category', 100)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('qty')->nullable();
            $table->string('media_url', 200)->nullable();
            $table->integer('item_id', false, true);
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('location', 100);
            $table->string('department', 100);
            $table->enum('type', ['Tangible', 'Intangible']);
            $table->enum('status', ['In Use', 'For Repair', 'Disposed'])->default('In Use');
            $table->integer('useful_life')->nullable();
            $table->decimal('depreciation_rate', 5, 2)->nullable();
            $table->date('registration_date')->nullable();
            $table->string('registration_id', 100)->nullable();
            $table->string('asset_id', 100)->nullable();
            $table->string('requestor', 100)->nullable();
            $table->string('approver', 100)->nullable();
            $table->string('assigned_personnel', 100)->nullable();
            $table->enum('asset_group', ['Current', 'Non-Current']);
            $table->string('supplier_vendor', 255)->nullable();

            $table->primary('item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};


