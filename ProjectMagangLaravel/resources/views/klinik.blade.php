@extends("Layouts.page")
@section("title","klinik")
@section('klinik')

    <div class="card-header">
        <h4>Daftar Klinik</h4>
        <a href="/klinik/tambahklinik" class="btn btn-primary">
            <i class="bi bi-plus-square"></i> Tambah Klinik
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
                <label for="searchKlinik">Cari Berdasarkan Nama Klinik</label>
                <input type="text" class="form-control" id="searchKlinik" name="nama"
                       value="{{ $searchKlinik }}" placeholder="Masukan Nama Klinik...">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="searchJenis">Cari Berdasarkan Jenis Klinik</label>
                <input type="text" class="form-control" id="searchJenis" name="jenis"
                       value="{{ $searchJenis }}" placeholder="Masukan Jenis Klinik...">
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

        <table id="klinikTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Id Klinik</th>
                    <th>Nama</th>
                    <th>Jenis</th>
                    <th>Gedung</th>
                    <th>Lantai</th>
                    <th>Kapasitas</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kl as $idx => $k)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td>{{ $k['id_Klinik'] }}</td>
                        <td>{{ $k['nama'] }}</td>
                        <td>{{ $k['jenis'] }}</td>
                        <td>{{ $k['gedung'] }}</td>
                        <td>{{ $k['lantai'] }}</td>
                        <td>{{ $k['kapasitas'] }}</td>
                        <td>{{ $k['keterangan'] }}</td>
                        <td>
                            <a href="/klinik/edit/{{ $k['id_Klinik'] }}" class="btn btn-success"><i class="bi bi-pencil-square"></i></a>
                            <form action="/delete-klinik/{{ $k['id_Klinik'] }}" method="POST" style="display: inline;">
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
    const searchKlinik = document.getElementById('searchKlinik');
    const searchJenis = document.getElementById('searchJenis');
    const resetButton = document.getElementById('resetSearch');

    // Variabel untuk menyimpan state pencarian
    let searchTimeout;
    let isSearching = false;

    function submitSearch() {
        if (isSearching) return;

        isSearching = true;

        const params = new URLSearchParams();
        if (searchKlinik.value) params.append('nama', searchKlinik.value);
        if (searchJenis.value) params.append('jenis', searchJenis.value);

        window.location.href = '/klinik?' + params.toString();
    }

    function handleSearchInput() {
        clearTimeout(searchTimeout);
    }

    // Event listeners untuk input - tidak auto search
    searchKlinik.addEventListener('input', handleSearchInput);
    searchJenis.addEventListener('input', handleSearchInput);

    // Event listeners untuk Enter key
    function handleEnterKey(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            submitSearch();
        }
    }

    searchKlinik.addEventListener('keypress', handleEnterKey);
    searchJenis.addEventListener('keypress', handleEnterKey);

    // Tombol reset
    resetButton.addEventListener('click', function() {
        window.location.href = '/klinik';
    });

    // Initialize DataTable
    $('#klinikTable').DataTable({
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
