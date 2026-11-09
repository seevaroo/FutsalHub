<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display home / arena overview page (facilities, court types, featured courts).
     */
    public function index()
    {
        $fields = Field::orderByRaw("FIELD(status, 'available', 'maintenance')")
            ->orderBy('name')
            ->get();

        $stats = [
            'total_fields' => Field::count(),
            'available_fields' => Field::where('status', 'available')->count(),
            'types_count' => [
                'vinyl' => Field::where('type', 'vinyl')->count(),
                'sintetis' => Field::where('type', 'sintetis')->count(),
                'interlock' => Field::where('type', 'interlock')->count(),
            ],
        ];

        return view('home', compact('fields', 'stats'));
    }

    /**
     * Display dedicated court catalog page with search and category filters.
     */
    public function catalog(Request $request)
    {
        $type = $request->query('type');
        $search = trim($request->query('search', ''));

        $query = Field::query();

        if ($type && in_array($type, ['vinyl', 'sintetis', 'interlock'], true)) {
            $query->where('type', $type);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        $fields = $query->orderByRaw("FIELD(status, 'available', 'maintenance')")
            ->orderBy('name')
            ->get();

        $stats = [
            'total_fields' => Field::count(),
            'available_fields' => Field::where('status', 'available')->count(),
            'types_count' => [
                'vinyl' => Field::where('type', 'vinyl')->count(),
                'sintetis' => Field::where('type', 'sintetis')->count(),
                'interlock' => Field::where('type', 'interlock')->count(),
            ],
        ];

        return view('catalog', compact('fields', 'type', 'search', 'stats'));
    }
}

