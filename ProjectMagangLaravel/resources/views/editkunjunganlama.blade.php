@extends("Layouts.page")
@section("title","Edit Kunjungan Baru")
@section("kunjungan")

<div class="card">
    <div class="card-header">
        <h3>Edit Kunjungan Baru</h3>
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

        <form action="/update-kunjungan-lama/{{ $kunjungan['id_Kunjungan'] }}" method="post">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="form-group col-md-4">
                    <label>Tanggal *</label>
                    <input type="date" name="Tanggal" class="form-control" required
                           value="{{ $kunjungan['tanggal'] ?? date('Y-m-d') }}" readonly>
                </div>
                <div class="form-group col-md-4">
                    <label>No Antrian *</label>
                    <input type="number" name="NoAntrian" class="form-control" required min="1"
                           value="{{ $kunjungan['noAntrian'] ?? '' }}" readonly>
                </div>
            </div>

            <div class="form-group">
                <label class="d-block mb-2">Data Pasien *</label>

                <div class="row">
                    <div class="col-md-4">
                        <label>No Rekam Medis</label>
                        <input type="text" name="Id_RekamMedis" id="noRekamMedis" class="form-control"
                               value="{{ $kunjungan['rekamMedis']['id_RekamMedis'] ?? '' }}" readonly required>
                    </div>
                    <div class="col-md-4">
                        <label>Nama Pasien</label>
                        <input type="text" id="namaPasien" class="form-control"
                               value="{{ $kunjungan['rekamMedis']['nama'] ?? '' }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label>Tanggal Lahir</label>
                        <input type="text" id="tanggalLahir" class="form-control"
                               value="{{ isset($kunjungan['rekamMedis']['tanggalLahir']) ? date('d-m-Y', strtotime($kunjungan['rekamMedis']['tanggalLahir'])) : '' }}" readonly>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="d-block mb-2">Data Klinik *</label>
                <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#klinikModal">
                    <i class="bi bi-search"></i> Cari Klinik
                </button>

                <div class="row">
                    <div class="col-md-4">
                        <label>Id Klinik</label>
                        <input type="text" name="Id_Klinik" id="idKlinik" class="form-control"
                               value="{{ $kunjungan['klinik']['id_Klinik'] ?? '' }}" readonly required>
                    </div>
                    <div class="col-md-4">
                        <label>Nama Klinik</label>
                        <input type="text" id="namaKlinik" class="form-control"
                               value="{{ $kunjungan['klinik']['nama'] ?? '' }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label>Jenis Klinik</label>
                        <input type="text" id="jenis" class="form-control"
                               value="{{ $kunjungan['klinik']['jenis'] ?? '' }}" readonly>
                    </div>
                </div>
            </div>
             <input type="hidden" name="JenisKunjungan" value="Lama">
            <div class="form-group">
                <label class="d-block mb-2">Data Dokter *</label>
                <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#dokterModal">
                    <i class="bi bi-search"></i> Cari Dokter
                </button>

                <div class="row">
                    <div class="col-md-4">
                        <label>ID Dokter</label>
                        <input type="text" name="Id_Dokter" id="idDokter" class="form-control"
                               value="{{ $kunjungan['dokter']['id_Dokter'] ?? '' }}" readonly required>
                    </div>
                    <div class="col-md-4">
                        <label>Nama Dokter</label>
                        <input type="text" id="namaDokter" class="form-control"
                               value="{{ $kunjungan['dokter']['nama'] ?? '' }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label>Spesialisasi</label>
                        <input type="text" id="spesialisasi" class="form-control"
                               value="{{ $kunjungan['dokter']['spesialisasi'] ?? '' }}" readonly>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Keluhan *</label>
                <textarea name="Keluhan" class="form-control" rows="3">{{ $kunjungan['keluhan'] ?? '' }}</textarea>
            </div>

            <div class="form-group">
                <label>Diagnosis *</label>
                <textarea name="Diagnosis" class="form-control" rows="3">{{ $kunjungan['diagnosis'] ?? '' }}</textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update
                </button>
                <a href="/kunjungan" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Modal Dokter -->
