@extends("Layouts.page")
@section("title","Riwayat Kunjungan Pasien")
@section('kunjungan')

    <div class="card-header">
        <h4>Riwayat Kunjungan Pasien</h4>
        <a href="/tambah-kunjungan" class="btn btn-primary">
            <i class="bi bi-plus-square"></i> Tambah Kunjungan
        </a>
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
   <div class="table-responsive">
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Id Kunjungan</th>
                    <th>Tanggal</th>
                    <th>No Rekam Medis</th>
                    <th>Nama Pasien</th>
                    <th>No Urut</th>
                    <th>Klinik</th>
                    <th>Dokter</th>
                    <th>Keluhan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ps as $idx => $p)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td>{{ $p['id_Kunjungan'] ?? '-' }}</td>
                        <td>{{ $p['tanggal'] ?? '-' }}</td>
                        <td>{{ $p['rekamMedis']['id_RekamMedis'] ?? '-' }}</td>
                        <td>{{ $p['rekamMedis']['nama'] ?? '-' }}</td>
                        <td>{{ $p['noAntrian'] ?? '-' }}</td>
                        <td>{{ $p['klinik']['nama'] ?? '-' }}</td>
                        <td>{{ $p['dokter']['nama'] ?? '-' }}</td>
                        <td>{{ $p['keluhan'] ?? '-' }}</td>
                        <td>
                            <a href="/kunjungan/{{ $p['id_Kunjungan'] }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                         </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>

@endsection

