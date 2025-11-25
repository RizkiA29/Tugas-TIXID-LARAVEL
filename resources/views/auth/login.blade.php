<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TIXID</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.min.css" rel="stylesheet" />
</head>

<body>
    <!-- filepath: c:\laragon\www\tixid-1\resources\views\auth\login.blade.php -->
<form class="w-50 d-block mx-auto my-5" method="POST" action="{{ route('auth') }}">
    @csrf
    @if (Session::get('error'))
        <div class="alert alert-danger my-3">{{ Session::get('error') }}</div>
    @endif
@if (session::get('ok'))
  <div class="alert alert-danger">{{session::get('error')}}</div>
@endif
    <div data-mdb-input-init class="form-outline mb-4">
        <input type="email" id="form1Example1" name="email"
            class="form-control @error('email') is-invalid @enderror"  value="{{ old('email') }}" />
        <label class="form-label" for="form1Example1">Email address</label>
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div data-mdb-input-init class="form-outline mb-4">
    <input type="password" id="form1Example2" name="password"
        class="form-control @error('password') is-invalid @enderror
    <label class="form-label" for="form1Example2">Password</label>
    @error('password')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

    <button data-mdb-ripple-init type="submit" class="btn btn-primary btn-block">Login</button>
    <div class="text-center mt-3">
        <a href="{{ route('home') }}">Kembali</a>
    </div>
</form>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.umd.min.js"></script>
</body>

</html>
