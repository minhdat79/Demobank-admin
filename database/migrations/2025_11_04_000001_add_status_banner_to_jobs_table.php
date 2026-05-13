<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('jobs', 'status')) {
                $table->string('status', 20)->default('open')->index();
            }
            if (!Schema::hasColumn('jobs', 'banner_image')) {
                $table->string('banner_image')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            if (Schema::hasColumn('jobs', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('jobs', 'banner_image')) {
                $table->dropColumn('banner_image');
            }
        });
    }
};
