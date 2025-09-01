<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Normalize legacy Russian statuses to English
        DB::table('withdrawals')->where('status', 'В обработке')->update(['status' => 'pending']);
        DB::table('withdrawals')->where('status', 'Выполнено')->update(['status' => 'approved']);
        DB::table('withdrawals')->where('status', 'approve')->update(['status' => 'approved']);
        DB::table('withdrawals')->where('status', 'Отклонено')->update(['status' => 'rejected']);
    }

    public function down(): void
    {
        // Best-effort reverse for the common cases
        DB::table('withdrawals')->where('status', 'pending')->update(['status' => 'В обработке']);
        DB::table('withdrawals')->where('status', 'approved')->update(['status' => 'Выполнено']);
        DB::table('withdrawals')->where('status', 'rejected')->update(['status' => 'Отклонено']);
    }
};

