@extends('templates.app')

@section('content')
    <div class="w-75 d-block mx-auto my-5">
        <form action="{{ route('admin.movies.update', $movie->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row mb-3">
            <div class="col-6">
                <label for="title" class="form-label">Judul Film</label>
                <input type="text" name="title" class="form-control" id="title"  @error('title') is-invalid @enderror value="{{ $movie->title }}">
                @error('title')
<small class="text-danger">{{ $message }}</small>
                @enderror
                </div>
                <div class="col-6">
                    <label for="duration" class="form-label">Durasi Film</label>
                    <input type="time" name="duration" class="form-control" id="duration"  @error('duration') is-invalid @enderror value="{{ $movie->duration }}">
                    @error('duration')
<small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <label for="genre" class="form-label">Genre Film</label>
                    <input type="text" name="genre" class="form-control" id="genre"  @error('genre') is-invalid @enderror value="{{ $movie->genre }}">
                    @error('genre')
<small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            <div class="col-6">
                <label for="director" class="form-label">Sustradara</label>
                <input type="text" name="director" class="form-control" id="director"  @error('director') is-invalid @enderror value="{{ $movie->director }}">
                @error('director')
<small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <label for="age_rating" class="form-label">Usia Minimal</label>
                    <input type="text" name="age_rating" class="form-control" id="age_rating" @error('age_rating') is-invalid @enderror value="{{ $movie->age_rating }}">
                    @error('age_rating')
<small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            <div class="col-6">
                <label for="poster" class="form-label">Poster Film</label>
                <img src="{{ asset('storage/' . $movie->poster) }}" style="height: 150px; object-fit: cover;" class="d-block mx-auto mb-3"/>
                <input type="file" name="poster" class="form-control @error('poster') is-invalid @enderror" id="poster">
                @error('poster')
<small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Sinopsis</label>
                <textarea name="description" class="form-control" id="description" rows="5"  @error('description') is-invalid @enderror >{{ $movie->description }}</textarea>
                @error('description')
<small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Ubah Data</button>
        </form>
    </div>
@endsection
