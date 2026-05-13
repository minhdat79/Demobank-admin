<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Job extends Model
{
    protected $fillable = [
        'title','department','location','employment_type',
        'salary_min','salary_max','publish_date','close_date',
        'apply_url','summary','description',
        'seo_title','seo_description','is_published',
        'status','banner_image','slug',
    ];

    protected $casts = [
        'publish_date' => 'datetime',
        'close_date'   => 'datetime',
        'is_published' => 'boolean',
    ];

    // ===== Auto banner URL (giữ như cũ) =====
    public function getBannerUrlAttribute(): ?string
    {
        if (!$this->banner_image) return null;
        if (str_starts_with($this->banner_image,'http')) return $this->banner_image;
        return Storage::disk('public')->exists($this->banner_image)
            ? Storage::disk('public')->url($this->banner_image)
            : null;
    }

    // ===== Auto-generate slug ở tầng Model =====
    protected static function booted()
    {
        static::creating(function (Job $job) {
            if (empty($job->slug)) {
                $job->slug = static::makeUniqueSlug($job->title);
            }
        });

        static::updating(function (Job $job) {
            // Nếu đổi title và slug đang trống thì sinh lại
            if (( $job->isDirty('title') ) && empty($job->slug)) {
                $job->slug = static::makeUniqueSlug($job->title, $job->id);
            }
        });
    }

    /** Tạo slug duy nhất dựa trên title */
    public static function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        if ($base === '') $base = 'job';

        $slug = $base;
        $i = 1;

        while (
            static::when($ignoreId, fn($q)=>$q->where('id','!=',$ignoreId))
                  ->where('slug', $slug)->exists()
        ) {
            $slug = $base.'-'.$i;
            $i++;
        }
        return $slug;
    }
}
