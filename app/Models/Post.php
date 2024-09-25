<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'image',  'category_id', 'user_id', 'blog_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function blog() {
        return $this->belongsTo(Blog::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function bookmarks() {
        return $this->morphMany(Bookmark::class, 'bookmarkable');
    }

    public function comments() {
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

}
