@extends("Layouts.page")
@section("title","laporan")
@section('laporan')

<div class="card-header">
    <h4>Laporan Kunjungan</h4>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#previewModal">
        <i class="bi bi-eye"></i> Preview & Cetak
    </button>

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
                Menampilkan {{ count($lp) }} data
            </span>
        </div>
    </div>

    <table id="laporanTable" class="table table-striped table-bordered" style="width:100%">
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
                <th>Spesialisasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lp as $idx => $l)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>{{ $l['id_Kunjungan'] ?? '-' }}</td>
                    <td>{{ isset($l['tanggal']) ? date('d/m/Y', strtotime($l['tanggal'])) : '-' }}</td>
                    <td>{{ $l['rekamMedis']['id_RekamMedis'] ?? '-' }}</td>
                    <td>{{ $l['rekamMedis']['nama'] ?? '-' }}</td>
                    <td>{{ $l['noAntrian'] ?? '-' }}</td>
                    <td>{{ $l['klinik']['nama'] ?? '-' }}</td>
                    <td>{{ $l['dokter']['nama'] ?? '-' }}</td>
                    <td>{{ $l['dokter']['spesialisasi'] ?? '-' }}</td>
                    <td>
                        <a href="/kunjungan/{{ $l['id_Kunjungan'] }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Preview & Print Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Preview Laporan Kunjungan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Filter Summary -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <h6>Filter yang diterapkan:</h6>
                            <ul class="mb-0">
                                <li>Bulan: <strong>{{ $searchBulan ? date('F Y', strtotime($searchBulan . '-01')) : 'Semua' }}</strong></li>
                                <li>Dokter: <strong>{{ $searchDokter ?: 'Semua' }}</strong></li>
                                <li>Klinik: <strong>{{ $searchKlinik ?: 'Semua' }}</strong></li>
                                <li>Total Data: <strong>{{ count($lp) }} rekaman</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Preview Table -->
                <div class="table-responsive" id="printableArea">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>ID Kunjungan</th>
                                <th>Tanggal</th>
                                <th>No RM</th>
                                <th>Nama Pasien</th>
                                <th>No Urut</th>
                                <th>Klinik</th>
                                <th>Dokter</th>
                                <th>Spesialisasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lp as $idx => $l)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>{{ $l['id_Kunjungan'] ?? '-' }}</td>
                                    <td>{{ isset($l['tanggal']) ? date('d/m/Y', strtotime($l['tanggal'])) : '-' }}</td>
                                    <td>{{ $l['rekamMedis']['id_RekamMedis'] ?? '-' }}</td>
                                    <td>{{ $l['rekamMedis']['nama'] ?? '-' }}</td>
                                    <td>{{ $l['noAntrian'] ?? '-' }}</td>
                                    <td>{{ $l['klinik']['nama'] ?? '-' }}</td>
                                    <td>{{ $l['dokter']['nama'] ?? '-' }}</td>
                                    <td>{{ $l['dokter']['spesialisasi'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if(count($lp) === 0)
                        <div class="alert alert-warning text-center">
                            Tidak ada data yang ditemukan dengan filter yang dipilih.
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="printPreview()">
                    <i class="bi bi-printer"></i> Cetak
                </button>
            </div>
        </div>
    </div>
</div>


<style>
    .form-group {
        margin-bottom: 1rem;
    }
    #resultCount {
        font-size: 0.9rem;
    }

    /* Print Styles */
    @media print {
        body * {
            visibility: hidden;
        }
        #printableArea, #printableArea * {
            visibility: visible;
        }
        #printableArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .table {
            font-size: 12px;
        }
        .alert-info {
            display: none;
        }
    }

    /* Modal specific styles */
    .modal-xl {
        max-width: 95%;
    }
</style>


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
        window.location.href = '/laporan?' + params.toString();
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
        window.location.href = '/laporan';
    });

    // Initialize DataTable
    $('#laporanTable').DataTable({
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

// Print Functionality
function printPreview() {
    const printContent = document.getElementById('printableArea').innerHTML;
    const originalContent = document.body.innerHTML;

    // Create print window
    const printWindow = window.open('', '_blank', 'width=800,height=600');

    // Build print document
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Laporan Kunjungan - {{ date('d-m-Y') }}</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                    font-size: 12px;
                }
                .print-header {
                    text-align: center;
                    margin-bottom: 20px;
                    border-bottom: 2px solid #333;
                    padding-bottom: 10px;
                }
                .print-header h2 {
                    margin: 0;
                    color: #333;
                }
                .filter-info {
                    background: #f8f9fa;
                    padding: 10px;
                    margin-bottom: 15px;
                    border-left: 4px solid #007bff;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 10px;
                }
                th {
                    background-color: #343a40;
                    color: white;
                    padding: 8px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                td {
                    padding: 6px;
                    border: 1px solid #ddd;
                }
                tr:nth-child(even) {
                    background-color: #f2f2f2;
                }
                .no-data {
                    text-align: center;
                    padding: 20px;
                    color: #6c757d;
                }
                @media print {
                    body { margin: 0; }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <div class="print-header">
                <h2>LAPORAN KUNJUNGAN PASIEN</h2>
                <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
            </div>

            <div class="filter-info">
                <strong>Filter yang diterapkan:</strong><br>
                Bulan: {{ $searchBulan ? date('F Y', strtotime($searchBulan . '-01')) : 'Semua' }} |
                Dokter: {{ $searchDokter ?: 'Semua' }} |
                Klinik: {{ $searchKlinik ?: 'Semua' }} |
                Total Data: {{ count($lp) }}
            </div>

            ${printContent}

            <div class="no-print" style="margin-top: 20px; text-align: center;">
                <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer;">
                    Cetak Dokumen
                </button>
                <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; cursor: pointer; margin-left: 10px;">
                    Tutup
                </button>
            </div>

            <script>
                // Auto-print option
                setTimeout(() => {
                    window.print();
                }, 500);
            <\/script>
        </body>
        </html>
    `);

    printWindow.document.close();
}

function exportToPDF() {
    const params = new URLSearchParams();
    @if($searchBulan) params.append('bulan', '{{ $searchBulan }}'); @endif
    @if($searchDokter) params.append('dokter', '{{ $searchDokter }}'); @endif
    @if($searchKlinik) params.append('klinik', '{{ $searchKlinik }}'); @endif

    window.location.href = '/export-laporan-pdf?' + params.toString();
    $('#previewModal').modal('hide');
}
</script>


@endsection
