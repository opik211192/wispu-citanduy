<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Pastikan user yang login boleh mengelola data $user.
     * Admin boleh semua; non-admin hanya boleh datanya sendiri.
     */
    private function authorizeUser(User $user): void
    {
        abort_unless(
            auth()->user()->can('manage-users') || auth()->id() === $user->id,
            403,
            'Anda hanya dapat mengubah data Anda sendiri.'
        );
    }

    /**
     * Tampilkan daftar user.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::query()
            // Non-admin hanya boleh melihat datanya sendiri
            ->when(! $request->user()->can('manage-users'), function ($query) use ($request) {
                $query->where('id', $request->user()->id);
            })
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.user.index', compact('users', 'search'));
    }

    /**
     * Tampilkan form tambah user.
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Simpan user baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        User::create([
            'name' => $validatedData['name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit user.
     */
    public function edit(User $user)
    {
        $this->authorizeUser($user);

        return view('admin.user.edit', compact('user'));
    }

    /**
     * Perbarui data user.
     */
    public function update(Request $request, User $user)
    {
        $this->authorizeUser($user);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::min(6)],
        ]);

        $user->name = $validatedData['name'];
        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];

        // Hanya ubah password jika diisi
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Hapus user.
     */
    public function destroy(User $user)
    {
        // Akun admin utama tidak boleh dihapus
        if ($user->username === 'admin') {
            return redirect()->route('user.index')->with('error', 'Akun admin tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
    }
}
