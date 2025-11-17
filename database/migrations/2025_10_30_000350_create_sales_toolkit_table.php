<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_toolkit', function (Blueprint $table) {
            $table->increments('sales_id');
            $table->integer('user_id');
            $table->string('sales_title');
            $table->text('form_url')->nullable();
            $table->text('response_url')->nullable();
            $table->string('icon')->default('communication.gif');
            $table->enum('type', ['Form+Sheet', 'Form', 'Sheet', 'Video', 'Slides', 'Folder'])->default('Form+Sheet');
            $table->tinyInteger('is_approved')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('user_id', 'user_id_index');
            $table->index('is_approved', 'is_approved_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_toolkit');
    }
};


