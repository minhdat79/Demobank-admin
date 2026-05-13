<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            if (!Schema::hasColumn('jobs', 'slug')) {
                // Nếu chưa có cột slug thì thêm kèm unique luôn
                $table->string('slug')->unique();
            }
        });

      
        if (Schema::hasColumn('jobs', 'slug')) {
            $driver = DB::getDriverName();

            if ($driver === 'sqlite') {
                $indexes = collect(DB::select("PRAGMA index_list('jobs')"));
                $hasUnique = $indexes->contains(function ($row) {
                    
                    return (isset($row->name) && $row->name === 'jobs_slug_unique')
                        || (isset($row->unique) && (int)$row->unique === 1 && isset($row->name) && str_contains($row->name, 'slug'));
                });
                if (!$hasUnique) {
                    DB::statement('CREATE UNIQUE INDEX "jobs_slug_unique" ON "jobs" ("slug")');
                }
            } else {
              
                try {
                    Schema::table('jobs', function (Blueprint $table) {
                        $table->unique('slug', 'jobs_slug_unique');
                    });
                } catch (\Throwable $e) {
                  
                }
            }
        }
    }

    public function down(): void
    {
      
        Schema::table('jobs', function (Blueprint $table) {
            if (Schema::hasColumn('jobs', 'status'))       $table->dropColumn('status');
            if (Schema::hasColumn('jobs', 'banner_image')) $table->dropColumn('banner_image');
         
        });
    }
};
