<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OnderdilRequest;
use App\Models\Onderdil;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OnderdilController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $onderdils = Onderdil::query()
            ->with('supplier')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder
                        ->where('kode_onderdil', 'like', "%{$search}%")
                        ->orWhere('nama_onderdil', 'like', "%{$search}%")
                        ->orWhere('jenis', 'like', "%{$search}%")
                        ->orWhereHas('supplier', fn ($supplier) => $supplier->where('nama_supplier', 'like', "%{$search}%"));
                });
            })
            ->orderBy('nama_onderdil')
            ->paginate(10)
            ->withQueryString();

        return view('admin.master-data.onderdil', compact('onderdils', 'search'));
    }

    public function create(): View
    {
        $suppliers = Supplier::orderBy('nama_supplier')->get();

        return view('admin.master-data.onderdil-form-page', [
            'onderdil' => null,
            'suppliers' => $suppliers,
        ]);
    }

    public function store(OnderdilRequest $request): RedirectResponse
    {
        Onderdil::create($request->validated());

        return redirect()
            ->route('admin.master-data.onderdil')
            ->with('success', 'Data onderdil berhasil ditambahkan.');
    }

    public function edit(Onderdil $onderdil): View
    {
        $suppliers = Supplier::orderBy('nama_supplier')->get();

        return view('admin.master-data.onderdil-form-page', compact('onderdil', 'suppliers'));
    }

    public function update(OnderdilRequest $request, Onderdil $onderdil): RedirectResponse
    {
        $onderdil->update($request->validated());

        return redirect()
            ->route('admin.master-data.onderdil')
            ->with('success', 'Data onderdil berhasil diperbarui.');
    }

    public function destroy(Onderdil $onderdil): RedirectResponse
    {
        $onderdil->delete();

        return redirect()
            ->route('admin.master-data.onderdil')
            ->with('success', 'Data onderdil berhasil dihapus.');
    }
}
