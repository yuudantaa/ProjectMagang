@extends("Layouts.page")
@section("title","dokter")
@section('dokter')

    <div class="card-header">
        <h4>Daftar Dokter</h4>
        <a href="/dokter/tambahdokter" class="btn btn-primary">
            <i class="bi bi-plus-square"></i> Tambah Dokter
        </a>

        <a href="/exportdokter" class="btn btn-success">
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
                <label for="searchDokter">Cari Berdasarkan Nama Dokter</label>
                <input type="text" class="form-control" id="searchDokter" name="nama"
                       value="{{ $searchDokter }}" placeholder="Masukan Nama Dokter...">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="searchSpesialisasi">Cari Berdasarkan Spesialisasi</label>
                <input type="text" class="form-control" id="searchSpesialisasi" name="spesialisasi"
                       value="{{ $searchSpesialisasi }}" placeholder="Masukan Spesialisasi...">
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
                Menampilkan {{ count($kl) }} data
            </span>
        </div>
    </div>

        <table id="dokterTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Id Dokter</th>
                    <th>Nama</th>
                    <th>Spesialisasi</th>
                    <th>Nomor HP</th>
                    <th>Email</th>
                    <th>Hari Praktek</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kl as $idx => $k)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td>{{ $k['id_Dokter'] }}</td>
                        <td>{{ $k['nama'] }}</td>
                        <td>{{ $k['spesialisasi'] }}</td>
                        <td>{{ $k['noHP'] }}</td>
                        <td>{{ $k['email'] }}</td>
                        <td>{{ $k['hariPraktek'] }}</td>
                        <td>{{ $k['jamMulai'] }}</td>
                        <td>{{ $k['jamSelesai'] }}</td>
                        <td>
                            <a href="/dokter/edit/{{ $k['id_Dokter'] }}" class="btn btn-success"><i class="bi bi-pencil-square"></i></a>
                            <form action="/delete-dokter/{{ $k['id_Dokter'] }}" method="POST" style="display: inline;">
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
    const searchDokter = document.getElementById('searchDokter');
    const searchSpesialisasi = document.getElementById('searchSpesialisasi');
    const resetButton = document.getElementById('resetSearch');

    // Variabel untuk menyimpan state pencarian
    let searchTimeout;
    let isSearching = false;

    function submitSearch() {
        if (isSearching) return;

        isSearching = true;

        const params = new URLSearchParams();
        if (searchDokter.value) params.append('nama', searchDokter.value);
        if (searchSpesialisasi.value) params.append('spesialisasi', searchSpesialisasi.value);

        window.location.href = '/dokter?' + params.toString();
    }

    function handleSearchInput() {
        clearTimeout(searchTimeout);
    }

    // Event listeners untuk input - tidak auto search
    searchDokter.addEventListener('input', handleSearchInput);
    searchSpesialisasi.addEventListener('input', handleSearchInput);

    // Event listeners untuk Enter key
    function handleEnterKey(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            submitSearch();
        }
    }

    searchDokter.addEventListener('keypress', handleEnterKey);
    searchSpesialisasi.addEventListener('keypress', handleEnterKey);

    // Tombol reset
    resetButton.addEventListener('click', function() {
        window.location.href = '/dokter';
    });

    // Initialize DataTable
    $('#dokterTable').DataTable({
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
