<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!--buat tabel-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css">
    <!--css icon-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <title>@yield('title', 'Rekam Medis')</title>

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/"><i class="bi bi-capsule-pill"> Website Rekam Medis </i></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav ml-auto">
        <li class="nav-item {{($key=="home") ? "active":""}}">
            <a class="nav-link" href="/"><i class="bi bi-house-door-fill"> Home </i> </a>
        </li>
        <li class="nav-item {{($key=="kunjungan") ? "active":""}}">
            <a class="nav-link" href="/kunjungan"><i class="bi bi-clipboard2-pulse-fill"> Kunjungan </i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{($key=="pasien") ? "active":""}}" href="/pasien"><i class="bi bi-person-standing"></i> Pasien </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{($key=="klinik") ? "active":""}}" href="/klinik"><i class="bi bi-hospital-fill"> Klinik </i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{($key=="dokter") ? "active":""}}" href="/dokter"><i class="bi bi-person-add"> Dokter </i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{($key=="laporan") ? "active":""}}" href="/laporan"><i class="bi bi-file-medical"> Laporan </i></a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
           <i class="bi bi-file-person"></i> {{ session('nama') ?? 'User' }}
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="#"><i class="bi bi-person-circle"></i> {{ session('nama') }}</a>
            <a class="dropdown-item" href="/logout"><i class="bi bi-box-arrow-left"></i> Keluar</a>
            </div>
        </li>
        </ul>
    </div>
    </nav>

    <div class="row">
        <div class="col-md-12 bg-primary py-2 d-flex align-items-center justify-content-between">
    </div>

<div class="col-md-12">
        <div class="card text-left">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link {{($key=="kunjungan") ? "active":""}}"" href="/kunjungan">Pasien Baru</a>
            </li>
            <li class="nav-item">
               <a class="nav-link {{($key=="kunjunganlama") ? "active":""}}"" href="/kunjungan-lama">Pasien Lama</a>
            </li>
            </ul>
        </div>
        <div class="card-body">
            @yield('kunjungan')
            @yield('kunjunganlama')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <!--buat table-->
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
    <script>
    new DataTable('#example');
    </script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
</body>
</html>
