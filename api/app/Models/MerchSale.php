<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use App\Models\Traits\Eventable;
use Illuminate\Support\Facades\DB;

class MerchSale extends Model
{
    use HasFactory;
    use Eventable;

    protected $guarded = [];

    public static function getTotalMerchSales(int $days = 30): Collection|array
    {
        return Event::with(['eventable'])
            ->where('user_id', Auth::id())
            ->whereHas('eventable', function ($query) {
                $query->where('eventable_type', \App\Models\MerchSale::class);
            })
            ->whereDate('created_at', '>=', Date::now()->subDays($days))
            ->get();
    }

    public static function getTopItemsBySale(int $count, int $days = 30): Collection|array
    {
        return Event::with(['eventable'])
            ->select('events.*', DB::raw('(SELECT SUM(merch_sales.count * merch_sales.price) FROM merch_sales WHERE merch_sales.id = events.eventable_id) AS total_value'))
            ->where('user_id', Auth::id())
            ->whereHas('eventable', function ($query) {
                $query->where('eventable_type', \App\Models\MerchSale::class);
            })
            ->whereDate('created_at', '>=', Date::now()->subDays($days))
            ->orderByDesc('total_value')
            ->limit($count)
            ->get();

    }
}
