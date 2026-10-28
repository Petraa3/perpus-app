<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        $bukus = Buku::orderBy('judul', 'asc')->get();
        return view('index', [
            'bukus' => $bukus,
            'totalStok' => $bukus->sum('stok')
        ]);

    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'stok' => 'required|integer|min:0',
        ]);

        Buku::create($validatedData);

        return redirect('/buku')->with('success', 'Buku berhasil ditambahkan!');
    }
}
