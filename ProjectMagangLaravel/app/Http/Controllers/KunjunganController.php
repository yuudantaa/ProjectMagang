<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Services\ApiService;

class KunjunganController extends Controller
{
    public function kunjungan(Request $request)
    {
        $client = new Client();

        try {
            // Gunakan endpoint untuk kunjungan ALL
            $response = $client->get('http://localhost:5022/Kunjungan/baru', [
                'timeout' => 30,
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]);

            $kunjungan = json_decode($response->getBody(), true);

            if (!is_array($kunjungan)) {
                $kunjungan = [];
                session()->flash('alert-warning', 'Format data kunjungan tidak valid');
            }

            // Filter data berdasarkan pencarian
            $searchBulan = $request->get('bulan');
            $searchDokter = $request->get('dokter');
            $searchKlinik = $request->get('klinik');
            $searchPasien = $request->get('rekamMedis');

            if ($searchBulan || $searchDokter || $searchKlinik || $searchPasien) {
                $kunjungan = array_filter($kunjungan, function($item) use ($searchBulan, $searchDokter, $searchKlinik, $searchPasien) {
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

                    // Filter berdasarkan nama pasien
                    if ($searchPasien && $match) {
                        $namaPasien = $item['rekamMedis']['nama'] ?? '';
                        $match = $match && (stripos($namaPasien, $searchPasien) !== false);
                    }

                    return $match;
                });
            }

            // Get unique values for dropdown suggestions
            $bulanList = [];
            $dokterList = [];
            $klinikList = [];
            $pasienList = [];
            foreach ($kunjungan as $item) {
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

                // Extract pasien
                $pasienNama = $item['rekamMedis']['nama'] ?? '';
                if ($pasienNama && !in_array($pasienNama, $pasienList)) {
                    $pasienList[] = $pasienNama;
                }
            }

            // Sort lists
            usort($bulanList, function($a, $b) {
                return strtotime($b['value']) - strtotime($a['value']);
            });
            sort($dokterList);
            sort($klinikList);
            sort($pasienList);

            return view("kunjungan", [
                "key" => "kunjungan",
                "ps" => array_values($kunjungan),
                "bulanList" => $bulanList,
                "dokterList" => $dokterList,
                "pasienList" => $pasienList,
                "klinikList" => $klinikList,
                "searchBulan" => $searchBulan,
                "searchDokter" => $searchDokter,
                "searchPasien" => $searchPasien,
                "searchKlinik" => $searchKlinik
            ]);

        } catch (\Exception $e) {
            session()->flash('alert-danger', 'Gagal mengambil data kunjungan: ' . $e->getMessage());
            return view("kunjungan", [
                "key" => "kunjungan",
                "ps" => [],
                "bulanList" => [],
                "dokterList" => [],
                "klinikList" => [],
                "pasienList" => [],
                "searchBulan" => '',
                "searchDokter" => '',
                "searchPasien" => '',
                "searchKlinik" => ''
            ]);
        }
    }

