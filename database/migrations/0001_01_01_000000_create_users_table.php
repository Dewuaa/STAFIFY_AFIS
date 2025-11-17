<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('user_name')->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('full_name');
            $table->string('user_email')->unique();
            $table->string('phone_number');
            $table->text('address');
            $table->string('country');
            $table->string('country_code', 2)->nullable();
            $table->string('user_pin')->nullable();
            $table->string('company')->nullable();
            $table->string('user_dept')->default('Unassigned');
            $table->string('user_position')->default('Unassigned');
            $table->string('user_password');
            $table->tinyInteger('is_archived')->default(0);
            $table->tinyInteger('access_level')->default(0); // 0=pending, 1=admin, 2=client, 3=user
            $table->string('profile_picture')->default('default.png');
            $table->string('temp_name')->nullable();
            $table->date('employment_date')->nullable();
            $table->string('branch_location')->nullable();
            $table->string('engagement_status')->nullable();
            $table->string('user_status')->default('Active');
            $table->string('user_type')->default('Employee');
            $table->string('wage_type')->nullable();
            $table->tinyInteger('sil_status')->default(0);
            $table->tinyInteger('statutory_benefits')->default(0);
            $table->string('drive_folder_id')->nullable();
            $table->text('drive_folder_link')->nullable();
            $table->tinyInteger('is_verified')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // Insert sample data
        DB::table('users')->insert([
            [
                'user_id' => 11,
                'user_name' => 'allenlim',
                'first_name' => 'Allen',
                'middle_name' => '',
                'last_name' => 'Lim',
                'full_name' => 'Allen Lim',
                'user_email' => 'allenlim@company.com',
                'phone_number' => '+1234567890',
                'address' => '123 Main Street',
                'country' => 'USA',
                'country_code' => 'US',
                'user_pin' => '1234',
                'company' => 'TechCorp',
                'user_dept' => 'Engineering',
                'user_position' => 'Developer',
                'user_password' => '$2y$10$sildf.fPOBYJ9Qf7W9nybOzY4WafTf1oIb9FHDTHn3I5DJ2k8cYxi',
                'is_archived' => 0,
                'access_level' => 1,
                'profile_picture' => 'default.png',
                'temp_name' => 'Allen',
                'employment_date' => '2024-01-01',
                'branch_location' => 'New York',
                'engagement_status' => 'Full-time',
                'user_status' => 'Active',
                'user_type' => 'Employee',
                'wage_type' => 'Salary',
                'sil_status' => 1,
                'statutory_benefits' => 1,
                'drive_folder_id' => null,
                'drive_folder_link' => null,
                'is_verified' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 12,
                'user_name' => 'amilmusa',
                'first_name' => 'Amil',
                'middle_name' => '',
                'last_name' => 'Musa',
                'full_name' => 'Amil Musa',
                'user_email' => 'amilmusa@company.com',
                'phone_number' => '+1987654321',
                'address' => '456 Oak Avenue',
                'country' => 'USA',
                'country_code' => 'US',
                'user_pin' => '5678',
                'company' => 'TechCorp',
                'user_dept' => 'Marketing',
                'user_position' => 'Manager',
                'user_password' => '$2y$10$sildf.fPOBYJ9Qf7W9nybOzY4WafTf1oIb9FHDTHn3I5DJ2k8cYxi',
                'is_archived' => 0,
                'access_level' => 2,
                'profile_picture' => 'default.png',
                'temp_name' => 'Amil',
                'employment_date' => '2024-01-01',
                'branch_location' => 'Los Angeles',
                'engagement_status' => 'Full-time',
                'user_status' => 'Active',
                'user_type' => 'Employee',
                'wage_type' => 'Salary',
                'sil_status' => 1,
                'statutory_benefits' => 1,
                'drive_folder_id' => null,
                'drive_folder_link' => null,
                'is_verified' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 13,
                'user_name' => 'juandelacruz',
                'first_name' => 'Juan',
                'middle_name' => '',
                'last_name' => 'Dela Cruz',
                'full_name' => 'Juan Dela Cruz',
                'user_email' => 'juandelacruz@company.com',
                'phone_number' => '+1122334455',
                'address' => '789 Pine Road',
                'country' => 'USA',
                'country_code' => 'US',
                'user_pin' => '9012',
                'company' => 'TechCorp',
                'user_dept' => 'HR',
                'user_position' => 'Director',
                'user_password' => '$2y$10$sildf.fPOBYJ9Qf7W9nybOzY4WafTf1oIb9FHDTHn3I5DJ2k8cYxi',
                'is_archived' => 0,
                'access_level' => 3,
                'profile_picture' => 'default.png',
                'temp_name' => 'Juan',
                'employment_date' => '2024-01-01',
                'branch_location' => 'Chicago',
                'engagement_status' => 'Full-time',
                'user_status' => 'Active',
                'user_type' => 'Employee',
                'wage_type' => 'Salary',
                'sil_status' => 1,
                'statutory_benefits' => 1,
                'drive_folder_id' => null,
                'drive_folder_link' => null,
                'is_verified' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
