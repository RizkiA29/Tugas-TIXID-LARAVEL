{{-- filepath: resources/views/staff/promo/trash.blade.php --}}
@extends('templates.app')
@section('content')
<div class="container my-5">
        <a href="{{ route('staff.promos.index') }}" class="btn btn-secondary mb-3">Kembali</a>
    <h3 class="my-3">Data Sampah Promo</h3>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Judul Promo</th>
                <th>Tipe</th>
                <th>Jumlah Potongan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($promosTrash as $index => $promo)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $promo->promo_code }}</td>
                <td>{{ $promo->type }}</td>
                <td>
                    @if($promo->type == 'percent')
                        {{ $promo->discount }}%
                    @else
                        Rp. {{ number_format($promo->discount, 0, ',', '.') }}
                    @endif
                </td>
                <td class="d-flex">
                    {{-- Restore --}}
                    <form action="{{ route('staff.promos.restore', $promo->id) }}" method="POST" class="me-2">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-success btn-sm">Kembalikan</button>
                    </form>
                    {{-- Hapus Permanen --}}
                    <form action="{{ route('staff.promos.delete-permanent', $promo->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus permanen promo ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data sampah promo.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
