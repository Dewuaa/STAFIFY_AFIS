<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('request_date')->useCurrent();
            $table->string('assignment_request_id', 50);
            $table->string('requestor_name', 255);
            $table->string('location', 100);
            $table->string('department', 100);
            $table->string('asset_id', 50);
            $table->text('reason')->nullable();
            $table->date('proposed_return_date')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->text('approver_comment')->nullable();
            $table->string('reviewed_by', 255)->nullable();
            $table->dateTime('reviewed_date')->nullable();
            $table->string('assigned_to', 255)->nullable();
            $table->date('assignment_date')->nullable();
            $table->date('expected_return_date')->nullable();
            $table->enum('asset_condition_assignment', ['In use', 'Available', 'For repair', 'Disposed'])->nullable();
            $table->text('assignment_notes')->nullable();
            $table->string('returned_by', 255)->nullable();
            $table->date('returned_date')->nullable();
            $table->enum('return_condition', ['In use', 'Available', 'For repair', 'Disposed'])->nullable();
            $table->text('return_comment')->nullable();
            $table->string('user_returned_by', 100)->nullable();
            $table->date('user_returned_date')->nullable();
            $table->enum('user_return_condition', ['In use', 'Available', 'For repair', 'Disposed'])->nullable();
            $table->text('user_return_comment')->nullable();
            $table->tinyInteger('archived')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_assignments');
    }
};


