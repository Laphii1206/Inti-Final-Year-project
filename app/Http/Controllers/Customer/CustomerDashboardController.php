<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\DeleteAccountRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        return redirect()->route('bookings.index');
    }

    public function carsIndex()
    {
        $user = auth()->user();
        $cars = $user->cars()->latest()->get();
        return view('customer.cars.index', compact('user', 'cars'));
    }

    public function addCar(Request $request)
    {
        if ($request->has('car_plate')) {
            $request->merge([
                'car_plate' => strtoupper(str_replace([' ', '-'], '', $request->car_plate))
            ]);
        }

        $validated = $request->validate([
            'brand'     => 'required|string|max:255',
            'model'     => 'required|string|max:255',
            'year'      => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'car_plate' => ['required', 'string', 'max:20', Rule::unique('cars', 'car_plate')->whereNull('deleted_at')],
            'mileage'   => 'required|integer|min:0',
        ]);

        $isDefault = $request->has('is_default') && $request->is_default;
        if ($isDefault || auth()->user()->cars()->count() === 0) {
            auth()->user()->cars()->update(['is_default' => false]);
            $isDefault = true;
        }

        $car = auth()->user()->cars()->create([
            ...$validated,
            'is_default' => $isDefault,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'car' => $car, 'message' => __('messages.msg_vehicle_added')]);
        }

        return back()->with('success', __('messages.msg_vehicle_added'));
    }

    public function updateCar(Request $request, Car $car)
    {
        if ($car->user_id !== auth()->id()) {
            abort(403);
        }

        if ($request->has('car_plate')) {
            $request->merge([
                'car_plate' => strtoupper(str_replace([' ', '-'], '', $request->car_plate))
            ]);
        }

        $validated = $request->validate([
            'brand'     => 'required|string|max:255',
            'model'     => 'required|string|max:255',
            'year'      => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'car_plate' => ['required', 'string', 'max:20', Rule::unique('cars', 'car_plate')->whereNull('deleted_at')->ignore($car->id)],
            'mileage'   => 'required|integer|min:0',
        ]);

        $car->update($validated);

        if ($request->has('is_default') && $request->is_default) {
            auth()->user()->cars()->where('id', '!=', $car->id)->update(['is_default' => false]);
            $car->update(['is_default' => true]);
        }

        return back()->with('success', __('messages.msg_vehicle_updated'));
    }

    public function destroyCar(Car $car)
    {
        if ($car->user_id !== auth()->id()) {
            abort(403);
        }

        $wasDefault = $car->is_default;
        $car->delete();

        if ($wasDefault) {
            $nextCar = auth()->user()->cars()->latest()->first();
            if ($nextCar) {
                $nextCar->update(['is_default' => true]);
            }
        }

        return back()->with('success', __('messages.msg_vehicle_deleted'));
    }

    public function setDefaultCar(Car $car)
    {
        if ($car->user_id !== auth()->id()) {
            abort(403);
        }

        auth()->user()->cars()->where('id', '!=', $car->id)->update(['is_default' => false]);
        $car->update(['is_default' => true]);

        return back()->with('success', __('dashboard.cars_default_updated'));
    }

    public function deleteAccountRequest(Request $request)
    {
        $user = auth()->user();

        // Check if there is already a pending request
        $existing = DeleteAccountRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('error', __('messages.msg_del_req_pending'));
        }

        $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        DeleteAccountRequest::create([
            'user_id' => $user->id,
            'reason'  => $request->reason,
            'status'  => 'pending',
        ]);

        $user->notify(new \App\Notifications\AccountDeletionPending());

        return back()->with('success', __('messages.msg_del_req_submitted'));
    }
}
