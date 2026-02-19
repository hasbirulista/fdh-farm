<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kandang;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('kandang')->get();
        return view('users.index', compact('users'), [
            'page' => 'User'
        ]);
    }

    public function create()
    {
        $kandangs = Kandang::all();
        return view('users.create', compact('kandangs'), [
            'page' => 'User'
        ]);
    }

    public function store(UserRequest $request)
    {
        User::create([
            'username'   => strtolower($request->username),
            'nama'       => $request->nama,
            'name'       => $request->nama,
            'no_hp'      => $request->no_hp,
            'role'       => $request->role,
            'kandang_id' => $request->kandang_id,
            'password'   => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        $kandangs = Kandang::all();
        return view('users.edit', compact('user', 'kandangs'), [
            'page' => 'User'
        ]);
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->only([
            'username',
            'nama',
            'no_hp',
            'role',
            'kandang_id'
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui');
    }


    public function destroy(User $user)
    {
        // ❌ Larang hapus user owner
        if ($user->role === 'owner') {
            return redirect()->route('users.index')
                ->with('error', 'User dengan role OWNER tidak boleh dihapus');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus');
    }
}
