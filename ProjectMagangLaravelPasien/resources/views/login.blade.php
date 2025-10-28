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
    <img src="{{ asset('images/image.png') }}" class="img-mx-auto d-block" alt="rekamMedis" style="height: 150px; object-fit: cover;">
    </br>

    <div class="form-group">
        <input type="text" name="username" placeholder="Username" class="form-control" autofocus
        pattern="\d*" inputmode="numeric" maxlength="8" required>
    </div>

    <div class="form-group">
        <small class="form-text text-muted">Pilih tanggal lahir Anda</small>
        <input type="date" name="birthdate" id="birthdate" class="form-control" required>
        <input type="hidden" name="password" id="password">
    </div>

    <script>
        document.getElementById('birthdate').addEventListener('change', function(e) {
            const date = new Date(this.value);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();

            document.getElementById('password').value = day + month + year;
        });
    </script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/cari-username">Cari Username</a></li>
        </ol>
    </nav>

    <div class="form-group">
        <button type="submit"class="btn btn-primary">Login</button>

    </div>

    </form>

@endsection
