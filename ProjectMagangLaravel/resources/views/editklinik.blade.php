@extends("Layouts/page")
@section("title","form edit klinik")
@section("klinik")
<div class="card">
    <div class="card-header">
        <h3>Edit Data Klinik</h3>
    </div>
    <div class="card-body">
        <form action="/update-klinik/{{ $klinik['id_Klinik'] }}" method="post">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>ID Klinik</label>
                <input type="text" class="form-control" value="{{ $klinik['id_Klinik'] }}" readonly>
                <input type="hidden" name="Id_Klinik" value="{{ $klinik['id_Klinik'] }}">
            </div>

            <div class="form-group">
                <label>Nama Klinik</label>
                <input type="text" name="nama" class="form-control" value="{{ $klinik['nama'] }}" readonly>
            </div>
            <div class="form-group">
                <label>Jenis</label>
                <select class="form-control" name="jenis">
                    <option value="BPJS" {{ $klinik['jenis'] == 'BPJS' ? 'selected' : '' }}>BPJS </option>
                    <option value="Non-BPJS" {{ $klinik['jenis'] == 'Non-BPJS' ? 'selected' : '' }}>Non BPJS </option>
                    <option value="VIP" {{ $klinik['jenis'] == 'VIP' ? 'selected' : '' }}>VIP 3</option>
                </select>
            </div>

            <div class="form-group">
                <label>Gedung</label>
                <select class="form-control" name="gedung">
                    <option value="Gedung_1" {{ $klinik['gedung'] == 'Gedung_1' ? 'selected' : '' }}>Gedung 1</option>
                    <option value="Gedung_2" {{ $klinik['gedung'] == 'Gedung_2' ? 'selected' : '' }}>Gedung 2</option>
                    <option value="Gedung_3"{{ $klinik['gedung'] == 'Gedung_3' ? 'selected' : '' }}>Gedung 3</option>
                </select>
            </div>
            <div class="form-group">
                <label>Lantai</label>
                <select class="form-control" name="lantai">
                    <option value="Lantai_1A" {{ $klinik['lantai'] == 'Lantai_1A' ? 'selected' : '' }}>Lantai 1A</option>
                    <option value="Lantai_1b" {{ $klinik['lantai'] == 'Lantai_1b' ? 'selected' : '' }}>Lantai 1B</option>
                    <option value="Lantai_2a" {{ $klinik['lantai'] == 'Lantai_2a' ? 'selected' : '' }}>Lantai 2A</option>
                    <option value="Lantai_2b" {{ $klinik['lantai'] == 'Lantai_2b' ? 'selected' : '' }}>Lantai 2B</option>
                    <option value="Lantai_3a" {{ $klinik['lantai'] == 'Lantai_3a' ? 'selected' : '' }}>Lantai 3A</option>
                    <option value="Lantai_3b" {{ $klinik['lantai'] == 'Lantai_3b' ? 'selected' : '' }}>Lantai 3B</option>
                </select>
            </div>
            <div class="form-group">
                <label>Kapasitas</label>
                <input type="number" name="kapasitas" class="form-control" value="{{ $klinik['kapasitas'] }}" required>
            </div>
            <div class="form-group">
                <label>Keterangan</label>
                <input type="text" name="keterangan" class="form-control" value="{{ $klinik['keterangan'] }}" >
            </div>
            <div class="form-group">
                    <button type="submit" class="btn btn-primary">simpan</button>
                </div>
        </form>
    </div>
</div>
@endsection
