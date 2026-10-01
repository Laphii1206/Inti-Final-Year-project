<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class AdminCarController extends Controller
{
    public function index()
    {
        $cars = Car::with('user')->withTrashed()->paginate(25);
        return view('admin.cars.index', compact('cars'));
    }

    public function store(Request $request)
    {
        if ($request->has('car_plate')) {
            $request->merge([
                'car_plate' => strtoupper(str_replace([' ', '-'], '', $request->car_plate))
            ]);
        }

        $validated = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'brand'     => 'required|string|max:100',
            'model'     => 'required|string|max:100',
            'year'      => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'car_plate' => 'required|string|unique:cars,car_plate',
            'mileage'   => 'nullable|integer|min:0',
            'is_default'=> 'boolean',
        ]);

        Car::create($validated);
        return back()->with('success', 'Customer car added successfully.');
    }

    public function update(Request $request, Car $car)
    {
        if ($request->has('car_plate')) {
            $request->merge([
                'car_plate' => strtoupper(str_replace([' ', '-'], '', $request->car_plate))
            ]);
        }

        $validated = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'brand'     => 'required|string|max:100',
            'model'     => 'required|string|max:100',
            'year'      => 'required|integer|min:1900',
            'car_plate' => 'required|string|unique:cars,car_plate,' . $car->id,
            'mileage'   => 'nullable|integer|min:0',
            'is_default'=> 'boolean',
        ]);

        $car->update($validated);
        return back()->with('success', 'Car details updated.');
    }

    public function destroy(Car $car)
    {
        $car->delete();
        return back()->with('success', 'Car removed.');
    }

    public function restore(int $id)
    {
        Car::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Car restored.');
    }
}