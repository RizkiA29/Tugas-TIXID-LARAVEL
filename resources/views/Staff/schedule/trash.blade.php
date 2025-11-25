@extends('templates.app')
@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-end">
            <a href="{{ route('staff.schedules.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <h3 class="my-3">Data Sampah Jadwal Tayangan</h3>
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Bioskop</th>
                    <th>Judul Film</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($schedulesTrash as $key => $schedule)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $schedule->cinema->name }}</td>
                    <td>{{ $schedule->movie->title }}</td>
                   <td class="d-flex">
    {{-- Restore --}}
    <form action="{{ route('staff.schedules.restore', $schedule->id)}}" method="POST">
        @csrf
        @method('PATCH')
        <button class="btn btn-success btn-sm ms-2">Kembalikan</button>
    </form>
    {{-- Hapus Permanen --}}
    <form action="{{ route('staff.schedules.delete-permanent', $schedule->id)}}" method="POST">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger btn-sm ms-2">Hapus</button>
    </form>
</td>
              </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
