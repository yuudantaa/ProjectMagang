@extends("Layouts/page")
@section("title","form tambah dokter")
@section("dokter")
<div class="card">
       <div class="card-header">
         <h3>Tambah Dokter Baru</h3>
    </div>

    <div class="card-body">
        <form action="/save-dokter" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Nama Dokter</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Spesialisasi</label>
                <input type="text" name="spesialisasi" class="form-control" required>
            </div>
         <div class="form-group">
                <label>Nomor Telepon</label>
                <input type="text" name="noHP" class="form-control" required
                 pattern="\d*" inputmode="numeric" maxlength="12">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Hari Praktek</label>
                <div class="row">
                    @php
                        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                    @endphp

                    @foreach($days as $day)
                    <div class="col-md-3 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="hariPraktek[]"
                                   value="{{ $day }}"
                                   id="hari{{ $day }}">
                            <label class="form-check-label" for="hari{{ $day }}">
                                {{ $day }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label>Jam Mulai Praktek</label>
                <input type="time" name="jamMulai" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Jam Selesai Praktek</label>
                <input type="time" name="jamSelesai" class="form-control" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
                <a href="/dokter" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
