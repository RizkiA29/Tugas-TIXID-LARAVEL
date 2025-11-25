{{-- filepath: resources/views/admin/cinema/trash.blade.php --}}
@extends('templates.app')
@section('content')
<div class="container my-5">
        <a href="{{ route('admin.cinemas.index') }}" class="btn btn-secondary mb-3">Kembali</a>
    <h3 class="my-3">Data Sampah Bioskop</h3>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Bioskop</th>
                <th>Lokasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($cinemasTrash as $index => $cinema)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $cinema->name }}</td>
                <td>{{ $cinema->location }}</td>
                <td class="d-flex">
                    {{-- Restore --}}
                    <form action="{{ route('admin.cinemas.restore', $cinema->id) }}" method="POST" class="me-2">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-success btn-sm">Kembalikan</button>
                    </form>
                    {{-- Hapus Permanen --}}
                    <form action="{{ route('admin.cinemas.delete-permanent', $cinema->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus permanen bioskop ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">Tidak ada data sampah bioskop.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
