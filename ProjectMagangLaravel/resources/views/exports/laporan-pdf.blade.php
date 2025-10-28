<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kunjungan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            color: #333;
        }
        .filter-info {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN KUNJUNGAN PASIEN</h2>
        <p>Periode: {{ date('d F Y') }}</p>
    </div>

    <div class="filter-info">
        @if($searchBulan || $searchDokter || $searchKlinik)
        <strong>Filter yang digunakan:</strong><br>
        @if($searchBulan) • Bulan: {{ date('F Y', strtotime($searchBulan . '-01')) }}<br> @endif
        @if($searchDokter) • Dokter: {{ $searchDokter }}<br> @endif
        @if($searchKlinik) • Klinik: {{ $searchKlinik }}<br> @endif
        @else
        <strong>Menampilkan semua data (tanpa filter)</strong><br>
        @endif
        <strong>Total Data: {{ $totalData }}</strong>
    </div>

    <table>
        <thead>
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
            @if(count($laporan) > 0)
                @foreach($laporan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item['id_Kunjungan'] ?? '-' }}</td>
                    <td>{{ isset($item['tanggal']) ? date('d/m/Y', strtotime($item['tanggal'])) : '-' }}</td>
                    <td>{{ $item['rekamMedis']['id_RekamMedis'] ?? '-' }}</td>
                    <td>{{ $item['rekamMedis']['nama'] ?? '-' }}</td>
                    <td>{{ $item['noAntrian'] ?? '-' }}</td>
                    <td>{{ $item['klinik']['nama'] ?? '-' }}</td>
                    <td>{{ $item['dokter']['nama'] ?? '-' }}</td>
                    <td>{{ $item['dokter']['spesialisasi'] ?? '-' }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="9" class="no-data">Tidak ada data yang ditemukan</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
