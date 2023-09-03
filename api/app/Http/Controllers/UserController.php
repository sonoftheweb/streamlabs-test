<?php

namespace App\Http\Controllers;

use AmrShawky\Currency;
use App\Models\Donation;
use App\Models\Follower;
use App\Models\MerchSale;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class UserController extends Controller
{
    public function getCurrentUser(): ?Authenticatable
    {
        /** @var User $localUser */
        return Auth::user();
    }

    /**
     * @param Request $request
     * @return Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
     */
    public function getCurrentUserDonationsRevenue(Request $request): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $days = $request->get('days', 30);

        try {
            $donations = Donation::getDonationsForUser($days);
            $donationRevenue = $this->computeDonationsRevenue($donations);
        } catch (Exception $e) {
            return response($e, 500);
        }

        return response($donationRevenue, 200);
    }

    /**
     * @param Request $request
     * @return Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
     */
    public function getCurrentUserMerchSales(Request $request): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $days = $request->get('days', 30);

        try {
            $merchSales = MerchSale::getTotalMerchSales($days);
            $merchRevenue = $this->computeMerchRevenue($merchSales);
        } catch (Exception $e) {
            return response($e, 500);
        }

        return response($merchRevenue, 200);
    }

    /**
     * @param Request $request
     * @return int
     */
    public function followersGained(Request $request): int
    {
        $days = $request->get('days', 30);

        return Follower::getUserFollowerCount($days);
    }

    public function getTopSaleItems(Request $request): Collection|array
    {
        $count = $request->get('count', 3);
        $days = $request->get('days', 30);

        $saleItems = MerchSale::getTopItemsBySale($count, $days);
        $items = [];

        foreach ($saleItems as $i) {
            $items[] = $i->eventable->item;
        }

        return $items;
    }

    private function computeMerchRevenue(Collection|array $merchSales): float|int
    {
        $revenue = 0;

        foreach ($merchSales as $merchSale) {
            $revenue += $merchSale->eventable->count * $merchSale->eventable->price;
        }

        return $revenue / 100;
    }

    /**
     * @throws Exception
     */
    private function computeDonationsRevenue(Collection|array $donations): float|int
    {
        $revenue = 0;

        $currencies = $donations->map(function ($d, $k) {
            return $d->eventable->currency;
        });

        $dates = $donations->sortBy('created_at')->map(function ($d, $k) {
            return Date::parse($d->created_at)->format('Y-m-d');
        });

        $rates = Currency::rates()
            ->timeSeries($dates->first(), $dates->last())
            ->symbols($currencies->toArray())
            ->base('USD')
            ->round(2)
            ->get();

        foreach ($donations as $donation) {
            $ratesByDonationDate = $rates[Date::parse($donation->created_at)->format('Y-m-d')];
            $rate = $ratesByDonationDate[$donation->eventable->currency];
            $revenue += ($donation->eventable->amount / 100) / $rate; //remember donation amount is in the lowest possible currency denomination.
        }

        return $revenue;
    }
}
