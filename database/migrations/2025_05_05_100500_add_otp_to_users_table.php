<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom otp_code jika belum ada
            if (!Schema::hasColumn('users', 'otp_code')) {
                $table->string('otp_code', 6)->nullable()->after('password');
            }

            // Menambahkan kolom otp_sent_at jika belum ada
            if (!Schema::hasColumn('users', 'otp_sent_at')) {
                $table->timestamp('otp_sent_at')->nullable()->after('otp_code');
            }

            // Menambahkan kolom is_verified jika belum ada
            if (!Schema::hasColumn('users', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('otp_sent_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['otp_code', 'otp_sent_at', 'is_verified']);
        });
    }
};
