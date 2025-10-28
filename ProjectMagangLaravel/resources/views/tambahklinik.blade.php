@extends("Layouts/page")
@section("title","form tambah klinik")
@section("klinik")
<div class="card">
    <div class="card-header">
         <h3>Tambah Klinik Baru</h3>
    </div>

    <div class="card-body">
        <form action="/save-klinik" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Nama Klinik</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Jenis</label>
                <select class="form-control" name="jenis">
                    <option value="BPJS">BPJS </option>
                    <option value="NonBPJS">Non BPJS </option>
                    <option value="VIP">VIP 3</option>
                </select>
            </div>

            <div class="form-group">
                <label>Gedung</label>
                <select class="form-control" name="gedung">
                    <option value="Gedung1">Gedung 1</option>
                    <option value="Gedung2">Gedung 2</option>
                    <option value="Gedung3">Gedung 3</option>
                </select>
            </div>
            <div class="form-group">
                <label>Lantai</label>
                <select class="form-control" name="lantai">
                    <option value="Lantai1a">Lantai 1A</option>
                    <option value="Lantai1b">Lantai 1B</option>
                    <option value="Lantai2a">Lantai 2A</option>
                    <option value="Lantai2b">Lantai 2B</option>
                    <option value="Lantai3a">Lantai 3A</option>
                    <option value="Lantai3b">Lantai 3B</option>
                </select>
            </div>
            <div class="form-group">
                <label>Kapasitas</label>
                <input type="number" name="kapasitas" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Keterangan</label>
                <input type="text" name="keterangan" class="form-control" >
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
                <a href="/klinik" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
