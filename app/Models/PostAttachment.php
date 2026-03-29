<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostAttachment extends Model
{
    protected $primaryKey = 'pk';

    protected $fillable = [
        'post_pk',
        'original_name',
        'stored_name',
        'file_path',
        'file_extension',
        'mime_type',
        'file_size',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_pk', 'pk');
    }

    public function isImage()
    {
        return in_array(strtolower($this->file_extension), array(
            'jpg', 'jpeg', 'png', 'gif', 'webp',
        ));
    }
}
