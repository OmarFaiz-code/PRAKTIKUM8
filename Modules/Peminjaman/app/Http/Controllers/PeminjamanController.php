<?php

namespace Modules\Peminjaman\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Peminjaman\Http\Requests\PeminjamanRequest;
use Modules\Peminjaman\Models\Peminjaman;

class PeminjamanController extends Controller
{
    // Halaman utama (hanya kerangka HTML; data diambil lewat AJAX)
    public function index()
    {
        return view('peminjaman::index');
    }

    // Endpoint JSON untuk mengisi tabel tanpa refresh
    public function data()
    {
        return response()->json(['data' => Peminjaman::latest()->get()]);
    }

    // Ambil satu data (untuk mengisi form edit)
    public function show(Peminjaman $peminjaman)
    {
        return response()->json(['data' => $peminjaman]);
    }

    public function store(PeminjamanRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('bukti')) {
            // simpan ke storage/app/public/bukti, yang disimpan di DB hanya path-nya
            $data['bukti'] = $request->file('bukti')->store('bukti', 'public');
        }

        $peminjaman = Peminjaman::create($data);

        return response()->json([
            'message' => 'Data peminjaman berhasil ditambahkan.',
            'data'    => $peminjaman,
        ], 201);
    }

    public function update(PeminjamanRequest $request, Peminjaman $peminjaman)
    {
        $data = $request->validated();

        if ($request->hasFile('bukti')) {
            // ganti file: hapus file lama dari storage dulu
            if ($peminjaman->bukti) {
                Storage::disk('public')->delete($peminjaman->bukti);
            }
            $data['bukti'] = $request->file('bukti')->store('bukti', 'public');
        } else {
            // tidak upload file baru: pertahankan file lama
            unset($data['bukti']);
        }

        $peminjaman->update($data);

        return response()->json([
            'message' => 'Data peminjaman berhasil diubah.',
            'data'    => $peminjaman->fresh(),
        ]);
    }

    public function destroy(Peminjaman $peminjaman)
    {
        // hapus file bukti dari storage ketika data dihapus
        if ($peminjaman->bukti) {
            Storage::disk('public')->delete($peminjaman->bukti);
        }

        $peminjaman->delete();

        return response()->json(['message' => 'Data peminjaman berhasil dihapus.']);
    }
}
