@extends('templates.app')
@section('content')
<div class="container mt-5">
    @if (session('success'))
        <div class="alert alert-success"><br>{{ session('success') }} </div>
    @endif
    <a href="{{ route('staff.promos.create') }}" class="btn btn-success mb-3">Tambah Promo</a>
    <a href="{{ route('staff.promos.export') }}" class="btn btn-secondary mb-3">Export (.xlsx)</a>
     <a href="{{ route('staff.promos.trash') }}" class="btn btn-secondary me-2">Sampah</a>
    <table class="table table-bordered" id="promosTable">
        <tr>
            <th>#</th>
            <th>Kode Promo</th>
            <th>Tipe Promo</th>
            <th>Jumlah Potongan</th>
            <th>Aksi</th>
        </tr>
        @push('script')
        <script>
            $(document).ready(function() {
                $('#promosTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('staff.promos.datatables') }}',
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'promo_code', name: 'code', orderable: true, searchable: true },
                        { data: 'type', name: 'type', orderable: false, searchable: false },
                        { data: 'discount', name: 'discount', orderable: false, searchable: false },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false },
                    ]
                });
            });
        </script>
        @endpush
    </table>
</div>
@endsection
