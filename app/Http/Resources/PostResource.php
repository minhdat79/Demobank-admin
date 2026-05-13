<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'slug'         => $this->slug,
            'title'        => $this->title,
            'excerpt'      => $this->excerpt,
            'content'      => $this->content,
            'author'       => $this->author_name ?? optional($this->user)->name,
            'status'       => $this->status,
            'published_at' => optional($this->published_at)->toIso8601String(),
            'cover_url'    => $this->cover_url, // URL tuyệt đối
        ];
    }
}
