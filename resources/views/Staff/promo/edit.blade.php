@extends('templates.app')
@section('content')
<div class="container mt-5">
    <h5>Edit Promo</h5>
    <form action="{{ route('staff.promos.update', $promo->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Kode Promo</label>
            <input type="text" name="promo_code" class="form-control" value="{{ $promo->promo_code }}" required>
        </div>
        <div class="mb-3">
            <label>Tipe Diskon</label>
            <select name="type" class="form-control" required>
                <option value="percent" @if($promo->type == 'percent') selected @endif>Persen (%)</option>
                <option value="rupiah" @if($promo->type == 'rupiah') selected @endif>Rupiah (Rp)</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Jumlah Potongan</label>
            <input type="number" name="discount" class="form-control" value="{{ $promo->discount }}" required>
        </div>
        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
