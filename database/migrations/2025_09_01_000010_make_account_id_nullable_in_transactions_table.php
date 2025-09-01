<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Make account_id nullable to allow transactions without a linked account
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('account_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Revert to NOT NULL (may fail if nulls exist)
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('account_id')->nullable(false)->change();
        });
    }
};

