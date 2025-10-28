@extends("Layouts.testlogin")
@section("title","Edit User")
@section('editUser')
    <form action="/update-user" method="post">
     @csrf
    <h4>Form Tambah User Pegawai</h4>
    <div class="form-group">
         <label>Username(Gunakan No Karyawan)</label>
        <input type="text" name="username" value="{{ session('username') }}" class="form-control" autofocus required>
    </div>

    <div class="form-group">
        <label>Nama User(Masukan nama Pengguna)</label>
        <input type="text" name="namaUser" value="{{ session('namaUser') }}" class="form-control" autofocus required>
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" value="{{ session('password') }}" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="text" name="email" value="{{ session('email') }}" class="form-control" autofocus required>
    </div>

    <div class="form-group">
        <label>Nomor HP</label>
        <input type="text" name="nomorHP" value="{{ session('nomorHP') }}" class="form-control" autofocus required>
    </div>

    <div class="form-group">
        <button type="submit"class="btn btn-primary">Submit</button>
        <a href="/login" class="btn btn-secondary">Batal</a>
    </div>

    </form>
@endsection
