<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('business_legal_name')->nullable();
            $table->string('trade_name')->nullable();
            $table->string('registration_type', 50)->nullable();
            $table->string('registration_no', 20)->nullable();
            $table->date('date_of_registration')->nullable();
            $table->string('industry_code', 100)->nullable();
            $table->text('business_description')->nullable();
            $table->string('business_tin', 15)->nullable();
            $table->string('rdo_code', 3)->nullable();
            $table->text('official_address')->nullable();
            $table->string('zip_code', 4)->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->string('official_email', 100)->nullable();
            $table->string('sss_no', 20)->nullable();
            $table->string('phic_no', 20)->nullable();
            $table->string('hdmf_no', 20)->nullable();
            $table->string('peza_cert_no', 50)->nullable();
            $table->text('permits')->nullable();
            $table->tinyInteger('enable_multi_branch')->default(0);
            $table->string('inventory_tracking_mode', 20)->nullable();
            $table->tinyInteger('use_weighted_avg_cost')->default(0);
            $table->tinyInteger('enable_audit_trail')->default(0);
            $table->string('tax_type', 50)->nullable();
            $table->string('books_of_accounts')->nullable();
            $table->string('accounting_method', 20)->nullable();
            $table->string('fiscal_start_month', 20)->nullable();
            $table->string('quarter_cutoff', 50)->nullable();
            $table->tinyInteger('withholding_agent')->default(0);
            $table->string('currency', 10)->nullable();
            $table->string('timezone', 50)->nullable();
            $table->string('week_start', 10)->nullable();
            $table->string('date_format', 20)->nullable();
            $table->string('number_format', 20)->nullable();
            $table->string('or_prefix', 10)->nullable();
            $table->string('si_prefix', 10)->nullable();
            $table->integer('next_or_number')->nullable();
            $table->integer('next_si_number')->nullable();
            $table->string('pdf_template', 50)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('last_updated')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_settings');
    }
};


