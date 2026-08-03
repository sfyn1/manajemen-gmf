<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingPageContentController extends Controller
{
    /**
     * Display all landing page content ordered by sort_order.
     */
    public function index()
    {
        $contents = LandingPageContent::orderBy('sort_order')->get();

        return view('admin.landing-content.index', compact('contents'));
    }

    /**
     * Store a new landing page content entry.
     */
    public function store(Request $request)
    {
        $request->validate([
            'section'    => 'required|in:hero,gallery,promo,info',
            'title'      => 'required|string|max:255',
            'body'       => 'nullable|string',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order' => 'required|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('landing', 'public');
        }

        LandingPageContent::create([
            'section'    => $request->section,
            'title'      => $request->title,
            'body'       => $request->body,
            'image_path' => $imagePath,
            'sort_order' => $request->sort_order,
            'is_active'  => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.landing-content.index')
            ->with('success', 'Konten berhasil ditambahkan.');
    }

    /**
     * Update an existing landing page content entry.
     */
    public function update(LandingPageContent $content, Request $request)
    {
        $request->validate([
            'section'    => 'required|in:hero,gallery,promo,info',
            'title'      => 'required|string|max:255',
            'body'       => 'nullable|string',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order' => 'required|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);

        $imagePath = $content->image_path;

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('landing', 'public');
        }

        $content->update([
            'section'    => $request->section,
            'title'      => $request->title,
            'body'       => $request->body,
            'image_path' => $imagePath,
            'sort_order' => $request->sort_order,
            'is_active'  => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.landing-content.index')
            ->with('success', 'Konten berhasil diperbarui.');
    }

    /**
     * Delete a landing page content entry and its associated image.
     */
    public function destroy(LandingPageContent $content)
    {
        if ($content->image_path && Storage::disk('public')->exists($content->image_path)) {
            Storage::disk('public')->delete($content->image_path);
        }

        $content->delete();

        return redirect()->route('admin.landing-content.index')
            ->with('success', 'Konten berhasil dihapus.');
    }

    /**
     * Reorder content items via AJAX – expects JSON body: { ids: [1, 3, 2, ...] }
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer|exists:landing_page_contents,id',
        ]);

        foreach ($request->ids as $order => $id) {
            LandingPageContent::where('id', $id)->update(['sort_order' => $order]);
        }

        return response()->json(['status' => 'ok']);
    }
}
