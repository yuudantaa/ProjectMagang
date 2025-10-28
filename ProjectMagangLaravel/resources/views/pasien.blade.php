@extends("Layouts.page")
@section("title","pasien")
@section('pasien')

    <div class="card-header">
        <h4>Daftar Pasien</h4>
        <a href="/exportpasien" class="btn btn-success">
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

            <!-- Form Pencarian -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="form-group">
                <label for="searchPasien">Cari Berdasarkan Nama Pasien</label>
                <input type="text" class="form-control" id="searchPasien" name="nama"
                       value="{{ $searchPasien }}" placeholder="Masukan Nama Pasien...">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="searchAlamat">Cari Berdasarkan Alamat</label>
                <input type="text" class="form-control" id="searchAlamat" name="alamat"
                       value="{{ $searchAlamat }}" placeholder="Masukan Alamat Pasien...">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="searchKecamatan">Cari Berdasarkan Kecamatan</label>
                <input type="text" class="form-control" id="searchKecamatan" name="kecamatan"
                       value="{{ $searchKecamatan }}" placeholder="Masukan Kecamatan Pasien...">
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


        <table id="pasienTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No Rekam Medis</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Tanggal Lahir</th>
                    <th>Gender</th>
                    <th>No Telp</th>
                    <th>Email</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ps as $idx => $p)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td>{{ $p['id_RekamMedis'] ?? 'N/A' }}</td>
                        <td>{{ $p['noKTP'] ?? 'N/A' }}</td>
                        <td>{{ $p['nama'] ?? 'N/A' }}</td>
                        <td>{{ $p['tanggalLahir'] ?? 'N/A' }}</td>
                        <td>{{ $p['gender'] ?? 'N/A' }}</td>
                        <td>{{ $p['noTelp'] ?? 'N/A' }}</td>
                        <td>{{ $p['email'] ?? 'N/A' }}</td>
                        <td>{{ $p['alamat'] ?? 'N/A' }}</td>
                        <td>
                            <a href="/pasien/{{ $p['id_RekamMedis'] }}" class="btn btn-primary"><i class="bi bi-eye"></i></a>
                            <a href="/pasien/edit/{{ $p['id_RekamMedis'] }}" class="btn btn-success"><i class="bi bi-pencil-square"></i></a>
                            <form action="/delete-pasien/{{ $p['id_RekamMedis'] }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchPasien = document.getElementById('searchPasien');
    const searchAlamat = document.getElementById('searchAlamat');
    const searchKecamatan = document.getElementById('searchKecamatan');
    const resetButton = document.getElementById('resetSearch');

    // Variabel untuk menyimpan state pencarian
    let searchTimeout;
    let isSearching = false;

    function submitSearch() {
        if (isSearching) return;

        isSearching = true;

        const params = new URLSearchParams();
        if (searchPasien.value) params.append('nama', searchPasien.value);
        if (searchKecamatan.value) params.append('kecamatan', searchKecamatan.value);
        if (searchAlamat.value) params.append('alamat', searchAlamat.value);

        window.location.href = '/pasien?' + params.toString();
    }

    function handleSearchInput() {
        clearTimeout(searchTimeout);
    }

    // Event listeners untuk input - tidak auto search
    searchPasien.addEventListener('input', handleSearchInput);
    searchAlamat.addEventListener('input', handleSearchInput);
    searchKecamatan.addEventListener('input', handleSearchInput);

    // Event listeners untuk Enter key
    function handleEnterKey(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            submitSearch();
        }
    }

    searchPasien.addEventListener('keypress', handleEnterKey);
    searchAlamat.addEventListener('keypress', handleEnterKey);
    searchKecamatan.addEventListener('keypress', handleEnterKey);

    // Tombol reset
    resetButton.addEventListener('click', function() {
        window.location.href = '/pasien';
    });

    // Initialize DataTable
    $('#pasienTable').DataTable({
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
