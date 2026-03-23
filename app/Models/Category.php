<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = 'pk';
    
    public $incrementing = true;
    
    protected $fillable = [
        'channel_pk',
        'name',
        'sort_order',
    ];
    
    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_pk', 'pk');
    }
    
    public function posts()
    {
        return $this->hasMany(Post::class, 'category_pk', 'pk');
    }
}
