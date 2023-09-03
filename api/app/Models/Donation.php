<?php

namespace App\Models;

use App\Models\Traits\Eventable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class Donation extends Model
{
    use HasFactory;
    use Eventable;

    protected $guarded = [];

    /**
     * @param int $days
     * @return Collection|array
     */
    public static function getDonationsForUser(int $days = 30): Collection|array
    {
        return Event::with(['eventable'])
            ->where('user_id', Auth::id())
            ->whereHas('eventable', function ($query) {
                $query->where('eventable_type', \App\Models\Donation::class);
            })
            ->whereDate('created_at', '>=', Date::now()->subDays($days))
            ->get();
    }
}
