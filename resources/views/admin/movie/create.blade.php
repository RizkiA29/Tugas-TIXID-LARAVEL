@extends('templates.app')

@section('content')
    <div class="w-75 d-block mx-auto my-5">
        <form action="{{ route('admin.movies.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
            <div class="col-6">
                <label for="title" class="form-label">Judul Film</label>
                <input type="text" name="title" class="form-control" id="title"  @error('title') is-invalid @enderror">
                @error('title')
<small class="text-danger">{{ $message }}</small>
                @enderror
                </div>
                <div class="col-6">
                    <label for="duration" class="form-label">Durasi Film</label>
                    <input type="time" name="duration" class="form-control" id="duration"  @error('duration') is-invalid @enderror">
                    @error('duration')
<small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <label for="genre" class="form-label">Genre Film</label>
                    <input type="text" name="genre" class="form-control" id="genre"  @error('genre') is-invalid @enderror">
                    @error('genre')
<small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            <div class="col-6">
                <label for="director" class="form-label">Sustradara</label>
                <input type="text" name="director" class="form-control" id="director"  @error('director') is-invalid @enderror">
                @error('director')
<small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <label for="age_rating" class="form-label">Usia Minimal</label>
                    <input type="text" name="age_rating" class="form-control" id="age_rating" @error('age_rating') is-invalid @enderror">
                    @error('age_rating')
<small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            <div class="col-6">
                <label for="poster" class="form-label">Poster Film</label>
                <input type="file" name="poster" class="form-control @error('poster') is-invalid @enderror" id="poster">
                @error('poster')
<small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Sinopsis</label>
                <textarea name="description" class="form-control" id="description" rows="5"  @error('description') is-invalid @enderror"></textarea>
                @error('description')
<small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Kirim</button>
        </form>
    </div>
@endsection
