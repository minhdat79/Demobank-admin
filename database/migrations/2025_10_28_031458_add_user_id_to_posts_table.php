<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'user_id')) {
                // nullable để không vướng dữ liệu cũ; SQLite ok
                $table->foreignId('user_id')->nullable()->after('status')
                      ->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'user_id')) {
                // Với SQLite cần dropForeign tên đúng, nhưng nullOnDelete + nullable nên có thể chỉ dropColumn
                $table->dropColumn('user_id');
            }
        });
    }
};
