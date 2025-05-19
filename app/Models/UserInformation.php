<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserInformation extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'phone_number',
        'bio',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
