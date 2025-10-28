@extends("Layouts.page")
@section("title","Tampil Pasien")
@section('pasien')

<div class="card">
    <div class="card-header">
        <h3>Detail Pasien</h3>
    </div>
    <div class="card-body">
        @if(session('alert'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>{{ session("alert") }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <h4>Data Pribadi</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>No Rekam Medis</th>
                        <td>{{ $pasien['id_RekamMedis'] }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $pasien['nama'] }}</td>
                    </tr>
                    <tr>
                        <th>No KTP</th>
                        <td>{{ $pasien['noKTP'] }}</td>
                    </tr>
                    <tr>
                        <th>Tempat, Tanggal Lahir</th>
                        <td>{{ $pasien['tempatLahir'] }}, {{ $pasien['tanggalLahir'] }}</td>
                    </tr>
                    <tr>
                        <th>Umur</th>
                        <td>{{ $pasien['umur'] }}</td>
                    </tr>
                    <tr>
                        <th>Bulan</th>
                        <td>{{ $pasien['bulan'] }}</td>
                    </tr>
                    <tr>
                        <th>Agama</th>
                        <td>{{ $pasien['agama'] }}</td>
                    </tr>
                    <tr>
                        <th>Status Kawin</th>
                        <td>{{ $pasien['statusKawin'] }}</td>
                    </tr>
                    <tr>
                        <th>Pendidikan</th>
                        <td>{{ $pasien['pendidikan'] }}</td>
                    </tr>
                    <tr>
                        <th>Pekerjaan</th>
                        <td>{{ $pasien['pekerjaan'] }}</td>
                    </tr>
                    <tr>
                        <th>Bahasa</th>
                        <td>{{ $pasien['bahasa'] }}</td>
                    </tr>
                    <tr>
                        <th>Butuh Penerjemah</th>
                        <td>{{ $pasien['butuhPenerjemah'] }}</td>
                    </tr>
                </table>
                </table>
            </div>
            <div class="col-md-6">
                <h4>Kontak & Alamat</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>No Telp</th>
                        <td>{{ $pasien['noTelp'] }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $pasien['email'] }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $pasien['alamat'] }}</td>
                    </tr>
                    <tr>
                        <th>Kecamatan</th>
                        <td>{{ $pasien['kecamatan'] }}</td>
                    </tr>
                    <tr>
                        <th>Kabupaten</th>
                        <td>{{ $pasien['kabupaten'] }}</td>
                    </tr>
                    <tr>
                        <th>Provinsi</th>
                        <td>{{ $pasien['provinsi'] }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mt-3">
            <a href="/pasien" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>

@endsection
