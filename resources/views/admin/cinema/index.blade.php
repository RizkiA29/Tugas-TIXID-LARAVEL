@extends('templates.app')
@section('content')
<div class="container mt-5">
    @if (Session::get('success'))
    <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.cinemas.trash') }}" class="btn btn-danger me-2">Sampah</a>
        <a href="{{ route('admin.cinemas.create') }}" class="btn btn-success">Tambah Data</a>
        @if (Session::get('error'))
        <div class="alert alert-danger me-3">{{ Session::get('error') }}</div>
        @endif
        <a href="{{ route('admin.cinemas.export') }}" class="btn btn-secondary me-2">Export (.xlsx)</a>
    </div>
    <h5 class="mt-3">Data Bioskop</h5>
    <table class="table table-bordered" id="cinemasTable">
        <tr>
            <th>#</th>
            <th>Nama Bioskop</th>
            <th>Lokasi Bioskop</th>
            <th>Aksi</th>
        </tr>
    @push('script')
    <script>
        $(document).ready(function() {
            $('#cinemasTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.cinemas.datatables') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name', orderable: true , searchable: true },
                    { data: 'location', name: 'location', orderable: false, searchable: false },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                ]
            });
        });
    </script>
    @endpush

    </table>
</div>
@endsection
