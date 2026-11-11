<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Pastikan user yang login boleh mengakses / mengubah data $user.
     * Superadmin boleh semua; user lain hanya boleh data dirinya sendiri.
     */
    private function authorizeUser(User $user): void
    {
        abort_unless(
            auth()->user()->isSuperadmin() || auth()->id() === $user->id,
            403,
            'Anda tidak memiliki hak untuk mengubah data ini.'
        );
    }

    /**
     * Tampilkan daftar user.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::query()
            // Non-superadmin hanya melihat data dirinya sendiri
            ->when(! $request->user()->isSuperadmin(), function ($query) use ($request) {
                $query->where('id', $request->user()->id);
            })
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderByRaw("FIELD(role, 'superadmin', 'admin') ASC")
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.user.index', compact('users', 'search'));
    }

    /**
     * Tampilkan form tambah user (hanya superadmin).
     */
    public function create()
    {
        $this->authorize('manage-users');

        return view('admin.user.create');
    }

    /**
     * Simpan user baru (hanya superadmin).
     */
    public function store(Request $request)
    {
        $this->authorize('manage-users');

        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(6)],
            'role'     => 'required|in:superadmin,admin',
        ]);

        User::create([
            'name'     => $validatedData['name'],
            'username' => $validatedData['username'],
            'email'    => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role'     => $validatedData['role'],
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

        $rules = [
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::min(6)],
        ];

        // Hanya superadmin yang boleh mengubah role
        if (auth()->user()->isSuperadmin()) {
            $rules['role'] = 'required|in:superadmin,admin';
        }

        $validatedData = $request->validate($rules);

        $user->name     = $validatedData['name'];
        $user->username = $validatedData['username'];
        $user->email    = $validatedData['email'];

        if (auth()->user()->isSuperadmin() && isset($validatedData['role'])) {
            $user->role = $validatedData['role'];
        }

        // Hanya ubah password jika diisi
        if (! empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Hapus user (hanya superadmin).
     */
    public function destroy(User $user)
    {
        $this->authorize('manage-users');

        // Superadmin tidak boleh menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('user.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
    }
}
