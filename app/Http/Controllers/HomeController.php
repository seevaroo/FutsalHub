<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display field catalog with type filters.
     */
    public function index(Request $request)
    {
        $type = $request->query('type');

        $query = Field::query();

        if ($type && in_array($type, ['vinyl', 'sintetis', 'interlock'], true)) {
            $query->where('type', $type);
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

        return view('home', compact('fields', 'type', 'stats'));
    }
}

