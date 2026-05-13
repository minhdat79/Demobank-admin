<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        // a) Thêm cột (đều để nullable để tránh lỗi SQLite)
        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts','slug'))          { $table->string('slug')->nullable()->after('id'); }
            if (!Schema::hasColumn('posts','title'))         { $table->string('title')->nullable()->after('slug'); }
            if (!Schema::hasColumn('posts','thumbnail'))     { $table->string('thumbnail')->nullable()->after('title'); }
            if (!Schema::hasColumn('posts','excerpt'))       { $table->string('excerpt')->nullable()->after('thumbnail'); }
            if (!Schema::hasColumn('posts','content'))       { $table->longText('content')->nullable()->after('excerpt'); }
            if (!Schema::hasColumn('posts','author_name'))   { $table->string('author_name')->nullable()->after('content'); }
            if (!Schema::hasColumn('posts','category_name')) { $table->string('category_name')->nullable()->after('author_name'); }
            if (!Schema::hasColumn('posts','published_at'))  { $table->timestamp('published_at')->nullable()->after('category_name'); }
            if (!Schema::hasColumn('posts','status'))        { $table->string('status')->nullable()->after('published_at'); }
        });

        // b) Backfill giá trị cho các bản ghi cũ
        $rows = DB::table('posts')->select('id','slug','title','author_name','status','created_at')->get();
        foreach ($rows as $r) {
            $title = $r->title ?: ('Bài viết #'.$r->id);
            $slug  = $r->slug ?: Str::slug($title.'-'.$r->id);

            DB::table('posts')->where('id', $r->id)->update([
                'title'        => $title,
                'slug'         => $slug,
                'author_name'  => $r->author_name ?: 'Saigonbank',
                'status'       => $r->status ?: 'published',
                'published_at' => $r->created_at ?: now(),
            ]);
        }

        // c) Tạo unique index cho slug (sau khi backfill)
        //   Lưu ý: slug đang nullable, SQLite vẫn cho unique trên nhiều NULL.
        Schema::table('posts', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = collect($sm->listTableIndexes('posts'))->keys()->map('strtolower');
            if (!$indexes->contains('posts_slug_unique')) {
                $table->unique('slug');
            }
        });
    }

    public function down(): void
    {
        // rollback nhẹ (có thể chỉ drop index và một vài cột chính)
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts','slug'))          { $table->dropUnique('posts_slug_unique'); $table->dropColumn('slug'); }
            if (Schema::hasColumn('posts','title'))         { $table->dropColumn('title'); }
            if (Schema::hasColumn('posts','thumbnail'))     { $table->dropColumn('thumbnail'); }
            if (Schema::hasColumn('posts','excerpt'))       { $table->dropColumn('excerpt'); }
            if (Schema::hasColumn('posts','content'))       { $table->dropColumn('content'); }
            if (Schema::hasColumn('posts','author_name'))   { $table->dropColumn('author_name'); }
            if (Schema::hasColumn('posts','category_name')) { $table->dropColumn('category_name'); }
            if (Schema::hasColumn('posts','published_at'))  { $table->dropColumn('published_at'); }
            if (Schema::hasColumn('posts','status'))        { $table->dropColumn('status'); }
        });
    }
};


