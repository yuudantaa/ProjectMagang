<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use GuzzleHttp\Client;

class LaporanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $searchBulan;
    protected $searchDokter;
    protected $searchKlinik;

    public function __construct($searchBulan = null, $searchDokter = null, $searchKlinik = null)
    {
        $this->searchBulan = $searchBulan;
        $this->searchDokter = $searchDokter;
        $this->searchKlinik = $searchKlinik;
    }

    public function collection()
    {
                $client = new Client();

        try {
            $response = $client->get('http://localhost:5022/Kunjungan/All', [
                'timeout' => 30,
                'headers' => ['Accept' => 'application/json']
            ]);

            $laporan = json_decode($response->getBody(), true);

            if (!is_array($laporan)) {
                return collect([]);
            }

            // Filter data berdasarkan parameter pencarian
            if ($this->searchBulan || $this->searchDokter || $this->searchKlinik) {
                $laporan = array_filter($laporan, function($item) {
                    $match = true;

                    // Filter berdasarkan bulan
                    if ($this->searchBulan) {
                        $tanggalKunjungan = $item['tanggal'] ?? $item['Tanggal'] ?? '';
                        $bulanKunjungan = date('Y-m', strtotime($tanggalKunjungan));
                        $match = $match && ($bulanKunjungan === $this->searchBulan);
                    }

                    // Filter berdasarkan nama dokter
                    if ($this->searchDokter && $match) {
                        $namaDokter = $item['dokter']['nama'] ?? '';
                        $match = $match && (stripos($namaDokter, $this->searchDokter) !== false);
                    }

                    // Filter berdasarkan nama klinik
                    if ($this->searchKlinik && $match) {
                        $namaKlinik = $item['klinik']['nama'] ?? '';
                        $match = $match && (stripos($namaKlinik, $this->searchKlinik) !== false);
                    }

                    return $match;
                });
            }

            return collect($laporan);

        } catch (\Exception $e) {
            return collect([]);
        }
    }

        public function headings(): array
    {
        return [
            'No',
            'ID Kunjungan',
            'Tanggal',
            'No Rekam Medis',
            'Nama Pasien',
            'No Urut',
            'Klinik',
            'Dokter',
            'Spesialisasi',
            'Keluhan',
            'Diagnosis'
        ];
    }

    public function map($laporan): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $laporan['id_Kunjungan'] ?? '-',
            isset($laporan['tanggal']) ? date('d/m/Y', strtotime($laporan['tanggal'])) : '-',
            $laporan['rekamMedis']['id_RekamMedis'] ?? '-',
            $laporan['rekamMedis']['nama'] ?? '-',
            $laporan['noAntrian'] ?? '-',
            $laporan['klinik']['nama'] ?? '-',
            $laporan['dokter']['nama'] ?? '-',
            $laporan['dokter']['spesialisasi'] ?? '-',
            $laporan['keluhan'] ?? '-',
            $laporan['diagnosis'] ?? '-'
        ];
    }
}
