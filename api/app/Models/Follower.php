<?php

namespace App\Models;

use App\Models\Traits\Eventable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class Follower extends Model
{
    use HasFactory;
    use Eventable;

    protected $guarded = [];

    public static function getUserFollowerCount(mixed $days): int
    {
        return Event::with(['eventable'])
            ->where('user_id', Auth::id())
            ->whereHas('eventable', function ($query) {
                $query->where('eventable_type', \App\Models\Follower::class);
            })
            ->whereDate('created_at', '>=', Date::now()->subDays($days))
            ->count();
    }
}
