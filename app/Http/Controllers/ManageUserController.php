<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User; // Panggil Model User

class ManageUserController extends Controller
{
    // 1. TAMPILKAN DAFTAR USER
    public function index()
    {
        // Ambil semua data user, urutkan dari yang terbaru
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // 2. FORM TAMBAH USER
    public function create()
    {
        $departments = User::departmentOptions();
        return view('admin.users.create', compact('departments'));
    }

    // 3. SIMPAN USER BARU
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,user',
            'department' => 'required|string|in:' . implode(',', User::departmentOptions()),
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password); // Enkripsi password
        $user->role = $request->role;
        $user->department = $request->department;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User baru berhasil ditambahkan!');
    }

    // 4. FORM EDIT USER
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $departments = User::departmentOptions();
        return view('admin.users.edit', compact('user', 'departments'));
    }

    // 5. UPDATE USER
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id, // Ignore email sendiri
            'department' => 'required|string|in:' . implode(',', User::departmentOptions()),
            'role' => 'required|in:admin,user',
            'password' => 'nullable|string|min:6', // Password boleh kosong kalau gak mau diganti
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->department = $request->department;
        $user->role = $request->role;

        // Cek apakah password diisi? Kalau iya, update password baru.
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui!');
    }

    // 6. HAPUS USER
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }
}
