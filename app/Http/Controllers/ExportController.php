<?php

namespace App\Http\Controllers;

use App\Exports\OmsetExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportOmset(Request $request)
    {
        $type = $request->type; // excel | pdf
        $time = $request->time; // all | today | week | month
        $toko = $request->toko; // all | Planet Fashion ...

        // if ($type === 'pdf') {
        //     return $this->exportPdf($time, $toko);
        // }

        return Excel::download(
            new OmsetExport($time, $toko),
            'laporan-omset.xlsx'
        );
    }
}
