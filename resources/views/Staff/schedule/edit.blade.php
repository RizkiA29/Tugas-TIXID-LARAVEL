@extends('templates.app')
@section('content')
<div class="container my-5">
    <form method="POST" action="{{ route('staff.schedules.update', $schedule->id) }}">
        @csrf
        @method('PATCH')
        <div class="modal-body">
            <div class="mb-3">
                <label for="cinema_id" class="col-form-label">Bioskop:</label>
                <input type="text" name="cinema_id" id="cinema_id" class="form-control" value="{{ $schedule->cinema->name }}"
                    disabled>
            </div>
            <div class="mb-3">
                <label for="movie_id" class="col-form-label">Film:</label>
                <input type="text" name="movie_id" id="movie_id" class="form-control"
                    value="{{ $schedule->movie->title }}" disabled>
            </div>
            <div class="mb-3">
                <label for="price" class="col-form-label">Harga:</label>
                <input type="number" class="form-control @error('price') is-invalid @enderror"
                    value="{{ $schedule['price'] }}" id="price" name="price" value="{{ old('price') }}">
                @error('price')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Jam Tayang:</label>
                @php
                    $hours = is_array($schedule['hours']) ? $schedule['hours'] : json_decode($schedule['hours'], true);
                @endphp
                @foreach ($hours as $index => $hour)
                    <div class="d-flex align-items-center hour-item">
                        <input type="time" name="hours[]" id="hours" class="form-control my-2"
                            value="{{ $hour }}">
                        @if ($index > 0)
                            <i class="fa solid fa-circle-xmark text-danger" style="font-size: 1.5rem; cursor: pointer"
                                onclick="this.closest('.hour-item').remove()"></i>
                        @endif
                    </div>
                @endforeach

                <div id="additionalInput"></div>
                <span class="text-primary" style="cursor: pointer" onclick="addInput()">+ Tambah Jam Tayang</span>
                @if ($errors->has('hours.*'))
                    <br>
                    <small class="text-danger">{{ $errors->first('hours.*') }}</small>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Kirim</button>
        </div>
    </form>
    </div>
@endsection

@push('scripts')
    <script>
        function addInput() {
            let content = `
                <div class="d-flex align-items-center hour-additional">
                    <input type="time" name="hours[]" id="hours" class="form-control my-2">
                    <i class"fa-solid fa-circle-xmark text-danger"
                    style="font-size: 1,5rem; cursor: pointer" onclick="this.closest('.hour-additional').remove()"></i>
                </div>
            `;
            document.querySelector('#additionalInput').innerHTML += content;
        }
    </script>
@endpush
