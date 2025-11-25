@extends('templates.app')
@section('content')
<div class="container mt-5">
    <h5>Dashboard Petugas</h5>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }} <b>Selamat Datang, {{ Auth::user()->name }}</b>
        </div>
    @endif
@endsection
