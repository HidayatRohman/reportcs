<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Produk;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksis = Transaksi::with('produk')->latest()->paginate(10);
        $produks = Produk::where('status', 'aktif')->get();
        
        return Inertia::render('Transaksi/Index', [
            'transaksis' => $transaksis,
            'produks' => $produks
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produks = Produk::where('status', 'aktif')->get();
        return Inertia::render('Transaksi/Create', [
            'produks' => $produks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_tlp' => 'required|string|max:20',
            'produk_id' => 'required|exists:produks,id',
            'chat' => 'required|in:Baru,Followup',
            'provinsi' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'transaksi' => 'required|numeric|min:0',
            'keterangan' => 'required|in:Aktif,Off Sementara,Otw Open,Tutup,Lainnya',
            'komentar' => 'nullable|string',
        ]);

        Transaksi::create($request->all());

        return redirect()->route('transaksi.index')
            ->with('message', 'Transaksi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        $transaksi->load('produk');
        return Inertia::render('Transaksi/Show', [
            'transaksi' => $transaksi
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        $produks = Produk::where('status', 'aktif')->get();
        $transaksi->load('produk');
        
        return Inertia::render('Transaksi/Edit', [
            'transaksi' => $transaksi,
            'produks' => $produks
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_tlp' => 'required|string|max:20',
            'produk_id' => 'required|exists:produks,id',
            'chat' => 'required|in:Baru,Followup',
            'provinsi' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'transaksi' => 'required|numeric|min:0',
            'keterangan' => 'required|in:Aktif,Off Sementara,Otw Open,Tutup,Lainnya',
            'komentar' => 'nullable|string',
        ]);

        $transaksi->update($request->all());

        return redirect()->route('transaksi.index')
            ->with('message', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();

        return redirect()->route('transaksi.index')
            ->with('message', 'Transaksi berhasil dihapus.');
    }
}
