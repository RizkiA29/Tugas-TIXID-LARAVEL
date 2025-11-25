<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TIXID</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
  rel="stylesheet"
/>
<!-- Google Fonts -->
<link
  href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
  rel="stylesheet"
/>
<!-- MDB -->
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.min.css"
  rel="stylesheet"
  />
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>
<body>
    <!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <!-- Container wrapper -->
  <div class="container">
    <!-- Navbar brand -->
    <a class="navbar-brand me-2" href="https://mdbgo.com/">
      <img
        src="https://play-lh.googleusercontent.com/FcRZx_UEXN2uc7uKM5EKGn7Jmb65c8VVELlmligxdfUcjKKIpzFX0SHXFePllD2g4ik"
        height="16"
        alt="MDB Logo"
        loading="lazy"
        style="margin-top: -1px;"
      />
    </a>

    <!-- Toggle button -->
    <button
      data-mdb-collapse-init
      class="navbar-toggler"
      type="button"
      data-mdb-target="#navbarButtonsExample"
      aria-controls="navbarButtonsExample"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <i class="fas fa-bars"></i>
    </button>

    <!-- Collapsible wrapper -->
    <div class="collapse navbar-collapse" id="navbarButtonsExample">
      <!-- Left links -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        @if (Auth::check() && Auth::user()->role == 'admin')
 <li class="nav-item">
          <a class="nav-link" href="#">Dashboard</a>
        </li>
       <li class="nav-item dropdown">
    <a
      data-mdb-dropdown-init
      class="nav-link dropdown-toggle"
      href="#"
      id="navbarDropdownMenuLink"
      role="button"
      data-mdb-toggle="dropdown"
      aria-expanded="false"
    >
      Data Master
    </a>
    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
      <li>
        <a class="dropdown-item" href="{{route('admin.cinemas.index')}}">Data Bioskop</a>
      </li>
      <li>
        <a class="dropdown-item" href="{{route('admin.movies.index')}}">Data Film</a>
      </li>
      <li>
        <a class="dropdown-item" href="{{route('admin.users.index')}}">Data Petugas</a>
      </li>
    </ul>
</li>
@elseif (Auth::check() && Auth::user()->role == 'staff')
 <li class="nav-item">
          <a class="nav-link" href="{{route('staff.schedules.index')}}">Jadwal Tiket</a>
        </li>
 <li class="nav-item">
          <a class="nav-link" href="{{route('staff.promos.index')}}">Promo</a>
        </li>
        @else
 <li class="nav-item">
          <a class="nav-link" href="#">Beranda</a>
        </li>
 <li class="nav-item">
          <a class="nav-link" href="{{ route('cinemas.list') }}">Bioskop</a>
        </li>
         <li class="nav-item">
          <a class="nav-link" href="{{ route('tickets.index') }}">Tiket</a>
        </li>
        @endif
      </ul>
      <!-- Left links -->

      <div class="d-flex align-items-center">
        @if (Auth::check())
        <a href="{{route('logout')}}" class="btn btn-danger">Logout</a>
        @else
        <a href="{{route('login')}}" class="btn btn-link px-3 me-2">
          Login
        </a>
        <a href="{{route('signup')}}"class="btn btn-primary me-3">
          Sign up
        </a>
        @endif
      </div>
    </div>
    <!-- Collapsible wrapper -->
  </div>
  <!-- Container wrapper -->
</nav>
<!-- Navbar -->
@yield('content')
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
<!-- MDB -->
<script
  type="text/javascript"
  src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.umd.min.js"
></script>
  <!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@stack('script')
</body>
</html>
