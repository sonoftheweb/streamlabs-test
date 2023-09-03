<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index(Request $request): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        // naturally I'd scope events to users by ID but got no time
        $pageNumber = (int)$request->get('page', 1);
        $perPage = 100;
        $totalPages = ceil(Event::query()->where('user_id', Auth::id())->count() / $perPage);

        if ($pageNumber > $totalPages) {
            return response([], 200);
        }

        $events = Event::with(['eventable'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $pageNumber);

        return response(EventResource::collection($events), 200);
    }

    public function toggleEventReadStatus(int $id): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        Event::query()
            ->where('id', $id)->update(['read' => DB::raw('NOT `read`')]);

        return response(null, 204);
    }
}
