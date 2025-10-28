<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use GuzzleHttp\Client;

class LaporanExportPDF implements FromView, ShouldAutoSize, WithTitle
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

    public function view(): View
    {
        $laporan = $this->getFilteredData();

        return view('exports.laporan-pdf', [
            'laporan' => $laporan,
            'searchBulan' => $this->searchBulan,
            'searchDokter' => $this->searchDokter,
            'searchKlinik' => $this->searchKlinik,
            'totalData' => count($laporan)
        ]);
    }

    public function title(): string
    {
        return 'Laporan Kunjungan';
    }

    protected function getFilteredData()
    {
        $client = new Client();

        try {
            $response = $client->get('http://localhost:5022/Kunjungan/All', [
                'timeout' => 30,
                'headers' => ['Accept' => 'application/json']
            ]);

            $laporan = json_decode($response->getBody(), true);

            // Handle response structure
            if (isset($laporan['data'])) {
                $laporan = $laporan['data'];
            } elseif (isset($laporan['result'])) {
                $laporan = $laporan['result'];
            }

            if (!is_array($laporan)) {
                return [];
            }

            // Filter data hanya jika ada parameter pencarian
            if ($this->searchBulan || $this->searchDokter || $this->searchKlinik) {
                $laporan = array_filter($laporan, function($item) {
                    $match = true;

                    // Filter berdasarkan bulan
                    if ($this->searchBulan) {
                        $tanggalKunjungan = $item['tanggal'] ?? $item['Tanggal'] ?? '';
                        if ($tanggalKunjungan) {
                            $bulanKunjungan = date('Y-m', strtotime($tanggalKunjungan));
                            $match = $match && ($bulanKunjungan === $this->searchBulan);
                        } else {
                            $match = false;
                        }
                    }

                    // Filter berdasarkan dokter
                    if ($this->searchDokter && $match) {
                        $namaDokter = $item['dokter']['nama'] ?? '';
                        $match = $match && (stripos($namaDokter, $this->searchDokter) !== false);
                    }

                    // Filter berdasarkan klinik
                    if ($this->searchKlinik && $match) {
                        $namaKlinik = $item['klinik']['nama'] ?? '';
                        $match = $match && (stripos($namaKlinik, $this->searchKlinik) !== false);
                    }

                    return $match;
                });
            }

            // Jika tidak ada filter, kembalikan semua data
            return array_values($laporan);

        } catch (\Exception $e) {
            \Log::error('Error fetching data for PDF export: ' . $e->getMessage());
            return [];
        }
    }
}
