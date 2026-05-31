<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $fillable = [
        'registration_fee',
        'monthly_fee',
        'bank_name',
        'account_name',
        'account_number',
    ];

    public function logs()
    {   
        return $this->hasMany(FeeLog::class);
    }
}


