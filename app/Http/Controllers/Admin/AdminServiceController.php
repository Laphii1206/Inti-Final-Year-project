<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with('branch');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $services = $query->latest()->paginate(10)->withQueryString();
        $categories = Service::select('category')->whereNotNull('category')->distinct()->orderBy('category')->pluck('category');

        return view('admin.services.index', compact('services', 'categories'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('admin.services.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id'          => 'required|exists:branches,id',
            'name'               => 'required|string|max:255',
            'description'        => 'nullable|string',
            'category'           => 'required|string|max:100',
            'price'              => 'required|numeric|min:0',
            'estimated_duration' => 'required|integer|min:0',
            'meta_data'          => 'nullable|array',
            'image_path'         => 'nullable|image|max:2048',
            'is_active'          => 'boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('services', 'public');
        }

        Service::create([
            ...$validated,
            'image_path' => $imagePath,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function show(Service $service)
    {
        return view('admin.services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        $branches = Branch::all();
        return view('admin.services.edit', compact('service', 'branches'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'branch_id'          => 'required|exists:branches,id',
            'name'               => 'required|string|max:255',
            'description'        => 'nullable|string',
            'category'           => 'required|string|max:100',
            'price'              => 'required|numeric|min:0',
            'estimated_duration' => 'required|integer|min:0',
            'meta_data'          => 'nullable|array',
            'image_path'         => 'nullable|image|max:2048',
            'is_active'          => 'boolean',
        ]);

        $imagePath = $service->image_path;
        if ($request->hasFile('image_path')) {
            if ($service->image_path) {
                Storage::disk('public')->delete($service->image_path);
            }
            $imagePath = $request->file('image_path')->store('services', 'public');
        }

        $service->update([
            ...$validated,
            'image_path' => $imagePath,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }

    public function forceDelete(Service $service)
    {
        $hasBookings = $service->bookings()->withTrashed()->exists();

        if ($hasBookings) {
            return back()->with('error', 'Cannot permanently delete a service that has booking history.');
        }

        if ($service->image_path) {
            Storage::disk('public')->delete($service->image_path);
        }

        $service->forceDelete();

        return redirect()->route('admin.services.index')->with('success', 'Service permanently deleted.');
    }
}
