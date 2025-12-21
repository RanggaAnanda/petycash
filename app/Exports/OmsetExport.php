<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OmsetExport implements FromCollection, WithHeadings
{
    protected $time, $toko;

    public function __construct($time, $toko)
    {
        $this->time = $time;
        $this->toko = $toko;
    }

    public function collection()
    {
        $data = collect([
            ['tanggal' => '12/12/2025', 'toko' => 'Planet Fashion Bandung', 'omset' => 1500000],
            ['tanggal' => '12/12/2025', 'toko' => 'Planet Fashion Jakarta', 'omset' => 2000000],
        ]);

        if ($this->toko !== 'all') {
            $data = $data->where('toko', $this->toko);
        }

        return $data->values();
    }

    public function headings(): array
    {
        return ['Tanggal', 'Toko', 'Omset'];
    }
}
