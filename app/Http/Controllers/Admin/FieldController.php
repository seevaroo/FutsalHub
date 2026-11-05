<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FieldController extends Controller
{
    /**
     * Display a listing of futsal fields.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type');
        $status = $request->query('status');

        $query = Field::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($type && in_array($type, ['vinyl', 'sintetis', 'interlock'], true)) {
            $query->where('type', $type);
        }

        if ($status && in_array($status, ['available', 'maintenance'], true)) {
            $query->where('status', $status);
        }

        $fields = $query->latest()->paginate(10);

        return view('admin.fields.index', compact('fields', 'search', 'type', 'status'));
    }

    /**
     * Show the form for creating a new field.
     */
    public function create()
    {
        return view('admin.fields.create');
    }

    /**
     * Store a newly created field in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:vinyl,sintetis,interlock'],
            'price_per_hour' => ['required', 'numeric', 'min:10000'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:available,maintenance'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'name.required' => 'Nama lapangan wajib diisi.',
            'type.required' => 'Tipe lapangan wajib dipilih.',
            'price_per_hour.required' => 'Tarif sewa per jam wajib diisi.',
            'price_per_hour.min' => 'Tarif sewa per jam minimal Rp 10.000.',
            'status.required' => 'Status lapangan wajib dipilih.',
            'image.image' => 'File harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('fields', 'public');
        }

        Field::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'price_per_hour' => $validated['price_per_hour'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.fields.index')
            ->with('success', 'Data lapangan baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified field.
     */
    public function edit(Field $field)
    {
        return view('admin.fields.edit', compact('field'));
    }

    /**
     * Update the specified field in database.
     */
    public function update(Request $request, Field $field)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:vinyl,sintetis,interlock'],
            'price_per_hour' => ['required', 'numeric', 'min:10000'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:available,maintenance'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'name.required' => 'Nama lapangan wajib diisi.',
            'type.required' => 'Tipe lapangan wajib dipilih.',
            'price_per_hour.required' => 'Tarif per jam wajib diisi.',
            'status.required' => 'Status lapangan wajib dipilih.',
            'image.image' => 'File harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $imagePath = $field->image;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($field->image && Storage::disk('public')->exists($field->image)) {
                Storage::disk('public')->delete($field->image);
            }
            $imagePath = $request->file('image')->store('fields', 'public');
        }

        $field->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'price_per_hour' => $validated['price_per_hour'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.fields.index')
            ->with('success', "Data lapangan '{$field->name}' berhasil diperbarui.");
    }

    /**
     * Remove the specified field from database.
     */
    public function destroy(Field $field)
    {
        $name = $field->name;

        // Check if there are active bookings
        $hasActiveBookings = $field->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($hasActiveBookings) {
            return back()->with('error', "Lapangan '{$name}' tidak dapat dihapus karena masih memiliki jadwal booking aktif.");
        }

        if ($field->image && Storage::disk('public')->exists($field->image)) {
            Storage::disk('public')->delete($field->image);
        }

        $field->delete();

        return redirect()->route('admin.fields.index')
            ->with('success', "Lapangan '{$name}' berhasil dihapus.");
    }
}

