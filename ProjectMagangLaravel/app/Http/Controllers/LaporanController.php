<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\LaporanExportPDF;
use App\Exports\LaporanExport;
use GuzzleHttp\Client;
use App\Services\ApiService;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function laporan(Request $request)
    {
        $client = new Client();

        try {
            // Gunakan endpoint untuk kunjungan ALL
            $response = $client->get('http://localhost:5022/Kunjungan/All', [
                'timeout' => 30,
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]);

            $laporan = json_decode($response->getBody(), true);

            if (!is_array($laporan)) {
                $laporan = [];
                session()->flash('alert-warning', 'Format data laporan tidak valid');
            }

            // Filter data berdasarkan pencarian
            $searchBulan = $request->get('bulan');
            $searchDokter = $request->get('dokter');
            $searchKlinik = $request->get('klinik');

            if ($searchBulan || $searchDokter || $searchKlinik) {
                $laporan = array_filter($laporan, function($item) use ($searchBulan, $searchDokter, $searchKlinik) {
                    $match = true;

                    // Filter berdasarkan bulan (format: YYYY-MM)
                    if ($searchBulan) {
                        $tanggalKunjungan = $item['tanggal'] ?? $item['Tanggal'] ?? '';
                        $bulanKunjungan = date('Y-m', strtotime($tanggalKunjungan));
                        $match = $match && ($bulanKunjungan === $searchBulan);
                    }

                    // Filter berdasarkan nama dokter
                    if ($searchDokter && $match) {
                        $namaDokter = $item['dokter']['nama'] ?? '';
                        $match = $match && (stripos($namaDokter, $searchDokter) !== false);
                    }

                    // Filter berdasarkan nama klinik
                    if ($searchKlinik && $match) {
                        $namaKlinik = $item['klinik']['nama'] ?? '';
                        $match = $match && (stripos($namaKlinik, $searchKlinik) !== false);
                    }

                    return $match;
                });
            }

            // Get unique values for dropdown suggestions
            $bulanList = [];
            $dokterList = [];
            $klinikList = [];

            foreach ($laporan as $item) {
                // Extract bulan
                $tanggal = $item['tanggal'] ?? $item['Tanggal'] ?? '';
                if ($tanggal) {
                    $bulan = date('Y-m', strtotime($tanggal));
                    $bulanLabel = date('F Y', strtotime($tanggal));
                    if (!in_array(['value' => $bulan, 'label' => $bulanLabel], $bulanList)) {
                        $bulanList[] = ['value' => $bulan, 'label' => $bulanLabel];
                    }
                }

                // Extract dokter
                $dokterNama = $item['dokter']['nama'] ?? '';
                if ($dokterNama && !in_array($dokterNama, $dokterList)) {
                    $dokterList[] = $dokterNama;
                }

                // Extract klinik
                $klinikNama = $item['klinik']['nama'] ?? '';
                if ($klinikNama && !in_array($klinikNama, $klinikList)) {
                    $klinikList[] = $klinikNama;
                }
            }

            // Sort lists
            usort($bulanList, function($a, $b) {
                return strtotime($b['value']) - strtotime($a['value']);
            });
            sort($dokterList);
            sort($klinikList);

            return view("laporan", [
                "key" => "laporan",
                "lp" => array_values($laporan),
                "bulanList" => $bulanList,
                "dokterList" => $dokterList,
                "klinikList" => $klinikList,
                "searchBulan" => $searchBulan,
                "searchDokter" => $searchDokter,
                "searchKlinik" => $searchKlinik
            ]);

        } catch (\Exception $e) {
            session()->flash('alert-danger', 'Gagal mengambil data laporan: ' . $e->getMessage());
            return view("laporan", [
                "key" => "laporan",
                "lp" => [],
                "bulanList" => [],
                "dokterList" => [],
                "klinikList" => [],
                "searchBulan" => '',
                "searchDokter" => '',
                "searchKlinik" => ''
            ]);
        }
    }

    public function exportlaporanpdf(Request $request)
    {
        $searchBulan = $request->get('bulan');
        $searchDokter = $request->get('dokter');
        $searchKlinik = $request->get('klinik');

        // Buat nama file berdasarkan filter yang digunakan
        $fileName = 'laporan-kunjungan';

        if ($searchBulan) {
            $fileName .= '-' . date('F-Y', strtotime($searchBulan . '-01'));
        }
        if ($searchDokter) {
            $fileName .= '-dokter-' . substr(str_replace(' ', '-', $searchDokter), 0, 15);
        }
        if ($searchKlinik) {
            $fileName .= '-klinik-' . substr(str_replace(' ', '-', $searchKlinik), 0, 15);
        }

        // Jika tidak ada filter, tambahkan label "semua-data"
        if (!$searchBulan && !$searchDokter && !$searchKlinik) {
            $fileName .= '-semua-data';
        }

        $fileName .= '-' . date('Y-m-d') . '.pdf';

        return Excel::download(
            new LaporanExportPDF($searchBulan, $searchDokter, $searchKlinik),
            $fileName,
            \Maatwebsite\Excel\Excel::MPDF
        );
    }

    public function getFilteredDataForModal(Request $request)
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
                return response()->json(['data' => []]);
            }

            // Filter data
            $searchBulan = $request->get('bulan');
            $searchDokter = $request->get('dokter');
            $searchKlinik = $request->get('klinik');

            if ($searchBulan || $searchDokter || $searchKlinik) {
                $laporan = array_filter($laporan, function($item) use ($searchBulan, $searchDokter, $searchKlinik) {
                    $match = true;

                    // Filter berdasarkan bulan
                    if ($searchBulan) {
                        $tanggalKunjungan = $item['tanggal'] ?? $item['Tanggal'] ?? '';
                        if ($tanggalKunjungan) {
                            $bulanKunjungan = date('Y-m', strtotime($tanggalKunjungan));
                            $match = $match && ($bulanKunjungan === $searchBulan);
                        } else {
                            $match = false;
                        }
                    }

                    // Filter berdasarkan dokter
                    if ($searchDokter && $match) {
                        $namaDokter = $item['dokter']['nama'] ?? '';
                        $match = $match && (stripos($namaDokter, $searchDokter) !== false);
                    }

                    // Filter berdasarkan klinik
                    if ($searchKlinik && $match) {
                        $namaKlinik = $item['klinik']['nama'] ?? '';
                        $match = $match && (stripos($namaKlinik, $searchKlinik) !== false);
                    }

                    return $match;
                });
            }

            return response()->json([
                'success' => true,
                'data' => array_values($laporan),
                'total' => count($laporan),
                'filters' => [
                    'bulan' => $searchBulan,
                    'dokter' => $searchDokter,
                    'klinik' => $searchKlinik
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching data: ' . $e->getMessage(),
                'data' => []
            ]);
        }
    }
}
