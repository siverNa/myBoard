<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
    
    // Eloquent 모델 이벤트를 등록함. 생성, 수정,삭제 시 시점에 자동 동작을 함 
    protected static function booted()
    {
        // postr가 삭제되기 직전에 이 코드를 실행하라는 뜻. 즉, delete 호출 시, 자동으로 실행됨.
        static::deleting(function ($post) {
            // 첨부파일 목록 확보
            $post->loadMissing('attachments');
            
            foreach ($post->attachments as $attachment) {
                // 파일이 있는 지 체크 후, 실제 storage 파일 제거
                if (!empty($attachment->file_path) && Storage::disk('public')->exists($attachment->file_path)) {
                    Storage::disk('public')->delete($attachment->file_path);
                }
            }
        });
    }

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

    public function attachments()
    {
        return $this->hasMany(PostAttachment::class, 'post_pk', 'pk');
    }
}
