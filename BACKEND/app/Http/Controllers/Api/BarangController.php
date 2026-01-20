<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;

class BarangController extends Controller
{
    /**
     * List semua barang
     */
    public function index()
    {
        $barang = Barang::all();
        return response()->json([
            'success' => true,
            'message' => 'List Data Barang',
            'data'    => $barang
        ], 200);
    }

    /**
     * Simpan barang baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
            'deskripsi'   => 'nullable|string',
        ]);

        $barang = Barang::create([
            'nama_barang' => $request->nama_barang,
            'harga'       => $request->harga,
            'stok'        => $request->stok,
            'deskripsi'   => $request->deskripsi
        ]);

        if ($barang) {
            return response()->json([
                'success' => true,
                'message' => 'Barang Created',
                'data'    => $barang
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Barang Failed to Save'
            ], 409);
        }
    }

    /**
     * Tampilkan detail barang
     */
    public function show($id)
    {
        $barang = Barang::find($id);

        if ($barang) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Data Barang',
                'data'    => $barang
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Barang Not Found'
            ], 404);
        }
    }

    /**
     * Update barang
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
            'deskripsi'   => 'nullable|string',
        ]);

        $barang = Barang::findOrFail($id);

        $barang->update([
            'nama_barang' => $request->nama_barang,
            'harga'       => $request->harga,
            'stok'        => $request->stok,
            'deskripsi'   => $request->deskripsi
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Barang Updated',
            'data'    => $barang
        ], 200);
    }

    /**
     * Hapus barang
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barang Deleted'
        ], 200);
    }
}
