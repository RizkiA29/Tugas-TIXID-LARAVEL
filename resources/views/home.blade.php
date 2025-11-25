@extends('templates.app')
@section('content')
@if (Session::get('success'))
    <div class="alert alert-success">{{Session::get('success')}} <b>Selamat Datang, {{Auth::user()->name}}</b></div>
@endif
@if (Session::get('logout'))
    <div class="alert alert-warning">{{Session::get('logout')}}</div>
@endif
    <div class="dropdown">
        <button class="btn btn-light w-100 text-start dropdown-toggle" type="button" id="dropdownMenuButton"
            data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
            <i class="fa-solid fa-location-dot"></i>Bogor
        </button>
        <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
            <li><a class="dropdown-item" href="#">Jakarta Timur</a></li>
            <li><a class="dropdown-item" href="#">Jakarta Barat</a></li>
            <li><a class="dropdown-item" href="#">Depok</a></li>
        </ul>
    </div>

    <!-- Carousel wrapper -->
    <div id="carouselBasicExample" class="carousel slide carousel-fade" data-mdb-ride="carousel" data-mdb-carousel-init>
        <!-- Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide-to="2"
                aria-label="Slide 3"></button>
        </div>

        <!-- Inner -->
        <div class="carousel-inner">
            <!-- Single item -->
            <div class="carousel-item active">
                <img style="height: 400px;"
                    src="https://www.fangoria.com/wp-content/uploads/2025/05/Screenshot-2025-05-07-at-10.06.39-e1746608843459.png"
                    class="d-block w-100" alt="Sunset Over the City" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>First slide label</h5>
                    <p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
                </div>
            </div>

            <!-- Single item -->
            <div class="carousel-item">
                <img style="height: 400px;"
                    src="data:image/jpeg;base64,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"
                    class="d-block w-100" alt="Canyon at Night" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>Second slide label</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
            </div>

            <!-- Single item -->
            <div class="carousel-item">
                <img style="height: 400px;"
                    src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTpXkCDGoLy4SK_AH5rB5K2bKRiTklxCYE3BA&s"
                    class="d-block w-100" alt="Sunset Over the City" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>Third slide label</h5>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.</p>
                </div>
            </div>
        </div>
        <!-- Inner -->

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <!-- Carousel wrapper -->
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-clapperboard"></i>
                <h5 class="ms-2 mt-2">Sedang Tayang</h5>
            </div>
            <div>
                <a href="{{ route('home.Movies.all') }}" class="btn btn-warning rounded-pill"> Semua</a>
            </div>
        </div>
    </div>

    <div class="container d-flex gap-2">
        <a href="{{ route('home.Movies.all') }}" class="btn btn-outline-primary rounded-pill">Semua Film</a>
        <button class="btn btn-outline-secondary rounded-pill">XXI</button>
        <button class="btn btn-outline-secondary rounded-pill">Cinepolis</button>
        <button class="btn btn-outline-secondary rounded-pill">Imax</button>
    </div>


    <div class="container d-flex gap-2 mt-4 justify-content-center">
        @foreach ($movies as $key => $item )
        <div class="card">
            <img src="{{ asset('storage/' . $item['poster']) }}" class="card-img-top"
                alt="Fissure in Sandstone" style="height: 400px;" />
            <div class="card-body bg-primary text-warning"
                style="padding: 0px !important; text-align: center; font-weight: bold;">
                <p class="card-text" style="padding: 0px !important; text-align: center;">
                    <a href="{{ route('schedule_detail', $item['id']) }}"
                     class="text-warning">BELI TIKET</p>
         </div>
            </div>
            @endforeach
        </div>

    <footer class="bg-body-tertiary text-center text-lg-start mt-5">
  <!-- Copyright -->
  <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
    © 2025 Copyright:
    <a class="text-body" href="https://mdbootstrap.com/">TIXID.com</a>
  </div>
  <!-- Copyright -->
</footer>
@endsection
