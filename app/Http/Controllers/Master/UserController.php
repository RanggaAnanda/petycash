<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * =========================
     * INDEX - LIST + FORM ADD USER
     * =========================
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 10;

        $query = User::with('store');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $users = $query->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $stores = Store::all(); // untuk dropdown form tambah/edit
        $roles  = ['admin', 'user', 'superadmin', 'repot']; // role

        return view('master.users.index', compact('users', 'stores', 'roles'));
    }

    /**
     * =========================
     * STORE - CREATE USER
     * =========================
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'store_id'  => 'required|exists:stores,id',
            'role'     => 'required|string',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'store_id' => $request->store_id,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('master.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function create()
    {
        $stores = Store::all();
        $roles = ['admin', 'user', 'superadmin', 'repot'];
        return view('master.users.create', compact('stores', 'roles'));
    }


    /**
     * =========================
     * EDIT - FORM EDIT USER (modal atau halaman sama index)
     * =========================
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $stores = Store::all();
        $roles  = ['admin', 'user', 'superadmin', 'repot'];

        return view('master.users.edit', compact('user', 'stores', 'roles'));
    }

    /**
     * =========================
     * UPDATE - EDIT USER
     * =========================
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'store_id'  => 'required|exists:stores,id',
            'role'     => 'required|string',
            'password' => 'nullable|min:6',
        ]);

        $data = [
            'name'     => $request->name,
            'email'    => $request->email,
            'store_id' => $request->toko_id,
            'role'     => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()
            ->route('master.users.index')
            ->with('success', 'User berhasil diperbarui');
    }

    /**
     * =========================
     * DESTROY - DELETE USER
     * =========================
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect()
            ->route('master.users.index')
            ->with('success', 'User berhasil dihapus');
    }
}
