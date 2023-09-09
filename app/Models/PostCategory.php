<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PostCategory extends Pivot
{
    use HasFactory;

    protected $table = 'posts_categories';

    protected $guarded = [];

    public $timestamps = true;

    public function posts()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
