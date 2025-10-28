@extends("Layouts.page")
@section("title","home")
@section('home')

<!-- Carousel dan Informasi -->
<div id="carouselExampleSlidesOnly" class="carousel slide mt-4" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ asset('images/antri.jpg') }}" class="d-block w-100" alt="rekamMedis" style="height: 300px; object-fit: cover;">
            <div class="carousel-caption d-none d-md-block">
                <div style="background-color: rgba(0, 0, 0, 0.5); padding: 20px; border-radius: 15px; max-width: 90%; margin: 0 auto;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <h1 style="color: #fff; font-weight: bold; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">Selamat Datang Di Web Rekam Medis</h1>
                        <h3 style="color: #fff; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">Rumah Sakit Bethesda Yogyakarta</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</br>
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
