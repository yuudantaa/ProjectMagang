<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!--buat tabel-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css">
    <!--css icon-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>@yield('title', 'Login')</title>
        <style>
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8); /* Efek buram */
            backdrop-filter: blur(5px); /* Efek blur modern */
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .spinner-container {
            text-align: center;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }
    </style>
    <title>Form Login</title>

  </head>
  <body>

  <div class="container-fluid">
    <div class="row shadow-sm">
        <div class="col-md-12 bg-white py-1  d-flex align-items-center justify-content-between ">
            <span class="badge badge-pill badge-white text-primary" style="font-size: 24px;">
                <i class="bi bi-capsule-pill"> Website Rekam Medis </i>
            </span>


        </div>
    </div>
        <div class="row">
        <div class="col-md-12 bg-primary py-2 d-flex align-items-center justify-content-between">
    </div>
</div>

    <div class="continer-fluid" style="margin-top:5%">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    @yield('login')
                </div>
            </div>
        </div>
    </div>

        <div id="loading-spinner" class="spinner-overlay" style="display: none;">
        <div class="spinner-container">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-2">Loading...</p>
        </div>
    </div>


     <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <!--buat table-->
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
    <script>
    new DataTable('#example');
    </script>

    <script>
    // Script untuk menangani spinner loading
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('a.nav-link, a.dropdown-item');
        const spinner = document.getElementById('loading-spinner');

        function showSpinner() {
            spinner.style.display = 'flex';
        }

        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.getAttribute('href') === '/logout' || this.getAttribute('target') === '_blank') {
                    return;
                }

                showSpinner();
            });
        });

        window.addEventListener('load', function() {
            spinner.style.display = 'none';
        });

        window.addEventListener('error', function() {
            spinner.style.display = 'none';
        });
    });
    </script>

</body>
</html>
