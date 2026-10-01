<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBranchController extends Controller
{
    public function index()
    {
        $branches = Branch::withTrashed()->paginate(20);
        return view('admin.branches.index', compact('branches'));
    }

    public function show(Branch $branch)
    {
        return view('admin.branches.show', compact('branch'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'contact_number'   => 'required|string|max:50',
            'address'          => 'required|string',
            'google_map_link'  => 'nullable|url',
            'opening_time'     => 'required|date_format:H:i',
            'closing_time'     => 'required|date_format:H:i|after:opening_time',
            'service_capacity' => 'required|integer|min:1',
            'image_path'       => 'nullable|image|max:5120',
            'is_active'        => 'boolean',
        ]);

        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('branches', 'public');
        }

        Branch::create($validated);
        return back()->with('success', 'Branch created successfully.');
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'contact_number'   => 'required|string|max:50',
            'address'          => 'required|string',
            'google_map_link'  => 'nullable|url',
            'opening_time'     => 'required|date_format:H:i',
            'closing_time'     => 'required|date_format:H:i',
            'service_capacity' => 'required|integer|min:1',
            'image_path'       => 'nullable|image|max:5120',
            'is_active'        => 'boolean',
        ]);

        if ($request->hasFile('image_path')) {
            if ($branch->image_path && !str_starts_with($branch->image_path, 'http')) {
                Storage::disk('public')->delete($branch->image_path);
            }
            $validated['image_path'] = $request->file('image_path')->store('branches', 'public');
        }

        $branch->update($validated);

        if (class_exists(ActivityLogger::class)) {
            ActivityLogger::admin($branch, 'branch_updated', auth()->user()->name . ' updated branch: ' . $branch->name);
        }

        return back()->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return back()->with('success', 'Branch removed.');
    }

    public function restore(int $id)
    {
        Branch::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Branch restored.');
    }
}