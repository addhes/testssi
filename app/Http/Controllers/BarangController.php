<?php

namespace App\Http\Controllers;

use App\Exports\BarangExport;
use DataTables;
use App\Models\Barang;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        // count by id barang
        $last = Barang::max('id')+1;

        if ($request->ajax()) {
            $data = Barang::select('*')->orderBy('created_at','DESC');
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                           $btn = '<div class="row"><a href="javascript:void(0)" id="'.$row->id.'" class="btn btn-primary btn-sm ml-2 btn-edit">Edit</a>';
                           $btn .= '<a href="javascript:void(0)" id="'.$row->id.'" class="btn btn-danger btn-sm ml-2 btn-delete">Delete</a></div>';

                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('admin.barang.index', compact('last'));
    }

    public function store(Request $request)
    {
        Barang::create($request->all());
    }

    public function edit($id)
    {
        $barang = Barang::find($id);
        if($barang) {
            return response()->json([
                'status' => '200',
                'barang' => $barang
            ]);
        } else {
            return response()->json(['message' => 'Data tidak ditemukan']);
        }
    }

    public function update(Request $request, $id)
    {
        // Barang::create($request->all());
        $barang = Barang::find($id);
        $barang->nama_barang = $request->nama_barang;
        $barang->harga = $request->harga;
        $barang->stok = $request->stok;
        $barang->save();
        return response()->json(['message' => 'Data berhasil diubah']);
    }

    public function destroy($id)
    {
        Barang::find($id)->delete();
    }

    public function export() 
    {
        return Excel::download(new BarangExport, 'Barang.xlsx');
    }
}
