@extends("Layouts/page")
@section("title","form edit pasien")
@section("pasien")
<div class="card">
    <div class="card-header">
        <h3>Edit Data Pasien</h3>
    </div>
    <div class="card-body">
        <form action="/update-pasien/{{ $pasien['id_RekamMedis'] }}" method="post">
            @csrf
            @method('PUT') {{-- Method spoofing untuk PUT --}}

            <div class="form-group">
                <label>ID Rekam Medis</label>
                <input type="text" name="Id_RekamMedis" class="form-control" value="{{ $pasien['id_RekamMedis'] }}" readonly>
            </div>

            <div class="form-group">
                <label>NIK</label>
                <input type="text" name="NoKTP" class="form-control" value="{{ $pasien['noKTP'] }}" readonly>
            </div>

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="Nama" class="form-control" value="{{ $pasien['nama'] }}" readonly>
            </div>

            <div class="form-group">
                <label>Gender</label>
                <select class="form-control" name="Gender">
                    <option value="Pria" {{ $pasien['gender'] == 'Pria' ? 'selected' : '' }}>Pria</option>
                    <option value="Wanita" {{ $pasien['gender'] == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                </select>
            </div>

            <div class="form-row">

            <div class="form-group col-md-6">
                <label>Tempat Lahir</label>
                <input type="text" name="TempatLahir" class="form-control" value="{{ $pasien['tempatLahir'] }}" required>
            </div>

            <div class="form-group col-md-6">
                <label>Tanggal Lahir</label>
                <input type="date" name="TanggalLahir" class="form-control" value="{{ $pasien['tanggalLahir'] }}" required>
            </div>

            <div class="form-group col-md-6">
                <label>Umur</label>
                <input type="number" name="Umur" class="form-control" value="{{ $pasien['umur'] }}" readonly>
            </div>

            <div class="form-group col-md-6">
                <label>Bulan</label>
                <input type="number" name="Bulan" class="form-control" value="{{ $pasien['bulan'] }}" readonly>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6" >
                <label>Agama</label>
                <select class="form-control" name="Agama" required>
                    <option value="Kristen" {{ $pasien['agama'] == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                    <option value="Katholik" {{ $pasien['agama'] == 'Katholik' ? 'selected' : '' }}>Katholik</option>
                    <option value="Islam" {{ $pasien['agama'] == 'Islam' ? 'selected' : '' }}>Islam</option>
                    <option value="Hindu" {{ $pasien['agama'] == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                    <option value="Budha" {{ $pasien['agama'] == 'Budha' ? 'selected' : '' }}>Budha</option>
                    <option value="Konghuchu" {{ $pasien['agama'] == 'Konghuchu' ? 'selected' : '' }}>Konghuchu</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Status pernikahan</label>
                <select class="form-control" name="StatusKawin" required>
                    <option value="SudahMenikah"{{ $pasien['statusKawin'] == 'SudahMenikah' ? 'selected' : '' }}>Sudah Menikah</option>
                    <option value="BelumMenikah"{{ $pasien['statusKawin'] == 'BelumMenikah' ? 'selected' : '' }}>Belum Menikah</option>
                    <option value="Bercerai"{{ $pasien['statusKawin'] == 'Bercerai' ? 'selected' : '' }}>Bercerai</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Pekerjaan</label>
                <input type="text" name="Pekerjaan" class="form-control" value="{{ $pasien['pekerjaan'] }}" required>
            </div>

        <div class="form-group col-md-6">
            <label>Pendidikan</label>
            <select class="form-control" name="Pendidikan" required>
                <option value="Belum Sekolah" {{ $pasien['pendidikan'] == 'Belum Sekolah' ? 'selected' : '' }}>Belum/Tidak Sekolah</option>
                <option value="SD" {{ $pasien['pendidikan'] == 'SD' ? 'selected' : '' }}>SD</option>
                <option value="SMP" {{ $pasien['pendidikan'] == 'SMP' ? 'selected' : '' }}>SMP</option>
                <option value="SMA" {{ $pasien['pendidikan'] == 'SMA' ? 'selected' : '' }}>SMA</option>
                <option value="SMK" {{ $pasien['pendidikan'] == 'SMK' ? 'selected' : '' }}>SMK</option>
                <option value="D4" {{ $pasien['pendidikan'] == 'D4' ? 'selected' : '' }}>D4</option>
                <option value="D3" {{ $pasien['pendidikan'] == 'D3' ? 'selected' : '' }}>D3</option>
                <option value="S1" {{ $pasien['pendidikan'] == 'S1' ? 'selected' : '' }}>S1</option>
                <option value="S2" {{ $pasien['pendidikan'] == 'S2' ? 'selected' : '' }}>S2</option>
                <option value="S3" {{ $pasien['pendidikan'] == 'S3' ? 'selected' : '' }}>S3</option>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Bahasa</label>
                <input type="text" name="Bahasa" class="form-control" value="{{ $pasien['bahasa'] }}" required>
            </div>

            <div class="form-group col-md-6">
                <label>Butuh Penerjemah</label>
                <select class="form-control" name="ButuhPenerjemah" required>
                    <option value="Butuh" {{ $pasien['butuhPenerjemah'] == 'Butuh' ? 'selected' : '' }}>Membutuhkan Penerjemah</option>
                    <option value="TidakButuh" {{ $pasien['butuhPenerjemah'] == 'TidakButuh' ? 'selected' : '' }}>Tidak Membutuhkan Penerjemah</option>
                </select>
            </div>
             </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>No Telepon</label>
                    <input type="text" name="NoTelp" class="form-control" value="{{ $pasien['bahasa'] }}" required maxlength="12">
                </div>

                <div class="form-group col-md-6">
                    <label>Email</label>
                    <input type="email" name="Email" class="form-control" value="{{ $pasien['email'] }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="Alamat" class="form-control" value="{{ $pasien['alamat'] }}" required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Kecamatan</label>
                    <input type="text" name="Kecamatan" class="form-control" value="{{ $pasien['kecamatan'] }}" required>
                </div>

                <div class="form-group col-md-4">
                    <label>Kabupaten</label>
                    <input type="text" name="Kabupaten" class="form-control" value="{{ $pasien['kabupaten'] }}" required>
                </div>

                <div class="form-group col-md-4">
                    <label>Provinsi</label>
                    <input type="text" name="Provinsi" class="form-control" value="{{ $pasien['provinsi'] }}" required>
                </div>
            </div>
</br>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="/pasien" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
