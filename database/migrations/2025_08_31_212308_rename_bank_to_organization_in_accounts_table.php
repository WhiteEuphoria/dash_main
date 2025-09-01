<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            if (Schema::hasColumn('accounts', 'bank') && !Schema::hasColumn('accounts', 'organization')) {
                $table->renameColumn('bank', 'organization');
            }
        });
    }

    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            if (Schema::hasColumn('accounts', 'organization') && !Schema::hasColumn('accounts', 'bank')) {
                $table->renameColumn('organization', 'bank');
            }
        });
    }
};
