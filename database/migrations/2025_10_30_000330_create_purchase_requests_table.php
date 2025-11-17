<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->date('request_date');
            $table->string('request_id', 50);
            $table->string('requestor_name', 255);
            $table->string('requestor_location', 255);
            $table->string('requestor_department', 255);
            $table->string('asset_type', 100);
            $table->string('asset_category', 100);
            $table->string('asset_name', 255);
            $table->text('asset_description')->nullable();
            $table->integer('asset_quantity')->default(1);
            $table->decimal('estimated_cost', 12, 2)->nullable();
            $table->string('supplier_vendor', 255)->nullable();
            $table->string('status', 50)->default('Pending');
            $table->text('approver_comment')->nullable();
            $table->string('reviewed_by', 255)->nullable();
            $table->dateTime('reviewed_date')->nullable();
            $table->tinyInteger('archived')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_requests');
    }
};


