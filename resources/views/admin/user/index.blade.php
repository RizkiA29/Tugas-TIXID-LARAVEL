@extends('templates.app')
@section('content')
    <div class="container mt-5">
        <a href="{{ route('admin.users.export') }}" class="btn btn-secondary mb-3">Export (.xlsx)</a>
        <a href="{{ route('admin.users.create') }}" class="btn btn-success mb-3">Tambah Pengguna</a>
        <a href="{{ route('admin.users.trash') }}" class="btn btn-danger mb-3">Sampah</a>
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        <table class="table table-bordered" id="usersTable">
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
       @push('script')
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.users.datatables') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name', orderable: true , searchable: true },
                    { data: 'email', name: 'email', orderable: false, searchable: false },
                    { data: 'role', name: 'role', orderable: false, searchable: false },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                ]
            });
        });
    </script>
       @endpush
        </table>
    </div>
@endsection