<div class="modal fade" id="dokterModal" tabindex="-1" role="dialog" aria-labelledby="dokterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dokterModalLabel">
                    Cari dan Pilih Dokter
                    <small class="text-muted">(Hanya menampilkan dokter yang praktek pada {{ $today }})</small>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    Hanya menampilkan dokter yang berpraktik pada hari {{ $today }}
                </div>

                <div class="form-group">
                    <input type="text" id="searchDokter" class="form-control" placeholder="Cari berdasarkan nama atau spesialisasi...">
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="tabelDokter">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID Dokter</th>
                                <th>Nama</th>
                                <th>Spesialisasi</th>
                                <th>Hari Praktek</th>
                                <th>Jam Praktek</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="dokterTableBody">
                            @foreach($dokters as $dokter)
                            <tr>
                                <td>{{ $dokter['id_Dokter'] ?? 'N/A' }}</td>
                                <td>{{ $dokter['nama'] ?? 'N/A' }}</td>
                                <td>{{ $dokter['spesialisasi'] ?? 'N/A' }}</td>
                                <td>{{ $dokter['hariPraktek'] ?? 'N/A' }}</td>
                                <td>{{ ($dokter['jamMulai'] ?? '') . ' - ' . ($dokter['jamSelesai'] ?? '') }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm pilih-dokter"
                                        data-id-dokter="{{ $dokter['id_Dokter'] }}"
                                        data-nama-dokter="{{ $dokter['nama'] }}"
                                        data-spesialisasi="{{ $dokter['spesialisasi'] }}">
                                        Pilih
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if(count($dokters) == 0)
                    <div class="alert alert-warning text-center">
                        Tidak ada dokter yang berpraktik pada hari {{ $today }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Klinik -->
<div class="modal fade" id="klinikModal" tabindex="-1" role="dialog" aria-labelledby="klinikModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="klinikModalLabel">
                    Pilih Klinik
                    <small class="text-muted">(Klinik yang tersedia untuk dokter hari {{ $today }})</small>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    Menampilkan klinik yang tersedia untuk dokter yang berpraktik pada hari {{ $today }}
                </div>

                @if(count($kliniks) == 0)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    Tidak ada klinik yang tersedia untuk dokter hari ini. Silakan pilih dari semua klinik.
                </div>
                @endif

                <div class="form-group">
                    <input type="text" id="searchKlinik" class="form-control" placeholder="Cari berdasarkan nama atau jenis klinik...">
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="tabelKlinik">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID Klinik</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Gedung</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="klinikTableBody">
                            @foreach($kliniks as $klinik)
                            <tr>
                                <td>{{ $klinik['id_Klinik'] ?? 'N/A' }}</td>
                                <td>{{ $klinik['nama'] ?? 'N/A' }}</td>
                                <td>{{ $klinik['jenis'] ?? 'N/A' }}</td>
                                <td>{{ $klinik['gedung'] ?? 'N/A' }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm pilih-klinik"
                                        data-id-klinik="{{ $klinik['id_Klinik'] }}"
                                        data-nama-klinik="{{ $klinik['nama'] }}"
                                        data-jenis="{{ $klinik['jenis'] }}">
                                        Pilih
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if(count($kliniks) == 0)
                    <div class="alert alert-warning text-center">
                        Tidak ada klinik yang tersedia untuk hari {{ $today }}.
                        Silakan hubungi administrator untuk menambahkan jadwal klinik.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Setup pencarian
    function setupSearch(searchInputId, tableId, searchColumns) {
        $(`#${searchInputId}`).on('keyup', function() {
            const value = $(this).val().toLowerCase();
            $(`#${tableId} tbody tr`).filter(function() {
                let match = false;
                searchColumns.forEach(col => {
                    if ($(this).find('td').eq(col).text().toLowerCase().indexOf(value) > -1) {
                        match = true;
                    }
                });
                $(this).toggle(match);
            });
        });
    }

    // Inisialisasi pencarian
    setupSearch('searchDokter', 'tabelDokter', [0, 1, 2, 3]);
    setupSearch('searchKlinik', 'tabelKlinik', [0, 1, 2, 3]);

    // Event listeners untuk pemilihan data
    $(document).on('click', '.pilih-dokter', function() {
        $('#idDokter').val($(this).data('id-dokter'));
        $('#namaDokter').val($(this).data('nama-dokter'));
        $('#spesialisasi').val($(this).data('spesialisasi'));
        $('#dokterModal').modal('hide');
    });

    $(document).on('click', '.pilih-klinik', function() {
        $('#idKlinik').val($(this).data('id-klinik'));
        $('#namaKlinik').val($(this).data('nama-klinik'));
        $('#jenis').val($(this).data('jenis'));
        $('#klinikModal').modal('hide');
    });

    // Inisialisasi DataTables
    $('#tabelDokter, #tabelKlinik').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "searching": false,
        "lengthChange": false,
        "pageLength": 5
    });
});
</script>
@endsection
