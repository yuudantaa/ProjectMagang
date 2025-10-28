<?php

namespace App\Exports;

use App\Dokter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use GuzzleHttp\Client;

class DokterExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $client = new Client();

        try {
            $response = $client->get('http://localhost:5022/dokter');
            $dokter = json_decode($response->getBody(), true);

            return collect($dokter);
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    public function headings(): array
    {
        return [
                    'Id_Dokter',
                    'Nama',
                    'Spesialisasi',
                    'NoHP',
                    'Email',
                    'HariPraktek',
                    'JamMulai',
                    'JamSelesai',
        ];
    }

    public function map($dokter): array
    {
        return [
            $dokter['id_Dokter'] ?? '-',
            $dokter['nama'] ?? '-',
            $dokter['spesialisasi'] ?? '-',
            $dokter['noHP'] ?? '-',
            $dokter['email'] ?? '-',
            $dokter['hariPraktek'] ?? '-',
            $dokter['jamMulai'] ?? '-',
            $dokter['jamSelesai'] ?? '-',
        ];
    }
}
