@extends("Layouts.page")
@section("title","home")
@section('home')

<!-- Carousel dan Informasi -->
<div id="carouselExampleSlidesOnly" class="carousel slide mt-4" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ asset('images/antri.jpg') }}" class="d-block w-100" alt="rekamMedis" style="height: 300px; object-fit: cover;">
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body text-center">
            <h1 class="mb-4" style="font-weight: bold;">Selamat Datang Di Web Rekam Medis</h1>
            <h3 class="mb-2" style="font-weight: bold;">Rumah Sakit Bethesda Yogyakarta</h3>
            </div>
        </div>
    </div>
</div>

<div class="card mb-12" style="max-width: auto;">
    <div class="row no-gutters">
        <div class="col-md-3" style="max-width: 10; height: 220px;">
            <img src="{{ asset('images/periksa.png') }}" class="d-block w-100" alt="rekamMedis" style="height: 220px; object-fit: cover;">
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <h3 class="text-left mb-4" style="font-weight: bold;">Apa itu Web Layanan Rekam Medis</h3>
                <p class="card-text">Web Layanan Rekam Medis adalah sebuah platform berbasis web yang digunakan untuk mengelola dan menyimpan data rekam medis pasien secara digital. Sistem ini memungkinkan tenaga medis, rumah sakit, klinik, atau fasilitas kesehatan lainnya untuk mencatat, mengakses, dan mengelola informasi kesehatan pasien dengan lebih efisien dan aman.</p>
            </div>
        </div>
    </div>
</div>

@endsection
