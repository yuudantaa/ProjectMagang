<?php

namespace App\Exports;

use App\Klinik;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use GuzzleHttp\Client;

class KlinikExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $client = new Client();

        try {
            $response = $client->get('http://localhost:5022/klinik');
            $klinik = json_decode($response->getBody(), true);

            return collect($klinik);
        } catch (\Exception $e) {
            return collect([]);
        }
    }

        public function headings(): array
    {
        return [
                    'Id_Klinik',
                    'Nama' ,
                    'Jenis',
                    'Gedung',
                    'Lantai',
                    'Kapasitas',
                    'Keterangan',
        ];
    }

    public function map($klinik): array
    {
        return [
            $klinik['id_Klinik'] ?? '-',
            $klinik['nama'] ?? '-',
            $klinik['jenis'] ?? '-',
            $klinik['gedung'] ?? '-',
            $klinik['lantai'] ?? '-',
            $klinik['kapasitas'] ?? '-',
            $klinik['keterangan'] ?? '-',
        ];
    }
}
