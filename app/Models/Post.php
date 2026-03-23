<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $primaryKey = 'pk';
    
    public $incrementing = true;
    
    protected $fillable = [
        'channel_pk',
        'category_pk',
        'user_pk',
        'title',
        'content',
        'view_count',
        'is_hidden',
    ];
    
    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_pk', 'pk');
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_pk', 'pk');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_pk', 'pk');
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_pk', 'pk');
    }
}
