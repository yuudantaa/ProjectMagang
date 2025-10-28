@extends("Layouts.testlogin")
@section("title","Lupa Password")

@section('login')
<div class="card-body">
    <h4 class="card-title text-center mb-4">Lupa Password</h4>

    @if(session('alert-success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! session('alert-success') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('alert-info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {!! session('alert-info') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('alert-danger'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {!! session('alert-danger') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form action="/proses-lupa-password" method="post">
        @csrf
        <div class="form-group">
            <label>Email atau Username</label>
            <input type="text" name="emailOrUsername" class="form-control"
                   value="{{ old('emailOrUsername') }}"
                   placeholder="Masukkan email atau username Anda" required>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
        </div>

        <div class="text-center">
            <p class="mb-0">
                <a href="/login">Kembali ke login</a>
            </p>
        </div>
    </form>
</div>
@endsection
