<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $accounts = Account::paginate(10);

        $editAccount = null;
        if ($request->has('edit')) {
            $editAccount = Account::find($request->edit);
        }

        return view('master.account.index', compact('accounts', 'editAccount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_akun' => 'required|unique:accounts,kode_akun',
            'nama_akun' => 'required|string',
            'jenis_akun' => 'required|in:Aset,Kewajiban,Modal,Pendapatan,Beban',
            'normal_balance' => 'required|in:Debit,Kredit',
            'parent_id' => 'nullable|exists:accounts,id',
        ]);

        Account::create($request->all());

        return redirect()->route('master.accounts.index')->with('success', 'Akun berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $request->validate([
            'kode_akun' => 'required|unique:accounts,kode_akun,' . $account->id,
            'nama_akun' => 'required|string',
            'jenis_akun' => 'required|in:Aset,Kewajiban,Modal,Pendapatan,Beban',
            'normal_balance' => 'required|in:Debit,Kredit',
            'parent_id' => 'nullable|exists:accounts,id',
        ]);

        $account->update($request->all());

        return redirect()->route('master.accounts.index')->with('success', 'Akun berhasil diupdate');
    }

    public function edit($id)
    {
        $editAccount = Account::findOrFail($id);
        $accounts = Account::paginate(10); // untuk tabel

        return view('master.account.index', compact('accounts', 'editAccount'));
    }

    public function destroy($id)
    {
        Account::findOrFail($id)->delete();
        return redirect()->route('master.accounts.index')->with('success', 'Akun berhasil dihapus');
    }
}
