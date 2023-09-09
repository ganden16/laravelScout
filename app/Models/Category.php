<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, HasFactory;

    protected $guarded = [];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'posts_categories', 'category_id', 'post_id')->using(PostCategory::class);
    }
}
