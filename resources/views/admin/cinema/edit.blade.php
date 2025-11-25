@extends('templates.app')

@section('content')
<div class="w-75 d-block mx-auto my-5 p-4">
<form method="POST" action="{{ route('admin.cinemas.update', ['id' => $cinema->id]) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Nama Bioskop</label>
        <input type="text" class="form-control" id="name" name="name" @error('name') is-invalid @enderror value="{{ $cinema->name }}">
        @error('name')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="mb-3">
        <label for="location" class="form-label">Lokasi </label>
        <textarea class="form-control" id="location" name="location" rows="5" @error('location') is-invalid @enderror>{{ $cinema->location }}</textarea>
        @error('location')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary">Kirim</button>
</form>
</div>
@endsection
