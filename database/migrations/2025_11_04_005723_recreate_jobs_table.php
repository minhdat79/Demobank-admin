<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
  
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('jobs');
        Schema::enableForeignKeyConstraints();

        Schema::create('jobs', function (Blueprint $t) {
            $t->id();

      
            $t->string('title');                    
            $t->string('slug')->unique();

            $t->string('department')->nullable();     
            $t->string('location')->nullable();       
            $t->string('employment_type')->nullable(); 

            $t->unsignedInteger('salary_min')->nullable();
            $t->unsignedInteger('salary_max')->nullable();

            $t->string('apply_url')->nullable();

            $t->boolean('is_published')->default(true)->index();
            $t->date('publish_date')->nullable()->index();
            $t->date('close_date')->nullable()->index();

            $t->longText('summary')->nullable();
            $t->longText('description')->nullable();

        
            $t->string('seo_title')->nullable();
            $t->string('seo_description', 300)->nullable();

            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
