<?php

namespace App\Http\Controllers\API;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;

class BarangController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $kode_barang = $request->input('kode_barang');
        $nama_barang = $request->input('nama_barang');
        $harga = $request->input('harga');
        $stok = $request->input('stok');

        if($id)
        {
            $barang = Barang::find($id);

            if($barang){
                return ResponseFormatter::success(
                    $barang,
                    'Data barang berhasil diambil'
                );

            
            }else{
                return ResponseFormatter::error(
                    null,
                    'Data barang tidak ditemukan',
                    404
                );
            }
        }

        $barang = Barang::all();

        if($kode_barang)
        {
            $barang->where('kode_barang', 'like', '%'.$kode_barang.'%');
        }

        if($nama_barang)
        {
            $barang->where('nama_barang', 'like', '%' . $nama_barang . '%');
        }

        if($stok)
        {
            $barang->where('stok', 'like', '%'.$stok.'%');
        }

        return ResponseFormatter::success(
            $barang,
            'Data barang berhasil diambil'
        );
    }
}
