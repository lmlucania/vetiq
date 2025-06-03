<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'first_name_kana',
        'last_name_kana',
        'phone_number',
        'post_code',
        'prefecture',
        'address1',
        'address2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
