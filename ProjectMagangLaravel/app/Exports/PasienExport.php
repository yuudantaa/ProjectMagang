<?php

namespace App\Exports;

use App\Pasien;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use GuzzleHttp\Client;

class PasienExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $client = new Client();

        try {
            $response = $client->get('http://localhost:5022/pasien');
            $pasien = json_decode($response->getBody(), true);

            return collect($pasien);
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    public function headings(): array
    {
        return [
                    'Id Rekam Medis',
                    'No KTP',
                    'Nama',
                    'Tempat Lahir',
                    'Tanggal Lahir',
                    'Agama',
                    'Gender',
                    'Status Kawin',
                    'Pendidikan',
                    'Pekerjaan',
                    'Butuh Penerjemah',
                    'Umur',
                    'Bulan',
                    'Bahasa',
                    'NoTelp',
                    'Email',
                    'Alamat',
                    'Kecamatan',
                    'Kabupaten' ,
                    'Provinsi'
        ];
    }

    public function map($pasien): array
    {
        return [
            $pasien['id_RekamMedis'] ?? '-',
            $pasien['noKTP'] ?? '-',
            $pasien['Nama'] ?? '-',
            $pasien['tempatLahir'] ?? '-',
            $pasien['tanggalLahir'] ?? '-',
            $pasien['agama'] ?? '-',
            $pasien['gender'] ?? '-',
            $pasien['statusKawin'] ?? '-',
            $pasien['pendidikan'] ?? '-',
            $pasien['pekerjaan'] ?? '-',
            $pasien['butuhPenerjemah'] ?? '-',
            $pasien['umur'] ?? '-',
            $pasien['bulan'] ?? '-',
            $pasien['bahasa'] ?? '-',
            $pasien['bulan'] ?? '-',
            $pasien['noTelp'] ?? '-',
            $pasien['email'] ?? '-',
            $pasien['alamat'] ?? '-',
            $pasien['kecamatan'] ?? '-',
            $pasien['kabupaten'] ?? '-',
            $pasien['provinsi'] ?? '-',
        ];
    }
}
