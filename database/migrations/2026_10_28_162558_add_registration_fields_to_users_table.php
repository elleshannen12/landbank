<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Account verification
            $table->string('atm_account_no', 20)->nullable()->after('password');
            $table->string('atm_pin')->nullable();               // will store hashed value
            $table->string('jai', 5)->nullable();
            $table->boolean('enable_fund_transfer')->default(false);

            // Personal details
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('mother_first_name')->nullable();
            $table->string('mother_middle_name')->nullable();
            $table->string('mother_last_name')->nullable();
            $table->string('spouse_first_name')->nullable();
            $table->string('spouse_middle_name')->nullable();
            $table->string('spouse_last_name')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('civil_status', 30)->nullable();
            $table->date('birthday')->nullable();
            $table->string('tin', 20)->nullable();

            // Address
            $table->string('street')->nullable();
            $table->string('barangay')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('zip', 15)->nullable();
            $table->string('country', 100)->nullable();

            // Contact
            $table->string('alt_email')->nullable();
            $table->string('mobile_country', 10)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('home_tel', 20)->nullable();
            $table->string('office_tel', 20)->nullable();

            // Security questions (answers will be hashed)
            $table->string('sec_q1')->nullable();
            $table->string('sec_a1')->nullable();
            $table->string('sec_q2')->nullable();
            $table->string('sec_a2')->nullable();
            $table->string('sec_q3')->nullable();
            $table->string('sec_a3')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'atm_account_no', 'atm_pin', 'jai', 'enable_fund_transfer',
                'first_name', 'middle_name', 'last_name',
                'mother_first_name', 'mother_middle_name', 'mother_last_name',
                'spouse_first_name', 'spouse_middle_name', 'spouse_last_name',
                'gender', 'civil_status', 'birthday', 'tin',
                'street', 'barangay', 'city', 'province', 'zip', 'country',
                'alt_email', 'mobile_country', 'mobile', 'home_tel', 'office_tel',
                'sec_q1', 'sec_a1', 'sec_q2', 'sec_a2', 'sec_q3', 'sec_a3',
            ]);
        });
    }
};