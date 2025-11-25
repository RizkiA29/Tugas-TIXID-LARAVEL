@extends('templates.app')
@section('content')
    <div class="container my-5">
<h5 class="mb-5">Seluruh Film Sedang Tayang</h5>
<form class="row mb-3" method="GET" action="">
    @csrf
    <div class="col-10">
        <input type="text" class="form-control" placeholder="Cari Judul Film..." name="search_movie" />
    </div>
    <div class="col-2">
        <button class="btn btn-primary">Cari</button>
    </form>
    </div>
<div class="container d-flex gap-2 mt-4 justify-content-center">
        @foreach ($movies as $key => $item )
        <div class="card">
            <img src="{{ asset('storage/' . $item['poster']) }}" class="card-img-top"
                alt="Fissure in Sandstone" style="height: 400px;" />
            <div class="card-body bg-primary text-warning"
                style="padding: 0px !important; text-align: center; font-weight: bold;">
                <p class="card-text" style="padding: 0px !important; text-align: center;">
                   <a href="{{ route('schedule_detail', $item['id']) }}" class="text-warning">BELI TIKET</a>
                </p>
         </div>
            </div>
            @endforeach
        </div>
@endsection
