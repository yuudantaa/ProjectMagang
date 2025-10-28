@extends("Layouts.pagekunjungan")
@section("title","kunjungan")
@section('kunjungan')

    <div class="card-header">
        <h4>Kunjungan Pasien Baru</h4>
        <a href="/tambahkunjungan" class="btn btn-primary">
            <i class="bi bi-plus-square"></i> Tambah Kunjungan
        </a>

        <a href="/exportkunjungan" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
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

        <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Id Kunjungan</th>
                    <th>Tanggal</th>
                    <th>No Rekam Medis</th>
                    <th>Nama</th>
                    <th>No Urut</th>
                    <th>Klinik</th>
                    <th>Dokter</th>
                    <th>Keluhan</th>
                    <th>Diagnosis</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ps as $idx => $p)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td>{{ $p['id_Kunjungan'] }}</td>
                        <td>{{ $p['tanggal'] }}</td>
                        <td>{{ $p['rekamMedis']['id_RekamMedis'] ?? '-' }}</td>
                       <td>{{ $p['rekamMedis']['nama'] ?? '-' }}</td>
                        <td>{{ $p['noAntrian'] }}</td>
                        <td>{{ $p['klinik']['nama'] ?? '-' }}</td>
                        <td>{{ $p['dokter']['nama'] ?? '-' }}</td>
                        <td>{{ $p['keluhan'] }}</td>
                        <td>{{ $p['diagnosis'] }}</td>
                        <td>
                            <a href="/kunjungan/{{ $p['id_Kunjungan'] }}" class="btn btn-primary"><i class="bi bi-eye"></i></a>
                            <a href="/kunjungan/edit/{{ $p['id_Kunjungan'] }}" class="btn btn-success"><i class="bi bi-pencil-square"></i></a>
                            <form action="/delete-kunjungan/{{ $p['id_Kunjungan'] }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>
                        </td>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

@endsection
