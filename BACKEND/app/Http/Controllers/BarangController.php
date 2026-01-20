<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class BarangController extends Controller
{
    /**
     * Menampilkan daftar barang
     */
    public function index()
    {
        // Ambil semua barang, pagination 5 per halaman
        $barang = Barang::paginate(5);
        return view('barang.index', compact('barang'));
    }

    /**
     * Menampilkan form tambah barang
     */
    public function create()
    {
        return view('barang.create');
    }

    /**
     * Menyimpan data barang baru
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'deskripsi' => 'nullable|string',
        ]);

        Barang::create($request->all());

        return redirect()->route('barang.index')
                         ->with('message', 'Barang berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit barang
     */
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }

    /**
     * Memperbarui data barang
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'deskripsi' => 'nullable|string',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($request->all());

        return redirect()->route('barang.index')
                         ->with('message', 'Barang berhasil diperbarui!');
    }

    /**
     * Menghapus barang
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')
                         ->with('message', 'Barang berhasil dihapus!');
    }
}
