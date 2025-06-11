<?php

declare(strict_types=1);

namespace App\Models;

use App\Domains\Location\Enum\Prefecture;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class UserProfile extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'first_name_kana',
        'last_name_kana',
        'phone',
        'post_code',
        'prefecture',
        'address1',
        'address2',
    ];

    protected function casts(): array
    {
        return [
            'prefecture' => Prefecture::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
