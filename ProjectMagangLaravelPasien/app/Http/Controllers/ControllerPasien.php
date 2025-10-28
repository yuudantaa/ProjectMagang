<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Exception;

class ControllerPasien extends Controller
{
    public function login(){
        return view("login",["key"=>"login"]);
    }

    public function home(){
        return view("home",["key"=>"home"]);
    }

    public function proseslogin(Request $request)
    {
        $client = new Client();

        try {
            $response = $client->post('http://localhost:5022/userpasien/login', [
                'json' => [
                    'username' => $request->username,
                    'password' => $request->password
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'timeout' => 30
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['success']) && $data['success']) {
                // Simpan data user ke session
                session([
                    'is_logged_in' => true,
                    'username' => $data['username'],
                    'nama' => $data['nama']
                ]);

                return redirect('/')->with('alert-success', 'Login berhasil');
            } else {
                return back()->with('alert-danger', $data['message'] ?? 'Login gagal')->withInput();
            }

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $error = json_decode($response->getBody()->getContents(), true);

            return back()->with('alert-danger', $error['message'] ?? 'Username atau password salah')->withInput();
        } catch (\Exception $e) {
            return back()->with('alert-danger', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function logout()
    {
        session()->flush();
        return redirect('/login')->with('alert-success', 'Logout berhasil');
    }

    public function prosescariusername(Request $request)
    {
        $client = new Client();

        try {
            $response = $client->post('http://localhost:5022/userpasien/find-username', [
                'json' => [
                    'email' => $request->email
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'timeout' => 30
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['success']) && $data['success']) {
                // Untuk development, tampilkan username
                if (isset($data['username'])) {
                    session()->flash('alert-info',
                        "Username Anda: <strong>{$data['username']}</strong><br>
                        <a href='" . url('/login') . "' class='btn btn-primary btn-sm'>Klik di sini untuk login</a>");
                } else {
                    session()->flash('alert-success', $data['message']);
                }
            } else {
                session()->flash('alert-danger', $data['message'] ?? 'Terjadi kesalahan');
            }

            return back();

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $error = json_decode($response->getBody()->getContents(), true);
            return back()->with('alert-danger', $error['message'] ?? 'Terjadi kesalahan');
        } catch (\Exception $e) {
            return back()->with('alert-danger', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cariusername()
    {
        return view("cariusername", ["key" => "cari-username"]);
    }

    public function kunjunganpasienlama(Request $request)
    {
        // Cek apakah user sudah login
        if (!session('is_logged_in')) {
            return redirect('/login')->with('alert-warning', 'Silakan login terlebih dahulu');
        }

        $client = new Client();
        $username = session('username');

        try {
            // Gunakan endpoint baru yang filter berdasarkan user
            $response = $client->get("http://localhost:5022/kunjungan/user/{$username}", [
                'timeout' => 30,
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]);

            $result = json_decode($response->getBody(), true);

            if (isset($result['success']) && $result['success']) {
                $kunjungan = $result['data'];
                $userInfo = $result['userInfo'];

                // Handle search functionality
                $search = $request->get('search');
                if ($search && is_array($kunjungan)) {
                    $kunjungan = array_filter($kunjungan, function($item) use ($search) {
                        $search = strtolower($search);
                        return (
                            (isset($item['id_Kunjungan']) && stripos($item['id_Kunjungan'], $search) !== false) ||
                            (isset($item['rekamMedis']['nama']) && stripos($item['rekamMedis']['nama'], $search) !== false) ||
                            (isset($item['rekamMedis']['id_RekamMedis']) && stripos($item['rekamMedis']['id_RekamMedis'], $search) !== false) ||
                            (isset($item['dokter']['nama']) && stripos($item['dokter']['nama'], $search) !== false) ||
                            (isset($item['klinik']['nama']) && stripos($item['klinik']['nama'], $search) !== false)
                        );
                    });
                }

                return view("kunjungan", [
                    "key" => "kunjungan",
                    "ps" => $kunjungan,
                    "search" => $search,
                    "userInfo" => $userInfo
                ]);
            } else {
                session()->flash('alert-warning', $result['message'] ?? 'Tidak ada data kunjungan');
                return view("kunjungan", [
                    "key" => "kunjungan",
                    "ps" => [],
                    "search" => null,
                    "userInfo" => null
                ]);
            }

        } catch (\Exception $e) {
            session()->flash('alert-danger', 'Gagal mengambil data kunjungan: ' . $e->getMessage());
            return view("kunjungan", [
                "key" => "kunjungan",
                "ps" => [],
                "search" => null,
                "userInfo" => null
            ]);
        }
    }

    public function tampilkunjunganpasienlama($id)
    {
        $client = new Client();

        try {
            // Mengambil data kunjungan berdasarkan ID dari API
            $response = $client->get("http://localhost:5022/kunjungan/{$id}");
            $kunjungan = json_decode($response->getBody(), true);

            return view("tampilkunjungan", [
                "key" => "kunjungan",
                "kunjungan" => $kunjungan
            ]);
        } catch (\Exception $e) {
            session()->flash('alert', 'Gagal mengambil data kunjungan: ' . $e->getMessage());
            return redirect('/kunjungan');
        }
    }

    public function checkDuplicateKunjunganpasien(Request $request)
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

            // Tambahkan pesan yang lebih informatif
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

   public function tambahkunjunganpasienlama()
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

            return view("tambahkunjungan", [
                "key" => "kunjungan",
                "dokters" => $dokters,
                "kliniks" => $kliniks,
                "pasiens" => $pasiens,
                "today" => $todayName
            ]);
        } catch (\Exception $e) {
            session()->flash('alert', 'Gagal mengambil data: ' . $e->getMessage());
            return view("tambah-kunjungan", [
                "key" => "kunjungan",
                "dokters" => [],
                "kliniks" => [],
                "pasiens" => [],
                "today" => "Hari ini"
            ]);
        }
    }


     public function simpankunjunganpasienlama(Request $request)
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

}
