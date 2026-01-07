<?php

namespace App\Helpers;

use App\Models\Jurnal;

class JournalHelper
{
    /**
     * Buat jurnal double entry.
     */
    public static function doubleEntry(
        $tanggal,
        $noBukti,
        $refType,
        $refId,
        $debitAccount,
        $creditAccount,
        $nominal,
        $keterangan,
        $storeId,
        $kategoriId,
        $subKategoriId
    ) {
        // DEBIT
        Jurnal::create([
            'tanggal'    => $tanggal,
            'account_id' => $debitAccount->id,
            'ref_type'   => $refType,
            'ref_id'     => $refId,
            'no_bukti'   => $noBukti,
            'keterangan' => $keterangan,
            'debit'      => $nominal,
            'kredit'     => 0,
            'store_id'   => $storeId,
            'kategori_id'     => $kategoriId,
            'subkategori_id' => $subKategoriId,
        ]);

        // KREDIT
        Jurnal::create([
            'tanggal'    => $tanggal,
            'account_id' => $creditAccount->id,
            'ref_type'   => $refType,
            'ref_id'     => $refId,
            'no_bukti'   => $noBukti,
            'keterangan' => $keterangan,
            'debit'      => 0,
            'kredit'     => $nominal,
            'store_id'   => $storeId,
            'kategori_id'     => $kategoriId,
            'subkategori_id' => $subKategoriId,
        ]);
    }
}