    public function kunjunganlama(Request $request)
    {
        $client = new Client();

        try {
            // Gunakan endpoint untuk kunjungan ALL
            $response = $client->get('http://localhost:5022/Kunjungan/lama', [
                'timeout' => 30,
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]);

            $kunjungan = json_decode($response->getBody(), true);

            if (!is_array($kunjungan)) {
                $kunjungan = [];
                session()->flash('alert-warning', 'Format data kunjungan tidak valid');
            }

            // Filter data berdasarkan pencarian
            $searchBulan = $request->get('bulan');
            $searchDokter = $request->get('dokter');
            $searchKlinik = $request->get('klinik');
            $searchPasien = $request->get('rekamMedis');

            if ($searchBulan || $searchDokter || $searchKlinik || $searchPasien) {
                $kunjungan = array_filter($kunjungan, function($item) use ($searchBulan, $searchDokter, $searchKlinik, $searchPasien) {
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

                    // Filter berdasarkan rekam medis pasien
                    if ($searchPasien && $match) {
                        $rekamMedis = $item['rekamMedis']['nama'] ?? '';
                        $match = $match && (stripos($rekamMedis, $searchPasien) !== false);
                    }
                    return $match;
                });
            }

            // Get unique values for dropdown suggestions
            $bulanList = [];
            $dokterList = [];
            $klinikList = [];
            $pasienList = [];

            foreach ($kunjungan as $item) {
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
                // Extract pasien
                $pasienNama = $item['rekamMedis']['nama'] ?? '';
                if ($pasienNama && !in_array($pasienNama, $pasienList)) {
                    $pasienList[] = $pasienNama;
                }
            }

            // Sort lists
            usort($bulanList, function($a, $b) {
                return strtotime($b['value']) - strtotime($a['value']);
            });
            sort($dokterList);
            sort($klinikList);
            sort($pasienList);

            return view("kunjunganlama", [
                "key" => "kunjunganlama",
                "ps" => array_values($kunjungan),
                "bulanList" => $bulanList,
                "dokterList" => $dokterList,
                "klinikList" => $klinikList,
                "pasienList" => $pasienList,
                "searchBulan" => $searchBulan,
                "searchDokter" => $searchDokter,
                "searchPasien" => $searchPasien,
                "searchKlinik" => $searchKlinik
            ]);

        } catch (\Exception $e) {
            session()->flash('alert-danger', 'Gagal mengambil data kunjungan lama: ' . $e->getMessage());
            return view("kunjunganlama", [
                "key" => "kunjunganlama",
                "ps" => [],
                "bulanList" => [],
                "dokterList" => [],
                "klinikList" => [],
                "pasienList" => [],
                "searchBulan" => '',
                "searchDokter" => '',
                "searchPasien" => '',
                "searchKlinik" => ''
            ]);
        }
    }

