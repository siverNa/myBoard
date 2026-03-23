<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelRequest extends Model
{
    protected $primaryKey = 'pk';
    
    public $incrementing = true;
    
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
    
    public function applicant()
    {
        return $this->belongsTo(User::class, 'applicant_user_pk', 'pk');
    }
    
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_user_pk', 'pk');
    }
}
