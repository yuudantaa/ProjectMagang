@extends("Layouts.testlogin")
@section("title","Reset Password")

@section('login')
<div class="card-body">
    <h4 class="card-title text-center mb-4">Reset Password</h4>

    @if(session('alert-danger'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {!! session('alert-danger') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form action="/proses-reset-password" method="post">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label>Password Baru</label>
            <input type="password" name="newPassword" class="form-control"
                   placeholder="Masukkan password baru (minimal 6 karakter)"
                   minlength="6" required>
        </div>

        <div class="form-group">
            <label>Konfirmasi Password Baru</label>
            <input type="password" name="confirmPassword" class="form-control"
                   placeholder="Masukkan kembali password baru"
                   minlength="6" required>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
        </div>

        <div class="text-center">
            <a href="/login">Kembali ke login</a>
        </div>
    </form>
</div>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const newPassword = document.querySelector('input[name="newPassword"]').value;
    const confirmPassword = document.querySelector('input[name="confirmPassword"]').value;

    if (newPassword !== confirmPassword) {
        e.preventDefault();
        alert('Password dan konfirmasi password tidak sama!');
        return false;
    }
});
</script>
@endsection
