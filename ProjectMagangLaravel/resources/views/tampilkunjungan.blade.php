@extends("Layouts.page")
@section("title","Tampil kunjungan")
@section('kunjungan')

<div class="card">
    <div class="card-header">
        <h3>Detail Kunjungan</h3>
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
                <div class="col-md-4">
                <h4>Data Kunjungan</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>Id Kunjungan</th>
                        <td>{{ $kunjungan['id_Kunjungan'] ?? '-'  }}</td>
                    </tr>
                    <tr>
                        <th>No Antrian</th>
                        <td>{{ $kunjungan['noAntrian']  ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Kunjungan</th>
                        <td>{{ $kunjungan['tanggal'] ?? '-'  }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kunjungan</th>
                        <td>{{ $kunjungan['jenisKunjungan'] ?? '-'  }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-4">
                <h4>Data Pasien</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>No Rekam Medis</th>
                        <td>{{ $kunjungan['rekamMedis']['id_RekamMedis'] ?? '-'  }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $kunjungan['rekamMedis']['nama']  ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>No KTP</th>
                        <td>{{ $kunjungan['rekamMedis']['noKTP'] ?? '-'  }}</td>
                    </tr>
                    <tr>
                        <th>Tempat, Tanggal Lahir</th>
                        <td>{{ $kunjungan['rekamMedis']['tempatLahir'] ?? '-'  }}, {{ $kunjungan['rekamMedis']['tanggalLahir']  ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Umur</th>
                        <td>{{ $kunjungan['rekamMedis']['umur']  ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Bulan</th>
                        <td>{{ $kunjungan['rekamMedis']['bulan']  ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td>{{ $kunjungan['rekamMedis']['gender']  ?? '-'  }}</td>
                    </tr>
                    <tr>
                        <th>Agama</th>
                        <td>{{ $kunjungan['rekamMedis']['agama']  ?? '-'  }}</td>
                    </tr>
                    <tr>
                        <th>Status Kawin</th>
                        <td>{{ $kunjungan['rekamMedis']['statusKawin'] ?? '-'  }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-4">
                <h4>Kontak & Alamat</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>No Telp</th>
                        <td>{{ $kunjungan['rekamMedis']['noTelp']?? '-'}}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $kunjungan['rekamMedis']['email']?? '-'}}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $kunjungan['rekamMedis']['alamat']?? '-'}}</td>
                    </tr>
                    <tr>
                        <th>Kecamatan</th>
                        <td>{{ $kunjungan['rekamMedis']['kecamatan']?? '-'}}</td>
                    </tr>
                    <tr>
                        <th>Kabupaten</th>
                        <td>{{ $kunjungan['rekamMedis']['kabupaten']?? '-'}}</td>
                    </tr>
                    <tr>
                        <th>Provinsi</th>
                        <td>{{ $kunjungan['rekamMedis']['provinsi']?? '-'}}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-4">
                <h4>Data Klinik</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>Id Klinik</th>
                        <td>{{ $kunjungan['klinik']['id_Klinik']?? '-'}}</td>
                    </tr>
                    <tr>
                        <th>Nama Klinik</th>
                        <td>{{ $kunjungan['klinik']['nama']?? '-'}}</td>
                    </tr>
                    <tr>
                        <th>Jenis</th>
                        <td>{{ $kunjungan['klinik']['jenis']?? '-'}}</td>
                    </tr>
                    <tr>
                        <th>Gedung</th>
                        <td>{{ $kunjungan['klinik']['gedung']?? '-'}}</td>
                    </tr>
                    <tr>
                        <th>Lantai</th>
                        <td>{{ $kunjungan['klinik']['lantai']?? '-'}}</td>
                    </tr>
                    <tr>
                        <th>Kapasitas</th>
                        <td>{{ $kunjungan['klinik']['kapasitas']?? '-'}}</td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td>{{ $kunjungan['klinik']['keterangan']?? '-'}}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-4">
                <h4>Data Dokter</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>Id Dokter</th>
                        <td>{{ $kunjungan['dokter']['id_Dokter']?? '-'}}</td>
                    </tr>
                    <tr>
                        <th>Nama Dokter</th>
                        <td>{{ $kunjungan['dokter']['nama']?? '-'}}</td>
                    </tr>
                    <tr>
                        <th>Spesialisasi</th>
                        <td>{{ $kunjungan['dokter']['spesialisasi']?? '-'}}</td>
                    </tr>
                    <tr>
                        <th>Nomor HP</th>
                        <td>{{ $kunjungan['dokter']['noHP']?? '-'}}</td>
                    </tr>
                    <tr>
                        <th>Dokter</th>
                        <td>{{ $kunjungan['dokter']['email']?? '-'}}</td>
                    </tr>
                    <tr>
                        <th>Hari Praktek</th>
                        <td>{{ $kunjungan['dokter']['hariPraktek']?? '-'}}</td>
                    </tr>
                    <tr>
                        <th>Jam Mulai</th>
                        <td>{{ $kunjungan['dokter']['jamMulai']?? '-'}}</td>
                    </tr>
                    <tr>
                        <th>Jam Selesai</th>
                        <td>{{ $kunjungan['dokter']['jamSelesai']?? '-'}}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mt-3">
            <a href="/kunjungan" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>

@endsection
