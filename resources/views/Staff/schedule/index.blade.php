@extends('templates.app')
@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-end">
            <a href="{{ route('staff.schedules.export') }}" class="btn btn-success mb-3">Export Excel</a>
            <a href="{{ route('staff.schedules.trash') }}" class="btn btn-secondary me-2">Sampah</a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdd">Tambah Data</button>
        </div>
        <h3 class="my-3">Data Jadwal Tayang</h3>
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        <table class="table table-bordered" id="schedulesTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Bioskop</th>
                    <th>Judul Film</th>
                    <th>Harga</th>
                    <th>Jadwal Tayang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
           @push('script')
    <script>
        $(document).ready(function() {
            $('#schedulesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('staff.schedules.datatables') }}',
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'cinema_id',
                        name: 'cinema_id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'movie_id',
                        name: 'movie_id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'price',
                        name: 'price',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'hours',
                        name: 'hours',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
           @endpush
            </tbody>
        </table>

        <!-- Modal Add -->
        <div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalAddLabel">Tambah Data</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('staff.schedules.store') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="cinema_id" class="col-form-label">Bioskop:</label>
                                <select name="cinema_id" id="cinema_id" class="form-select @error('cinema_id') is-invalid @enderror">
                                    <option disabled hidden selected>Pilih Bioskop</option>
                                    @foreach ($cinemas as $cinema)
                                        <option value="{{ $cinema->id }}" {{ old('cinema_id') == $cinema->id ? 'selected' : '' }}>{{ $cinema->name }}</option>
                                    @endforeach
                                </select>
                                @error('cinema_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="movie_id" class="col-form-label">Film:</label>
                                <select name="movie_id" id="movie_id" class="form-select @error('movie_id') is-invalid @enderror">
                                    <option disabled hidden selected>Pilih Film</option>
                                    @foreach ($movies as $movie)
                                        <option value="{{ $movie->id }}" {{ old('movie_id') == $movie->id ? 'selected' : '' }}>{{ $movie->title }}</option>
                                    @endforeach
                                </select>
                                @error('movie_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="price" class="col-form-label">Harga:</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}">
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jam Tayang:</label>
                                @if ($errors->has('hours.*'))
                                    <small class="text-danger d-block">{{ $errors->first('hours.*') }}</small>
                                @endif
                                <div id="hoursInputs">
                                    @if(old('hours'))
                                        @foreach(old('hours') as $oldHour)
                                            <input type="time" name="hours[]" class="form-control mt-2 @if ($errors->has('hours.*')) is-invalid @endif" value="{{ $oldHour }}">
                                        @endforeach
                                    @else
                                        <input type="time" name="hours[]" class="form-control @if ($errors->has('hours.*')) is-invalid @endif">
                                    @endif
                                </div>
                                <button class="btn btn-outline-primary mt-2" type="button" id="addHourBtn">+Tambah Input</button>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('addHourBtn').addEventListener('click', function() {
    let input = document.createElement('input');
    input.type = 'time';
    input.name = 'hours[]';
    input.className = 'form-control mt-2';
    document.getElementById('hoursInputs').appendChild(input);
});
</script>
@if ($errors->any())
<script>
    let modalAdd = document.querySelector('#modalAdd');
    new bootstrap.Modal(modalAdd).show();
</script>
@endif
@endpush
