<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelUserRole extends Model
{
    /**
     * 채널 생성자(소유자)
     * @var string
     */
    public const ROLE_OWNER = 'owner';
    
    /**
     * 채널 관리자
     * @var string
     */
    public const ROLE_MANAGER = 'manager';
    
    protected $primaryKey = 'pk';
    
    public $incrementing = true;
    
    protected $fillable = [
        'channel_pk',
        'user_pk',
        'role',
    ];
    
    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_pk', 'pk');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_pk', 'pk');
    }
}
