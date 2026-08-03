<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPackage;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = MembershipPackage::orderBy('price')->get();
        return view('admin.packages.index', compact('packages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'              => ['required', 'string', 'max:100'],
            'type'              => ['required', 'in:monthly_regular,monthly_student,daily'],
            'price'             => ['required', 'numeric', 'min:0'],
            'duration_days'     => ['required', 'integer', 'min:1'],
            'description'       => ['nullable', 'string'],
            'required_document' => ['required', 'in:ktp,ktm'],
            'is_active'         => ['boolean'],
        ]);

        MembershipPackage::create([...$data, 'is_active' => $request->boolean('is_active', true)]);

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil ditambahkan.');
    }

    public function update(MembershipPackage $package, Request $request)
    {
        $data = $request->validate([
            'name'              => ['required', 'string', 'max:100'],
            'type'              => ['required', 'in:monthly_regular,monthly_student,daily'],
            'price'             => ['required', 'numeric', 'min:0'],
            'duration_days'     => ['required', 'integer', 'min:1'],
            'description'       => ['nullable', 'string'],
            'required_document' => ['required', 'in:ktp,ktm'],
            'is_active'         => ['boolean'],
        ]);

        $package->update([...$data, 'is_active' => $request->boolean('is_active')]);

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(MembershipPackage $package)
    {
        if ($package->members()->where('status', 'active')->exists()) {
            return redirect()->back()->with('error', 'Paket masih digunakan oleh member aktif.');
        }
        $package->update(['is_active' => false]);
        return redirect()->route('admin.packages.index')->with('success', 'Paket dinonaktifkan.');
    }
}
