<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelRequest extends Model
{
    protected $primaryKey = 'pk';
    
    public $incrementing = true;
    
    public const STATUS_PENDING = 'pending';
    
    public const STATUS_APPROVED = 'approved';
    
    public const STATUS_REJECTED = 'rejected';
    
    protected $fillable = [
        'applicant_user_pk',
        'channel_name',
        'channel_description',
        'reason',
        'status',
        'reviewed_user_pk',
        'reviewed_at',
        'reject_reason',
    ];
    
    // 해당 필드를 날짜형으로 캐스팅
    protected $casts = [
        'reviewed_at' => 'datetime',
    ];
    
    public function applicant()
    {
        return $this->belongsTo(User::class, 'applicant_user_pk', 'pk');
    }
    
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_user_pk', 'pk');
    }
    
    public function getStatusLabel(): string
    {
        if ($this->status === self::STATUS_PENDING) {
            return '대기';
        }
        
        if ($this->status === self::STATUS_APPROVED) {
            return '승인';
        }
        
        if ($this->status === self::STATUS_REJECTED) {
            return '반려';
        }
        
        return $this->status;
    }
}
