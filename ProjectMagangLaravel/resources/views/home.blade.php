@extends("Layouts.page")
@section("title","home")
@section('home')

<!-- Carousel dan Informasi -->
<div id="carouselExampleSlidesOnly" class="carousel slide mt-4" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ asset('images/rekam.jpg') }}" class="d-block w-100" alt="rekamMedis" style="height: 300px; object-fit: cover;">
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body text-center">
            <h1 class="mb-4">Selamat Datang Di Web Rekam Medis</h1>
            <h3 class="mb-2">Rumah Sakit Bethesda Yogyakarta</h3>
            </div>
        </div>
    </div>
</div>

<!-- Header Informasi Tanggal -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body text-center">
                <h3 class="mb-1"><strong>{{ $stats['hari_ini'] }}, {{ date('d', strtotime($stats['tanggal_hari_ini'])) }} {{ $stats['bulan_tahun'] }}</strong></h3>
                <p class="mb-0 text-muted">Data Statistik Hari Ini</p>
            </div>
        </div>
    </div>
</div>

<!-- Cards Statistik -->
<div class="row mb-4">

    <!-- Card Kunjungan Hari Ini -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Kunjungan Hari Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_kunjungan'] ?? 0 }}</div>
                        <small class="text-muted">Total kunjungan</small>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-calendar-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Dokter Tersedia -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Dokter Tersedia</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_dokter'] ?? 0 }}</div>
                        <small class="text-muted">Praktek hari {{ $stats['hari_ini'] }}</small>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-person-badge fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Klinik Aktif -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Klinik Aktif</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_klinik_aktif'] ?? 0 }}</div>
                        <small class="text-muted">Melayani hari ini</small>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-building fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Data Detail Hari Ini -->
<div class="row">
<!-- Tabel Dokter Tersedia -->
<div class="col-lg-6 mb-4">
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-info">
                <i class="bi bi-person-badge"></i> Dokter Tersedia Hari Ini
            </h6>
            <span class="badge badge-info">{{ $stats['total_dokter'] }} Dokter</span>
        </div>
        <div class="card-body">
            @if($stats['total_dokter'] > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama Dokter</th>
                            <th>Spesialisasi</th>
                            <th>Jam Praktek</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(array_slice($stats['dokter_tersedia'], 0, 5) as $dokter)
                        <tr>
                            <td>{{ $dokter['nama'] ?? 'N/A' }}</td>
                            <td>{{ $dokter['spesialisasi'] ?? 'N/A' }}</td>
                            <td>{{ ($dokter['jamMulai'] ?? '') . ' - ' . ($dokter['jamSelesai'] ?? '') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($stats['total_dokter'] > 5)
            <div class="text-center mt-2">
                <small class="text-muted">Menampilkan 5 dari {{ $stats['total_dokter'] }} dokter</small>
            </div>
            @endif
            @else
            <div class="text-center py-4">
                <i class="bi bi-person-x fa-3x text-muted mb-2"></i>
                <p class="text-muted">Tidak ada dokter yang berpraktik hari ini</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Tabel Kunjungan Per Klinik -->
<div class="col-lg-6 mb-4">
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-warning">
                <i class="bi bi-building"></i> Kunjungan Per Klinik
            </h6>
            <span class="badge badge-warning">{{ $stats['total_klinik_aktif'] }} Klinik</span>
        </div>
        <div class="card-body">
            @if($stats['total_klinik_aktif'] > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama Klinik</th>
                            <th>Jenis</th>
                            <th>Jumlah Kunjungan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(array_slice($stats['kunjungan_per_klinik'], 0, 5) as $klinikData)
                        <tr>
                            <td>{{ $klinikData['klinik']['nama'] ?? 'N/A' }}</td>
                            <td>{{ $klinikData['klinik']['jenis'] ?? 'N/A' }}</td>
                            <td class="text-center">
                                <span class="badge badge-primary">{{ $klinikData['jumlah'] }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(count($stats['kunjungan_per_klinik']) > 5)
            <div class="text-center mt-2">
                <small class="text-muted">Menampilkan 5 dari {{ count($stats['kunjungan_per_klinik']) }} klinik</small>
            </div>
            @endif
            @else
            <div class="text-center py-4">
                <i class="bi bi-building fa-3x text-muted mb-2"></i>
                <p class="text-muted">Belum ada kunjungan ke klinik hari ini</p>
            </div>
            @endif
        </div>
    </div>
</div>
</div>

</br>
<div class="card mb-12" style="max-width: auto;">
    <div class="row no-gutters">
        <div class="col-md-3" style="max-width: 10; height: 220px;">
            <img src="{{ asset('images/RekamMedis.jpg') }}" class="d-block w-100" alt="rekamMedis" style="height: 220px; object-fit: cover;">
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <h3 class="text-left mb-4" style="font-weight: bold;">Apa itu Web Layanan Rekam Medis</h3>
                <p class="card-text">Web Layanan Rekam Medis adalah sebuah platform berbasis web yang digunakan untuk mengelola dan menyimpan data rekam medis pasien secara digital. Sistem ini memungkinkan tenaga medis, rumah sakit, klinik, atau fasilitas kesehatan lainnya untuk mencatat, mengakses, dan mengelola informasi kesehatan pasien dengan lebih efisien dan aman.</p>
            </div>
        </div>
    </div>
</div>

<!-- CSS Tambahan -->
<style>
    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
    }

    .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
    .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
    .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
    .border-left-warning { border-left: 0.25rem solid #f6c23e !important; }

    .shadow { box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important; }
    .text-xs { font-size: 0.7rem; }

    .badge { font-size: 0.7rem; }
    .table th { background-color: #f8f9fa; font-weight: 600; }

    .btn-block {
        padding: 15px 10px;
        height: 80px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .btn-block i {
        font-size: 1.5rem;
        margin-bottom: 5px;
    }
</style>

<!-- JavaScript untuk Auto Refresh (Opsional) -->
<script>
    // Auto refresh setiap 5 menit (300000 ms)
    setTimeout(function() {
        window.location.reload();
    }, 300000);

    // Update waktu real-time
    function updateTime() {
        const now = new Date();
        const timeElement = document.querySelector('.current-time');
        if (timeElement) {
            timeElement.textContent = now.toLocaleTimeString('id-ID');
        }
    }

    setInterval(updateTime, 1000);
    updateTime();
</script>

@endsection
