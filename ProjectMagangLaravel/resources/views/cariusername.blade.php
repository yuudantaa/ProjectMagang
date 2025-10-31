@extends("Layouts.testlogin")
@section("title","Cari Username")

@section('login')
<div class="card-body">
    <h4 class="card-title text-center mb-4">Cari Username</h4>

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

    <form action="/proses-cari-username" method="post">
        @csrf
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control"
                   value="{{ old('email') }}"
                   placeholder="Masukkan email yang terdaftar" required>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Cari Username</button>
        </div>

        <div class="text-center">
            <p class="mb-0">
                <a href="/login">Batalkan pencarian user</a>
            </p>
        </div>
    </form>
</div>
@endsection
