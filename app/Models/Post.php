<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    protected $fillable = [
        'slug','title','excerpt','content',
        'thumbnail','image_path','author_name','category_name',
        'published_at','status','user_id',
    ];

    protected $casts = ['published_at' => 'datetime'];

    protected static function booted()
    {
        static::saving(function ($post) {
            if (empty($post->slug) && !empty($post->title)) {
                $base = Str::slug(Str::limit($post->title, 80, ''));
                $slug = $base ?: Str::random(8);
                $i = 1;
                while (static::where('slug', $slug)
                    ->when($post->id, fn($q)=>$q->where('id','<>',$post->id))
                    ->exists()) {
                    $slug = $base.'-'.$i++;
                }
                $post->slug = $slug;
            }

            $post->author_name  = $post->author_name ?: (auth()->user()->name ?? 'Saigonbank');
            $post->status       = $post->status ?: 'published';
            $post->published_at = $post->published_at ?: now();

            if (!empty($post->image_path)) {
                $post->image_path = str_replace('\\','/',$post->image_path);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCoverUrlAttribute(): string
    {
        if ($this->image_path) {
            $path = str_replace('\\','/',$this->image_path);
            if (Storage::disk('public')->exists($path)) {
                // Ưu tiên route media (khỏi cần symlink)
                if (app('router')->has('media')) {
                    return route('media', ['path' => $path]);
                }
                // Hoặc dùng symlink storage
                return Storage::url($path);
            }
        }
        return asset('images/placeholder-cover.jpg');
    }
}
