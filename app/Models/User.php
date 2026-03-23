<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $primaryKey = 'pk';
    
    public $incrementing = true;
    
    protected $keyType = 'int';
    
    protected $fillable = [
        'login_id',
        'email',
        'password',
        'global_role',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    // 내가 쓴 게시글
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_pk', 'pk');
    }
    
    // 내가 쓴 댓글
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_pk', 'pk');
    }
    
    // 내가 생성한 채널
    public function channels()
    {
        return $this->hasMany(Channel::class, 'created_user_pk', 'pk');
    }
}
