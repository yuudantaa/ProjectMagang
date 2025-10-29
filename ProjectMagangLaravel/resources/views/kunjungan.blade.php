@extends("Layouts.pagekunjungan")
@section("title","kunjungan")
@section('kunjungan')

    <div class="card-header">
        <h4>Kunjungan Pasien Baru</h4>
        <a href="/tambahkunjungan" class="btn btn-primary">
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
    <!-- Form Pencarian -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="form-group">
                <label for="searchBulan">Cari Berdasarkan Bulan</label>
                <input type="month" class="form-control" id="searchBulan" name="bulan"
                       value="{{ $searchBulan }}" placeholder="Pilih bulan...">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="searchDokter">Cari Berdasarkan Dokter</label>
                <input type="text" class="form-control" id="searchDokter" name="dokter"
                       value="{{ $searchDokter }}" placeholder="Ketikan nama dokter..." list="dokterList">
                <datalist id="dokterList">
                    @foreach($dokterList as $dokter)
                        <option value="{{ $dokter }}">
                    @endforeach
                </datalist>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="searchKlinik">Cari Berdasarkan Klinik</label>
                <input type="text" class="form-control" id="searchKlinik" name="klinik"
                       value="{{ $searchKlinik }}" placeholder="Ketikan nama klinik..." list="klinikList">
                <datalist id="klinikList">
                    @foreach($klinikList as $klinik)
                        <option value="{{ $klinik }}">
                    @endforeach
                </datalist>
            </div>
        </div>
    </div>

    <!-- Tombol Reset -->
    <div class="row mb-4">
        <div class="col-md-12">
            <button type="button" id="resetSearch" class="btn btn-secondary">
                <i class="bi bi-arrow-clockwise"></i> Reset Pencarian
            </button>
            <span class="ml-2 text-muted" id="resultCount">
                Menampilkan {{ count($ps) }} data
            </span>
        </div>
    </div>

        <table id="kunjunganbaru" class="table table-striped table-bordered" style="width:100%">
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Existing search functionality...
    const searchBulan = document.getElementById('searchBulan');
    const searchDokter = document.getElementById('searchDokter');
    const searchKlinik = document.getElementById('searchKlinik');
    const resetButton = document.getElementById('resetSearch');

    function submitSearch() {
        const params = new URLSearchParams();
        if (searchBulan.value) params.append('bulan', searchBulan.value);
        if (searchDokter.value) params.append('dokter', searchDokter.value);
        if (searchKlinik.value) params.append('klinik', searchKlinik.value);
        window.location.href = '/kunjungan?' + params.toString();
    }

    searchBulan.addEventListener('change', submitSearch);

    let dokterTimeout;
    searchDokter.addEventListener('input', function() {
        clearTimeout(dokterTimeout);
        dokterTimeout = setTimeout(submitSearch, 500);
    });

    let klinikTimeout;
    searchKlinik.addEventListener('input', function() {
        clearTimeout(klinikTimeout);
        klinikTimeout = setTimeout(submitSearch, 500);
    });

    resetButton.addEventListener('click', function() {
        window.location.href = '/kunjungan';
    });

    // Initialize DataTable
    $('#kunjunganbaru').DataTable({
        "language": {
            "search": "Cari dalam tabel:",
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Data tidak ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data tersedia",
            "infoFiltered": "(disaring dari _MAX_ total data)"
        },
        "responsive": true
    });
});

</script>
@endsection
