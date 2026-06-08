<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $suppliers = Supplier::query()
            ->withCount('onderdils')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder
                        ->where('nama_supplier', 'like', "%{$search}%")
                        ->orWhere('kontak', 'like', "%{$search}%")
                        ->orWhere('alamat', 'like', "%{$search}%");
                });
            })
            ->orderBy('nama_supplier')
            ->paginate(10)
            ->withQueryString();

        return view('admin.master-data.supplier', compact('suppliers', 'search'));
    }

    public function create(): View
    {
        return view('admin.master-data.supplier-form-page', [
            'supplier' => null,
        ]);
    }

    public function store(SupplierRequest $request): RedirectResponse
    {
        Supplier::create($request->validated());

        return redirect()
            ->route('admin.master-data.supplier')
            ->with('success', 'Data supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier): View
    {
        return view('admin.master-data.supplier-form-page', compact('supplier'));
    }

    public function update(SupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $supplier->update($request->validated());

        return redirect()
            ->route('admin.master-data.supplier')
            ->with('success', 'Data supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        if ($supplier->onderdils()->exists()) {
            return redirect()
                ->route('admin.master-data.supplier')
                ->with('error', 'Supplier tidak dapat dihapus karena masih digunakan oleh data onderdil.');
        }

        $supplier->delete();

        return redirect()
            ->route('admin.master-data.supplier')
            ->with('success', 'Data supplier berhasil dihapus.');
    }
}
