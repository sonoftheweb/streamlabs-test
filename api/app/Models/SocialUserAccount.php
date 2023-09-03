<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialUserAccount extends Model
{
    use HasFactory;

    protected $table = 'user_social_account';
    protected $guarded = [];


    /**
     * Relationship between SocialUserAccount and User model
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
