<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $primaryKey = 'pk';
    
    public $incrementing = true;
    
    protected $fillable = [
        'name',
        'description',
        'status',
        'created_user_pk',
    ];
    
    // 채널 생성자
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_user_pk', 'pk');
    }
    
    // 채널의 게시글
    public function posts()
    {
        return $this->hasMany(Post::class, 'channel_pk', 'pk');
    }
    
    // 채널의 카테고리
    public function categories()
    {
        return $this->hasMany(Category::class, 'channel_pk', 'pk');
    }
}
