{{-- filepath: resources/views/admin/movie/trash.blade.php --}}
@extends('templates.app')
@section('content')
<div class="container my-5">
    <a href="{{ route('admin.movies.index') }}" class="btn btn-primary mb-3">Kembali ke Daftar Film</a>
    <h3 class="my-3">Data Sampah Film</h3>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Judul Film</th>
                <th>Genre</th>
                <th>Durasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($moviesTrash as $index => $movie)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $movie->title }}</td>
                <td>{{ $movie->genre }}</td>
                <td>{{ $movie->duration }}</td>
                <td class="d-flex">
                    {{-- Restore --}}
                    <form action="{{ route('admin.movies.restore', $movie->id) }}" method="POST" class="me-2">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-success btn-sm">Kembalikan</button>
                    </form>
                    {{-- Hapus Permanen --}}
                    <form action="{{ route('admin.movies.delete-permanent', $movie->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus permanen film ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data sampah film.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
