<?php

namespace App\Exports;

use App\KunjunganLama;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use GuzzleHttp\Client;

class KunjunganLamaExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $client = new Client();

        try {
            $response = $client->get('http://localhost:5022/Kunjungan/Lama');
            $kunjungan = json_decode($response->getBody(), true);

            return collect($kunjungan);
        } catch (\Exception $e) {
            return collect([]);
        }
    }

        public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'No Urut',
            'No Rekam Medis',
            'Nama Pasien',
            'Tanggal Lahir',
            'Id Klinik',
            'Klinik',
            'Jenis Klinik',
            'Id Dokter',
            'Dokter',
            'Spesialisasi',
            'Keluhan',
            'Diagnosis'
        ];
    }

    public function map($kunjungan): array
    {
        return [
            $kunjungan['id_Kunjungan'] ?? '-',
            $kunjungan['tanggal'] ?? '-',
            $kunjungan['noAntrian'] ?? '-',
            $kunjungan['rekamMedis']['id_RekamMedis'] ?? '-',
            $kunjungan['rekamMedis']['nama'] ?? '-',
            $kunjungan['rekamMedis']['tanggalLahir'] ?? '-',
            $kunjungan['klinik']['id_Klink'] ?? '-',
            $kunjungan['klinik']['nama'] ?? '-',
            $kunjungan['klinik']['jenis'] ?? '-',
            $kunjungan['dokter']['id_Dokter'] ?? '-',
            $kunjungan['dokter']['nama'] ?? '-',
            $kunjungan['dokter']['spesialisasi'] ?? '-',
            $kunjungan['keluhan'] ?? '-',
            $kunjungan['diagnosis'] ?? '-'
        ];
    }
}
