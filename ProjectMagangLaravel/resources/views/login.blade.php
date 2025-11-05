@extends("Layouts.testlogin")
@section("title","login")
@section('login')

    @if(session('alert-success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('alert-success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('alert-danger'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session('alert-danger') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('alert-warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>{{ session('alert-warning') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form action="/login" method="post">
    @csrf
    <img src="{{ asset('images/images.png') }}" class="img-mx-auto d-block" alt="rekamMedis" style="height: 150px; object-fit: cover;">
    </br>

    <div class="form-group">
        <input type="text" name="username" placeholder="Username" class="form-control" autofocus
        pattern="\d*" inputmode="numeric" maxlength="8" required>
    </div>

    <div class="form-group">
         <input type="password" name="password" placeholder="Password" class="form-control" required>
    </div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
             <li class="breadcrumb-item"><a href="/tambah-user">Buat akun</a></li>
             <li class="breadcrumb-item"><a href="/lupa-password">Lupa Password</a></li>
            <li class="breadcrumb-item"><a href="/cari-username">Cari Username</a></li>
        </ol>
    </nav>

    <div class="form-group">
        <button type="submit"class="btn btn-primary">Login</button>

    </div>

    </form>

@endsection
