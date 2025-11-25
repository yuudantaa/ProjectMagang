@extends("Layouts/page")
@section("title","form edit dokter")
@section("dokter")
<div class="card">
    <div class="card-header">
        <h3>Edit Data Dokter</h3>
    </div>
    <div class="card-body">
        <form action="/update-dokter/{{ $dokter['id_Dokter'] }}" method="post">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>ID Dokter</label>
                <input type="text" class="form-control" value="{{ $dokter['id_Dokter'] }}" readonly>
                <input type="hidden" name="Id_Dokter" value="{{ $dokter['id_Dokter'] }}">
            </div>

            <div class="form-group">
                <label>Nama Dokter</label>
                <input type="text" name="nama" class="form-control" value="{{ $dokter['nama'] }}" required>
            </div>

            <div class="form-group">
                <label>Spesialisasi</label>
                <input type="text" name="spesialisasi" class="form-control" value="{{ $dokter['spesialisasi'] }}" required>
            </div>
         <div class="form-group">
                <label>Nomor Telepon</label>
                <input type="text" name="noHP" class="form-control" value="{{ $dokter['noHP'] }}" required maxlength="12">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ $dokter['email'] }}" required>
            </div>

            <div class="form-group">
                <label>Hari Praktek</label>
                <div class="row">
                    @php
                        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                        // Pisahkan string hari praktek menjadi array
                        $selectedDays = explode(',', $dokter['hariPraktek'] ?? '');
                        // Bersihkan spasi di setiap elemen
                        $selectedDays = array_map('trim', $selectedDays);
                    @endphp

                    @foreach($days as $day)
                    <div class="col-md-3 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="hariPraktek[]"
                                   value="{{ $day }}"
                                   id="hari{{ $day }}"
                                   {{ in_array($day, $selectedDays) ? 'checked' : '' }}>
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
                <input type="time" name="jamMulai" class="form-control" value="{{ $dokter['jamMulai'] }}" required>
            </div>
            <div class="form-group">
                <label>Jam Selesai Praktek</label>
                <input type="time" name="jamSelesai" class="form-control" value="{{ $dokter['jamSelesai'] }}" required>
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
