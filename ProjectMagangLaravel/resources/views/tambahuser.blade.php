@extends("Layouts.testlogin")
@section("title","Tambah User")
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

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form action="/simpan-user" method="post">
        @csrf
        <h4 class="mb-4 text-center">Form Tambah User Pegawai</h4>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror"
                   value="{{ old('username') }}"
                   pattern="\d*" inputmode="numeric" maxlength="8"
                   placeholder="Masukkan no karyawan sebanyak 8 digit" required>
            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                   placeholder="Masukkan password (minimal 6 karakter)" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="namaUser">Nama Pengguna</label>
            <input type="text" name="namaUser" id="namaUser" class="form-control @error('namaUser') is-invalid @enderror"
                   value="{{ old('namaUser') }}"
                   placeholder="Masukkan nama" required>
            @error('namaUser')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}"
                   placeholder="Masukkan alamat email" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="nomorHp">Nomor Telepon</label>
            <input type="text" name="nomorHp" id="nomorHp" class="form-control @error('nomorHp') is-invalid @enderror"
                   value="{{ old('nomorHp') }}"
                   pattern="\d*" inputmode="numeric" maxlength="12"
                   placeholder="Masukkan nomor telepon (10-12 digit)" required>
            @error('nomorHp')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
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

    <script>
        // Validasi real-time untuk input numerik
        document.addEventListener('DOMContentLoaded', function() {
            const numericInputs = document.querySelectorAll('input[pattern="\\d*"]');

            numericInputs.forEach(input => {
                input.addEventListener('input', function(e) {
                    // Hanya allow angka
                    this.value = this.value.replace(/[^\d]/g, '');
                });

                input.addEventListener('keypress', function(e) {
                    // Prevent karakter non-numeric
                    if (!/\d/.test(e.key)) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>

@endsection
