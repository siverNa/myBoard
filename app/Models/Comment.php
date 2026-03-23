<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $primaryKey = 'pk';
    
    public $incrementing = true;
    
    protected $fillable = [
        'post_pk',
        'user_pk',
        'content',
    ];
    
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_pk', 'pk');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_pk', 'pk');
    }
}
