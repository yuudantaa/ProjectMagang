<?php

namespace App\Exports;

use App\Kunjungan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use GuzzleHttp\Client;

class KunjunganExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $client = new Client();

        try {
            $response = $client->get('http://localhost:5022/Kunjungan/Baru');
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
