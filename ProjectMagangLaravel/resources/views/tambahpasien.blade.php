@extends("Layouts.page")
@section("title","form tambah pasien")
@section("pasien")
<div class="card">
    <div class="card-header">
        <h3>Tambah Pasien Baru</h3>
    </div>
    <div class="card-body">
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

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form action="/save-pasien" method="post">
            @csrf

            <div class="form-group col-md-6">
                <label>NIK (NoKTP)</label>
                <input type="text" name="NoKTP" class="form-control" required
                    maxlength="16" pattern="\d*" inputmode="numeric">
            </div>

            <div class="form-group col-md-6">
                <label>Nama</label>
                <input type="text" name="Nama" class="form-control" required>
            </div>

            <div class="form-group col-md-6">
                <label>Gender</label>
                <select class="form-control" name="Gender" required>
                    <option value="">Pilih Gender</option>
                    <option value="Pria">Pria</option>
                    <option value="Wanita">Wanita</option>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Tempat Lahir</label>
                    <input type="text" name="TempatLahir" class="form-control" required>
                </div>

                <div class="form-group col-md-6">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="TanggalLahir" class="form-control" required>
                </div>
            </div>

            <div class="form-row">
            <div class="form-group col-md-6">
                <label>Umur</label>
                <input type="number" name="Umur" class="form-control" readonly>
            </div>

            <div class="form-group col-md-6">
                <label>Bulan</label>
                <input type="number" name="Bulan" class="form-control" readonly>
            </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Agama</label>
                    <select class="form-control" name="Agama" required>
                        <option value="">Pilih Agama</option>
                        <option value="Kristen">Kristen</option>
                        <option value="Katholik">Katholik</option>
                        <option value="Islam">Islam</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Budha">Budha</option>
                        <option value="Konghuchu">Konghuchu</option>
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label>Status Pernikahan</label>
                    <select class="form-control" name="StatusKawin" required>
                        <option value="">Pilih Status</option>
                        <option value="SudahMenikah">Sudah Menikah</option>
                        <option value="BelumMenikah">Belum Menikah</option>
                        <option value="Bercerai">Bercerai</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Pendidikan</label>
                    <select class="form-control" name="Pendidikan" required>
                        <option value="">Pilih Pendidikan</option>
                        <option value="BelumSekolah">Belum Sekolah</option>
                        <option value="SD">SD</option>
                        <option value="SMP">SMP</option>
                        <option value="SMA">SMA</option>
                        <option value="SMK">SMK</option>
                        <option value="D3">D3</option>
                        <option value="D4">D4</option>
                        <option value="S1">S1</option>
                        <option value="S2">S2</option>
                        <option value="S3">S3</option>
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label>Pekerjaan</label>
                    <input type="text" name="Pekerjaan" class="form-control" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Bahasa</label>
                    <input type="text" name="Bahasa" class="form-control" required>
                </div>

                <div class="form-group col-md-6">
                    <label>Butuh Penerjemah</label>
                    <select class="form-control" name="ButuhPenerjemah" required>
                        <option value="">Pilih</option>
                        <option value="Butuh">Butuh</option>
                        <option value="TidakButuh">Tidak Butuh</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>No Telepon</label>
                    <input type="text" name="NoTelp" class="form-control" required
                     pattern="\d*" inputmode="numeric" maxlength="12">
                </div>

                <div class="form-group col-md-6">
                    <label>Email</label>
                    <input type="email" name="Email" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="Alamat" class="form-control" required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Kecamatan</label>
                    <input type="text" name="Kecamatan" class="form-control" required>
                </div>

                <div class="form-group col-md-4">
                    <label>Kabupaten</label>
                    <input type="text" name="Kabupaten" class="form-control" required>
                </div>

                <div class="form-group col-md-4">
                    <label>Provinsi</label>
                    <input type="text" name="Provinsi" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
                <a href="/tambahkunjungan" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
            <input type="hidden" name="redirect_to_kunjungan" value="1">
        </form>
    </div>
</div>

<style>
    .form-control[readonly] {
    background-color: #f8f9fa;
    cursor: not-allowed;
}

.form-control[readonly]:focus {
    border-color: #ced4da;
    box-shadow: none;
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const tanggalLahirInput = document.querySelector('input[name="TanggalLahir"]');
    const umurInput = document.querySelector('input[name="Umur"]');
    const bulanInput = document.querySelector('input[name="Bulan"]');

    function calculateAgeFromInput() {
        if (!tanggalLahirInput.value) {
            umurInput.value = '';
            bulanInput.value = '';
            return;
        }

        const today = new Date();
        const birthDate = new Date(tanggalLahirInput.value);

        let years = today.getFullYear() - birthDate.getFullYear();
        let months = today.getMonth() - birthDate.getMonth();
        let days = today.getDate() - birthDate.getDate();

        // Adjust if current month/day is before birth month/day
        if (months < 0 || (months === 0 && days < 0)) {
            years--;
            months += 12;
        }

        if (days < 0) {
            const lastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
            days += lastMonth.getDate();
            months--;
        }

        // Untuk bayi di bawah 1 tahun, tampilkan bulan saja
        if (years === 0) {
            umurInput.value = 0;
            bulanInput.value = months;
        } else {
            umurInput.value = years;
            bulanInput.value = months;
        }
    }

    // Event listeners
    tanggalLahirInput.addEventListener('change', calculateAgeFromInput);
    tanggalLahirInput.addEventListener('input', calculateAgeFromInput);

    // Hitung otomatis saat halaman dimuat
    calculateAgeFromInput();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const noKtpInput = document.querySelector('input[name="NoKTP"]');
    const form = document.querySelector('form');

    noKtpInput.addEventListener('blur', function() {
        const noKtp = this.value;
        if (noKtp.length === 16) {
            // Cek ke API apakah NoKTP sudah terdaftar
            fetch(`/check-noktp?noktp=${noKtp}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        alert('No KTP ini sudah terdaftar dalam sistem!');
                        this.focus();
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    });

    // Tambahkan route di Laravel untuk endpoint pengecekan NoKTP
});
</script>
@endsection
