<?php

namespace Modules\Buku\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Buku\Http\Requests\BukuRequest;
use Modules\Buku\Models\Buku;

class BukuController extends Controller
{
    // Semua user login boleh melihat daftar + mencari + pagination
    public function index(Request $request)
    {
        $q = $request->query('q');

        $buku = Buku::query()
            ->when($q, function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('judul', 'like', "%{$q}%")
                      ->orWhere('penulis', 'like', "%{$q}%")
                      ->orWhere('penerbit', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(5)
            ->withQueryString(); // supaya kata kunci pencarian tidak hilang saat pindah halaman

        return view('buku::index', compact('buku', 'q'));
    }

    public function create()
    {
        return view('buku::create');
    }

    public function store(BukuRequest $request)
    {
        Buku::create($request->validated());

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Buku $buku)
    {
        return view('buku::edit', compact('buku'));
    }

    public function update(BukuRequest $request, Buku $buku)
    {
        $buku->update($request->validated());

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diubah.');
    }

    public function destroy(Buku $buku)
    {
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}