    public function simpankunjungan(Request $request)
    {
        $client = new \GuzzleHttp\Client();

        try {
            // Cek duplikasi sebelum mengirim ke API
            $checkResponse = $client->get('http://localhost:5022/kunjungan/check-duplicate', [
                'query' => [
                    'rekam_medis' => $request->Id_RekamMedis,
                    'dokter' => $request->Id_Dokter,
                    'klinik' => $request->Id_Klinik,
                    'tanggal' => $request->Tanggal
                ],
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'timeout' => 30
            ]);

            $checkResult = json_decode($checkResponse->getBody(), true);

            if ($checkResult['duplicate']) {
                return back()->with('alert-danger', 'Data kunjungan duplikat ditemukan! Kombinasi pasien, dokter, klinik, dan tanggal sudah ada dalam sistem.')->withInput();
            }

            $response = $client->post('http://localhost:5022/Kunjungan', [
                'json' => [
                    'Tanggal' => $request->Tanggal,
                    'Id_RekamMedis' => $request->Id_RekamMedis,
                    'Id_Klinik' => $request->Id_Klinik,
                    'Id_Dokter' => $request->Id_Dokter,
                    'NoAntrian' => (int)$request->NoAntrian,
                    'Keluhan' => $request->Keluhan,
                    'Diagnosis' => $request->Diagnosis,
                    'JenisKunjungan' => 'Baru'
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'timeout' => 30
            ]);

            if ($response->getStatusCode() === 201) {
                return redirect('/kunjungan')->with('alert-success', 'Data kunjungan berhasil disimpan');
            } else {
                return back()->with('alert-danger', 'Gagal menyimpan data kunjungan')->withInput();
            }

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $error = json_decode($response->getBody()->getContents(), true);
            return back()->with('alert-danger', $error['message'] ?? 'Gagal menyimpan data kunjungan')->withInput();
        } catch (\Exception $e) {
            return back()->with('alert-danger', 'Gagal menyimpan data kunjungan: ' . $e->getMessage())->withInput();
        }
    }

    public function simpankunjunganlama(Request $request)
    {
        $client = new \GuzzleHttp\Client();

        try {
            // Cek duplikasi sebelum mengirim ke API
            $checkResponse = $client->get('http://localhost:5022/kunjungan/check-duplicate', [
                'query' => [
                    'rekam_medis' => $request->Id_RekamMedis,
                    'dokter' => $request->Id_Dokter,
                    'klinik' => $request->Id_Klinik,
                    'tanggal' => $request->Tanggal
                ],
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'timeout' => 30
            ]);

            $checkResult = json_decode($checkResponse->getBody(), true);

            if ($checkResult['duplicate']) {
                return back()->with('alert-danger', 'Data kunjungan duplikat ditemukan! Kombinasi pasien, dokter, klinik, dan tanggal sudah ada dalam sistem.')->withInput();
            }

            $response = $client->post('http://localhost:5022/Kunjungan/PasienLama', [
                'json' => [
                    'Tanggal' => $request->Tanggal,
                    'Id_RekamMedis' => $request->Id_RekamMedis,
                    'Id_Klinik' => $request->Id_Klinik,
                    'Id_Dokter' => $request->Id_Dokter,
                    'NoAntrian' => (int)$request->NoAntrian,
                    'Keluhan' => $request->Keluhan,
                    'Diagnosis' => $request->Diagnosis,
                    'JenisKunjungan' => 'Lama'
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'timeout' => 30
            ]);

            if ($response->getStatusCode() === 201) {
                return redirect('/kunjungan-lama')->with('alert-success', 'Data kunjungan berhasil disimpan');
            } else {
                return back()->with('alert-danger', 'Gagal menyimpan data kunjungan')->withInput();
            }

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $error = json_decode($response->getBody()->getContents(), true);
            return back()->with('alert-danger', $error['message'] ?? 'Gagal menyimpan data kunjungan')->withInput();
        } catch (\Exception $e) {
            return back()->with('alert-danger', 'Gagal menyimpan data kunjungan: ' . $e->getMessage())->withInput();
        }
    }

    public function checkDuplicateKunjungan(Request $request)
    {
        $client = new Client();

        try {
            $response = $client->get('http://localhost:5022/kunjungan/check-duplicate', [
                'query' => [
                    'rekam_medis' => $request->rekam_medis,
                    'dokter' => $request->dokter,
                    'klinik' => $request->klinik,
                    'tanggal' => $request->tanggal
                ],
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'timeout' => 30
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['duplicate']) {
                $data['message'] = 'Sudah ada kunjungan untuk kombinasi pasien, dokter, klinik, dan tanggal ini. Silakan pilih data yang berbeda.';
            }

            return response()->json($data);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $error = json_decode($response->getBody()->getContents(), true);
            return response()->json([
                'duplicate' => false,
                'message' => $error['message'] ?? 'Error checking duplicate'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'duplicate' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function tambahkunjungan()
    {
        $client = new Client();
        $today = date('Y-m-d');
        $dayOfWeek = date('N');

        try {
            // Ambil semua dokter dari API
            $response = $client->get('http://localhost:5022/dokter');
            $allDokters = json_decode($response->getBody(), true);

            // Filter dokter yang berpraktik hari ini
            $daysMap = [
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
                7 => 'Minggu'
            ];

            $todayName = $daysMap[$dayOfWeek];
            $dokters = array_filter($allDokters, function($dokter) use ($todayName) {
                if (!isset($dokter['hariPraktek'])) return false;

                $hariPraktek = is_array($dokter['hariPraktek'])
                    ? $dokter['hariPraktek']
                    : explode(',', $dokter['hariPraktek']);

                return in_array($todayName, $hariPraktek);
            });

            // Data lainnya
            $response = $client->get('http://localhost:5022/klinik');
            $kliniks = json_decode($response->getBody(), true);

            $response = $client->get('http://localhost:5022/pasien');
            $pasiens = json_decode($response->getBody(), true);

            // Data pasien baru dari session (jika ada)
            $newPatient = session('new_patient_data');
            $patientAdded = session('patient_added_success');

            return view("tambahkunjungan", [
                "key" => "kunjungan",
                "dokters" => $dokters,
                "kliniks" => $kliniks,
                "pasiens" => $pasiens,
                "today" => $todayName,
                "showSuccessModal" => session('show_success_modal', false),
                "successMessage" => session('success_message', ''),
                "newPatient" => $newPatient,
                "patientAdded" => $patientAdded
            ]);

        } catch (\Exception $e) {
            session()->flash('alert', 'Gagal mengambil data: ' . $e->getMessage());
            return view("tambahkunjungan", [
                "key" => "kunjungan",
                "dokters" => [],
                "kliniks" => [],
                "pasiens" => [],
                "today" => "Hari ini",
                "newPatient" => null,
                "patientAdded" => false,
                "showSuccessModal" => false,
                "successMessage" => ''
            ]);
        }
    }

    public function tambahkunjunganlama()
    {
        $client = new Client();
        $today = date('Y-m-d');
        $dayOfWeek = date('N');

        try {
            // Ambil semua dokter dari API
            $response = $client->get('http://localhost:5022/dokter');
            $allDokters = json_decode($response->getBody(), true);

            // Filter dokter yang berpraktik hari ini
            $daysMap = [
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
                7 => 'Minggu'
            ];

            $todayName = $daysMap[$dayOfWeek];
            $dokters = array_filter($allDokters, function($dokter) use ($todayName) {
                if (!isset($dokter['hariPraktek'])) return false;

                $hariPraktek = is_array($dokter['hariPraktek'])
                    ? $dokter['hariPraktek']
                    : explode(',', $dokter['hariPraktek']);

                return in_array($todayName, $hariPraktek);
            });

            // Data lainnya
            $response = $client->get('http://localhost:5022/klinik');
            $kliniks = json_decode($response->getBody(), true);

            $response = $client->get('http://localhost:5022/pasien');
            $pasiens = json_decode($response->getBody(), true);

            return view("tambahkunjunganlama", [
                "key" => "kunjungan",
                "dokters" => $dokters,
                "kliniks" => $kliniks,
                "pasiens" => $pasiens,
                "today" => $todayName
            ]);
        } catch (\Exception $e) {
            session()->flash('alert', 'Gagal mengambil data: ' . $e->getMessage());
            return view("tambahkunjunganlama", [
                "key" => "kunjungan",
                "dokters" => [],
                "kliniks" => [],
                "pasiens" => [],
                "today" => "Hari ini"
            ]);
        }
    }

    public function editkunjungan($id)
    {
        $client = new Client();

        try {
            // Ambil data kunjungan yang akan diedit
            $response = $client->get("http://localhost:5022/kunjungan/{$id}");
            $kunjungan = json_decode($response->getBody(), true);

            // Ambil data untuk dropdown
            $response = $client->get('http://localhost:5022/dokter');
            $dokters = json_decode($response->getBody(), true);

            $response = $client->get('http://localhost:5022/klinik');
            $kliniks = json_decode($response->getBody(), true);

            $response = $client->get('http://localhost:5022/pasien');
            $pasiens = json_decode($response->getBody(), true);

            return view("editkunjungan", [
                "key" => "kunjungan",
                "kunjungan" => $kunjungan,
                "dokters" => $dokters,
                "kliniks" => $kliniks,
                "pasiens" => $pasiens
            ]);
        } catch (\Exception $e) {
            session()->flash('alert-danger', 'Gagal mengambil data: ' . $e->getMessage());
            return redirect('/kunjungan');
        }
    }

    public function editkunjunganlama($id)
    {
        $client = new Client();

        try {
            // Ambil data kunjungan lama yang akan diedit
            $response = $client->get("http://localhost:5022/Kunjungan/{$id}", [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'timeout' => 30
            ]);

            $kunjungan = json_decode($response->getBody(), true);

            // Ambil data untuk dropdown
            $responseDokter = $client->get('http://localhost:5022/dokter');
            $dokters = json_decode($responseDokter->getBody(), true);

            $responseKlinik = $client->get('http://localhost:5022/klinik');
            $kliniks = json_decode($responseKlinik->getBody(), true);

            $responsePasien = $client->get('http://localhost:5022/pasien');
            $pasiens = json_decode($responsePasien->getBody(), true);

            return view("editkunjunganlama", [
                "key" => "kunjungan",
                "kunjungan" => $kunjungan,
                "dokters" => $dokters,
                "kliniks" => $kliniks,
                "pasiens" => $pasiens
            ]);

        } catch (\Exception $e) {
            session()->flash('alert-danger', 'Gagal mengambil data: ' . $e->getMessage());
            return redirect('/kunjungan-lama');
        }
    }

    public function updatekunjungan($id, Request $request)
    {
        $client = new Client();

        try {
            $response = $client->put("http://localhost:5022/kunjungan", [
                'json' => [
                    'Id_Kunjungan' => $id,
                    'Tanggal' => $request->Tanggal,
                    'Id_RekamMedis' => $request->Id_RekamMedis,
                    'Id_Klinik' => $request->Id_Klinik,
                    'Id_Dokter' => $request->Id_Dokter,
                    'NoAntrian' => (int)$request->NoAntrian,
                    'Keluhan' => $request->Keluhan,
                    'Diagnosis' => $request->Diagnosis,
                    'JenisKunjungan' => 'Baru'
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'timeout' => 30
            ]);

            session()->flash('alert-success', 'Data kunjungan berhasil diupdate');
        } catch (\Exception $e) {
            session()->flash('alert-danger', 'Gagal update data: ' . $e->getMessage());
        }

        return redirect('/kunjungan');
    }

    public function updatekunjunganlama($id, Request $request)
    {
        $client = new Client();

        try {
            $response = $client->put("http://localhost:5022/Kunjungan/PasienLama", [
                'json' => [
                    'Id_Kunjungan' => $id,
                    'Tanggal' => $request->Tanggal,
                    'Id_RekamMedis' => $request->Id_RekamMedis,
                    'Id_Klinik' => $request->Id_Klinik,
                    'Id_Dokter' => $request->Id_Dokter,
                    'NoAntrian' => (int)$request->NoAntrian,
                    'Keluhan' => $request->Keluhan,
                    'Diagnosis' => $request->Diagnosis,
                    'JenisKunjungan' => 'Lama'
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'timeout' => 30
            ]);

            session()->flash('alert-success', 'Data kunjungan berhasil diupdate');
        } catch (\Exception $e) {
            session()->flash('alert-danger', 'Gagal update data: ' . $e->getMessage());
        }

        return redirect('/kunjungan-lama');
    }

    public function deletekunjungan($id)
    {
        $client = new Client();

        try {
            $response = $client->delete("http://localhost:5022/kunjungan/{$id}");
            return redirect('/kunjungan')->with('alert-success', 'Data kunjungan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect('/kunjungan')->with('alert-danger', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function deletekunjunganlama($id)
    {
        $client = new Client();

        try {
            $response = $client->delete("http://localhost:5022/Kunjungan/PasienLama/{$id}", [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'timeout' => 30
            ]);

            $result = json_decode($response->getBody(), true);

            if ($response->getStatusCode() === 200 && isset($result['success']) && $result['success']) {
                session()->flash('alert-success', $result['message'] ?? 'Data kunjungan lama berhasil dihapus');
            } else {
                session()->flash('alert-danger', $result['message'] ?? 'Gagal menghapus data kunjungan lama');
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $error = json_decode($response->getBody()->getContents(), true);
            session()->flash('alert-danger', $error['detail'] ?? 'Gagal menghapus data kunjungan lama: ' . $e->getMessage());
        } catch (\Exception $e) {
            session()->flash('alert-danger', 'Gagal menghapus data kunjungan lama: ' . $e->getMessage());
        }

        return redirect('/kunjungan-lama');
    }

    public function tampilkunjungan($id)
    {
        $client = new Client();

        try {
            $response = $client->get("http://localhost:5022/kunjungan/{$id}");
            $kunjungan = json_decode($response->getBody(), true);

            return view("tampilkunjungan", [
                "key" => "kunjungan",
                "kunjungan" => $kunjungan
            ]);
        } catch (\Exception $e) {
            session()->flash('alert-danger', 'Gagal mengambil data kunjungan: ' . $e->getMessage());
            return redirect('/kunjungan');
        }
    }
}
