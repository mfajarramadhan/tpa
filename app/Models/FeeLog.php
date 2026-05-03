<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeLog extends Model
{
    protected $fillable = [
        'user_id',
        'fee_id',
        'old_registration_fee',
        'new_registration_fee',
        'old_monthly_fee',
        'new_monthly_fee',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }
}
