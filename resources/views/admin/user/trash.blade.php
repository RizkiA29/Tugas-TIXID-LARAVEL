{{-- filepath: resources/views/admin/user/trash.blade.php --}}
@extends('templates.app')
@section('content')
<div class="container my-5">
    <a href="{{ route('admin.users.index') }}" class="btn btn-primary mb-3">Kembali ke Daftar Pengguna</a>
    <h3 class="my-3">Data Sampah Pengguna</h3>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($usersTrash as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td class="d-flex">
                    {{-- Restore --}}
                    <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="me-2">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-success btn-sm">Kembalikan</button>
                    </form>
                    {{-- Hapus Permanen --}}
                    <form action="{{ route('admin.users.delete-permanent', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus permanen pengguna ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data sampah pengguna.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
