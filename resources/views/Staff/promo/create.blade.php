@extends('templates.app')
@section('content')
<div class="container mt-5">
    <h5>Tambah Promo</h5>
    <form action="{{ route('staff.promos.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Kode Promo</label>
            <input type="text" name="promo_code" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Tipe Diskon</label>
            <select name="type" class="form-control" required>
                <option value="percent">Persen (%)</option>
                <option value="rupiah">Rupiah (Rp)</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Jumlah Potongan</label>
            <input type="number" name="discount" class="form-control" required>
        </div>
        <button class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
