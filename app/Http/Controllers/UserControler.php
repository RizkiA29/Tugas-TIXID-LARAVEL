<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;
use Yajra\DataTables\Facades\DataTables;

class UserControler extends Controller
{
    /**
     * Proses login user.
     */
    public function authentication(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $data = $request->only(['email', 'password']);
        if (Auth::attempt($data)) {
            $role = Auth::user()->role;
            if ($role == 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Berhasil login!');
            } elseif ($role == 'staff') {
                return redirect()->route('staff.dashboard')->with('success', 'Berhasil login!');
            } else {
                return redirect()->route('home')->with('success', 'Berhasil login!');
            }
        } else {
            return redirect()->route('login')->with('error', 'Gagal! Pastikan email dan password benar');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home')->with('logout', 'Anda telah logout! Silakan login kembali untuk akses lengkap.');
    }

    /**
     * Tampilkan form signup.
     */
    public function showSignupForm()
    {
        return view('auth.signup');
    }

    /**
     * Proses registrasi user baru.
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'email' => 'required|email|min:3|unique:users,email',
            'password' => 'required|min:8'
        ], [
            'first_name.required' => 'First name wajib diisi',
            'first_name.min' => 'First name minimal 3 karakter',
            'last_name.required' => 'Last name wajib diisi',
            'last_name.min' => 'Last name minimal 3 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email tidak valid',
            'email.min' => 'Email minimal 3 karakter',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
        ]);

        $createData = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

        if ($createData) {
            return redirect()->route('login')->with('success', "Berhasil membuat akun, silakan login!");
        } else {
            return redirect()->route('signup')->with('failed', 'Gagal memproses data, silakan coba lagi!');
        }
    }

    public function index()
    {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }

    public function datatables()
    {
        $users = User::select(['id', 'name', 'email', 'role', 'password']);
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('actions', function ($user) {
                $btnEdit = '<a href="' . route('admin.users.edit', ['id' => $user->id]) . '" class="btn btn-primary me-2">Edit</a>';
                $btnDelete = '<form action="' . route('admin.users.delete', ['id' => $user->id]) . '" method="POST" style="display:inline;">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button class="btn btn-danger" onclick="return confirm(\'Yakin ingin menghapus data ini?\')">Hapus</button>'
                    . '</form>';

                return '<div class="d-flex justify-content-center align-items-center gap-2">' . $btnEdit . ' ' . $btnDelete . '</div>';
            })
            ->rawColumns(['actions', 'name', 'email', 'role', 'password'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }

    // Menampilkan user yang sudah dihapus (soft deleted)
    public function trash()
    {
        $usersTrash = User::onlyTrashed()->get();
        return view('admin.user.trash', compact('usersTrash'));
    }

    // Mengembalikan user yang sudah dihapus (restore)
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dikembalikan!');
    }

    // Menghapus user secara permanen
    public function deletePermanent($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();
        return redirect()->back()->with('success', 'User berhasil dihapus secara permanen!');
    }
    public function export()
    {
        return Excel::download(new UserExport, 'data_user.xlsx');
    }
}
