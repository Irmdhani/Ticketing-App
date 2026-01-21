<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentTypes = PaymentType::latest()->paginate(10);
        return view('admin.payment-types.index', compact('paymentTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.payment-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:payment_types,nama'
        ], [
            'nama.required' => 'Nama tipe pembayaran wajib diisi',
            'nama.unique' => 'Nama tipe pembayaran sudah ada',
            'nama.max' => 'Nama tipe pembayaran maksimal 255 karakter'
        ]);

        PaymentType::create($validated);

        return redirect()->route('admin.payment-types.index')
            ->with('success', 'Tipe pembayaran berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentType $paymentType)
    {
        return view('admin.payment-types.show', compact('paymentType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentType $paymentType)
    {
        return view('admin.payment-types.edit', compact('paymentType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentType $paymentType)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:payment_types,nama,' . $paymentType->id
        ], [
            'nama.required' => 'Nama tipe pembayaran wajib diisi',
            'nama.unique' => 'Nama tipe pembayaran sudah ada',
            'nama.max' => 'Nama tipe pembayaran maksimal 255 karakter'
        ]);

        $paymentType->update($validated);

        return redirect()->route('admin.payment-types.index')
            ->with('success', 'Tipe pembayaran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentType $paymentType)
    {
        try {
            $paymentType->delete();
            return redirect()->route('admin.payment-types.index')
                ->with('success', 'Tipe pembayaran berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.payment-types.index')
                ->with('error', 'Tipe pembayaran tidak dapat dihapus karena masih digunakan');
        }
    }
}
