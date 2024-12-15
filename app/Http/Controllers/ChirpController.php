<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use App\Models\Chirp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Chirps/Index', [
            'chirps' => Chirp::with('user:id,name')->latest()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk file gambar
        ]);

        // Simpan gambar jika ada
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('chirps', 'public');
        }

        // Simpan data chirp
        $request->user()->chirps()->create($validated);

        // Feedback kepada pengguna
        session()->flash('success', 'Chirp created successfully.');

        return redirect(route('chirps.index'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp): RedirectResponse
    {
        Gate::authorize('update', $chirp);

        $validated = $request->validate([
            'message' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Hapus gambar lama jika ada gambar baru
        if ($request->hasFile('image')) {
            if ($chirp->image) {
                Storage::disk('public')->delete($chirp->image);
            }

            $validated['image'] = $request->file('image')->store('chirps', 'public');
        }

        $chirp->update($validated);

        session()->flash('success', 'Chirp updated successfully.');

        return redirect(route('chirps.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp): RedirectResponse
    {
        Gate::authorize('delete', $chirp);

        // Hapus gambar jika ada
        if ($chirp->image) {
            Storage::disk('public')->delete($chirp->image);
        }

        $chirp->delete();

        session()->flash('success', 'Chirp deleted successfully.');

        return redirect(route('chirps.index'));
    }
}
