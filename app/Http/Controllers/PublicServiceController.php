<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Booking;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublicServiceController extends Controller
{
    /**
     * Services index — browse by category cards.
     */
    public function index()
    {
        $categories = Service::where('is_active', true)
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('public.services.index', compact('categories'));
    }

    /**
     * Services category page — show all items in a category.
     */
    public function show($category)
    {
        // Find the real category whose slug matches the URL exactly
        $realCategory = Service::where('is_active', true)
            ->get()
            ->pluck('category')
            ->unique()
            ->first(fn ($c) => \Illuminate\Support\Str::slug($c) === $category);

        abort_if(!$realCategory, 404);

        $dbCategory = $realCategory; // use the exact real category name from here on

        $services = Service::where('is_active', true)
            ->where('category', $realCategory)
            ->orderBy('name')
            ->get();

        // Preload completed booking counts in a single grouped query (no N+1)
        $completedCounts = Booking::select('service_id', DB::raw('count(*) as count'))
            ->where('status', Booking::STATUS_COMPLETED)
            ->whereIn('service_id', $services->pluck('id'))
            ->groupBy('service_id')
            ->pluck('count', 'service_id');
        foreach ($services as $service) {
            $service->completed_count = (int) ($completedCounts[$service->id] ?? 0);
        }

        $catalog = [
            'Tyres' => ['Continental', 'Michelin', 'Bridgestone', 'Goodyear', 'Pirelli', 'Dunlop'],
            'Maintenance' => ['Bosch', 'Castrol', 'Shell Helix', 'Mobil 1', 'Motul', 'Petronas'],
            'Tinting Films' => ['ClearShield', '3M', 'V-Kool', 'LLumar', 'Solar Gard', 'Ray-Ban Auto'],
            'Dashcams' => ['70mai', 'DDPAI', 'Thinkware', 'BlackVue', 'Garmin', 'Mio'],
            'Wipers' => ['Bosch', 'PIAA', 'Valeo', 'Michelin Wiper', 'Denso', 'Trico'],
            'Car Mats' => ['Trapo', '3M Mats', 'Dodomat', 'Carmat.my', 'Enzo', 'Maxpider'],
        ];

        foreach ($services as $service) {
            $rawCat = trim($service->category);
            $cat = 'Maintenance';
            foreach (array_keys($catalog) as $cName) {
                if (stripos($rawCat, $cName) !== false || stripos($cName, $rawCat) !== false) {
                    $cat = $cName;
                    break;
                }
            }
            if ($cat === 'Maintenance') {
                if (stripos($rawCat, 'tyre') !== false) $cat = 'Tyres';
                elseif (stripos($rawCat, 'wiper') !== false) $cat = 'Wipers';
                elseif (stripos($rawCat, 'film') !== false || stripos($rawCat, 'tint') !== false) $cat = 'Tinting Films';
                elseif (stripos($rawCat, 'cam') !== false) $cat = 'Dashcams';
                elseif (stripos($rawCat, 'mat') !== false) $cat = 'Car Mats';
            }
            $service->mapped_category = $cat;

            $brand = $catalog[$cat][0] ?? 'Generic';
            foreach ($catalog[$cat] ?? [] as $bName) {
                if (stripos($service->name, $bName) !== false || stripos($service->description ?? '', $bName) !== false) {
                    $brand = $bName;
                    break;
                }
            }
            $service->mapped_brand = $brand;

            $sub = '';
            if ($cat === 'Tyres') {
                $sub = $service->meta_data['tyre_size'] ?? '15"';
            } elseif ($cat === 'Tinting Films' || $cat === 'Car Mats') {
                $sub = $service->meta_data['vehicle_type'] ?? 'Sedan';
            } elseif ($cat === 'Maintenance') {
                $sub = $service->meta_data['service_type'] ?? 'Fully Synthetic';
            } elseif ($cat === 'Dashcams') {
                $sub = $service->meta_data['camera_type'] ?? 'Front Only';
            } elseif ($cat === 'Wipers') {
                $sub = $service->meta_data['wiper_type'] ?? 'Standard';
            }
            $service->mapped_sub = $sub;
        }

        $allCategories = ['Tyres', 'Maintenance', 'Tinting Films', 'Wipers', 'Dashcams', 'Car Mats'];
        $currentCatName = 'Maintenance';
        foreach ($allCategories as $acName) {
            if (stripos($dbCategory, $acName) !== false || stripos($acName, $dbCategory) !== false || stripos($acName, str_replace(' ', '', $dbCategory)) !== false) {
                $currentCatName = $acName;
                break;
            }
        }
        $brandsForCategory = $catalog[$currentCatName] ?? [];

        $title = ucwords($realCategory);

        return view('public.services.show', compact('services', 'title', 'category', 'currentCatName', 'brandsForCategory'));
    }

    /**
     * Global search — searches services by name, description, and category.
     */
    public function search(Request $request)
    {
        $query = trim($request->get('q', ''));

        $results = collect();
        $categories = collect();

        if (strlen($query) >= 2) {
            // Search in Service
            $results = Service::where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%')
                      ->orWhere('description', 'like', '%' . $query . '%')
                      ->orWhere('category', 'like', '%' . $query . '%');
                })
                ->orderBy('category')
                ->orderBy('name')
                ->get();

            // Also match top-level category names for category cards
            $allCategories = Service::where('is_active', true)
                ->select('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category');

            $categories = $allCategories->filter(function ($cat) use ($query) {
                return stripos($cat, $query) !== false;
            })->values();
        }

        return view('public.search.results', compact('results', 'categories', 'query'));
    }
}
