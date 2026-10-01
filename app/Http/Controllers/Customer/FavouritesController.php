<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\UserFavourite;
use App\Models\Service;
use Illuminate\Http\Request;

class FavouritesController extends Controller
{
    /**
     * Display the user's favourite services.
     */
    public function index()
    {
        $user = auth()->user();
        $favourites = $user->favouredServices()
            ->where('is_active', true)
            ->with('branch')
            ->latest('user_favourites.created_at')
            ->get();

        return view('customer.favourites.index', compact('favourites'));
    }

    /**
     * Toggle a service as favoured/unfavoured (AJAX).
     * Returns JSON: { favourited: bool, count: int }
     */
    public function toggle(Request $request, Service $service)
    {
        $user = auth()->user();

        $existing = UserFavourite::where('user_id', $user->id)
            ->where('service_id', $service->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $favourited = false;
        } else {
            UserFavourite::create([
                'user_id'    => $user->id,
                'service_id' => $service->id,
            ]);
            $favourited = true;
        }

        $count = $user->favourites()->count();

        if ($request->expectsJson()) {
            return response()->json([
                'favourited' => $favourited,
                'count'      => $count,
            ]);
        }

        return back()->with('success', $favourited
            ? __('messages.msg_fav_added') ?? 'Added to your favourites.'
            : __('messages.msg_fav_removed') ?? 'Removed from your favourites.'
        );
    }
}
