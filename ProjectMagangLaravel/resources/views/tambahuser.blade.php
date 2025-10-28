@extends("Layouts.testlogin")
@section("title","Tambah User")
@section('createUser')

    <form action="/save-user" method="post">
        @csrf
        <h4 class="mb-4">Form Tambah User Pegawai</h4>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Nama User</label>
                <input type="text" name="namaUser" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Nomor Telepon</label>
                <input type="text" name="nomorHp" class="form-control"
                maxlength="12" pattern="\d*" inputmode="numeric" required>
            </div>

        <div class="form-group">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="bi bi-save"></i> Simpan
                </button>
            <a href="/login" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Login
            </a>
        </div>
    </form>
@endsection
