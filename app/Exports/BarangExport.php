<?php

namespace App\Exports;

use App\Models\Barang;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class BarangExport implements FromCollection,WithHeadings,ShouldAutoSize,WithMapping
{
    public function collection()
    {
        return Barang::all();
    }

    public function map($row): array
	    {
	        return [
                $row->id => 
                $row->kode_barang,
                $row->nama_barang,
                $row->harga,
                $row->stok,
	            $row->created_at->format('d-m-Y'),
                $row->updated_at->format('d-m-Y')
	        ];
	    }

    public function headings(): array
    {
        return ["No", "Kode Barang", "Nama Barang", "Stok", "harga", "Tanggal Buat", "Tanggal Update"];
    }

}