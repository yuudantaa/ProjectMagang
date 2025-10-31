<?php

namespace App\Http\Controllers;
use App\pasien;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Services\ApiService;
use Maatwebsite\Excel\Facades\Excel;

class MagangController extends Controller
{

public function home(){
    $client = new Client();
    $stats = [];
    $today = date('Y-m-d');

    try {
        // Data Pasien Hari Ini
        \Log::info('Mengambil data pasien dari API...');
        $response = $client->get('http://localhost:5022/pasien', [
            'timeout' => 30,
            'headers' => ['Accept' => 'application/json']
        ]);

        $allPasien = json_decode($response->getBody(), true);

        // Debug response structure
        \Log::info('Response structure:', $allPasien);

        // Handle different response structures
        if (isset($allPasien['data']) && is_array($allPasien['data'])) {
            $allPasien = $allPasien['data'];
        } elseif (isset($allPasien['result']) && is_array($allPasien['result'])) {
            $allPasien = $allPasien['result'];
        } elseif (is_array($allPasien)) {
            // Jika langsung array, gunakan langsung
            $allPasien = $allPasien;
        } else {
            $allPasien = [];
        }

        \Log::info('Processed pasien data:', ['count' => count($allPasien), 'sample' => $allPasien[0] ?? 'No data']);

        // Filter pasien yang dibuat hari ini
        $pasienHariIni = array_filter($allPasien, function($item) use ($today) {
            // Cek berbagai kemungkinan field tanggal
            $dateFields = ['created_at', 'tanggal_dibuat', 'TanggalDibuat', 'tgl_daftar'];

            foreach ($dateFields as $field) {
                if (isset($item[$field]) && !empty($item[$field])) {
                    try {
                        $createdDate = date('Y-m-d', strtotime($item[$field]));
                        return $createdDate === $today;
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
            return false;
        });

        $stats['total_pasien'] = count($pasienHariIni);
        $stats['pasien_hari_ini'] = array_values($pasienHariIni);

        \Log::info('Pasien hari ini:', ['total' => $stats['total_pasien'], 'data' => $stats['pasien_hari_ini']]);

    } catch (\Exception $e) {
        \Log::error('Error mengambil data pasien: ' . $e->getMessage());
        $stats['total_pasien'] = 0;
        $stats['pasien_hari_ini'] = [];
    }

    try {
        // Data Kunjungan Hari Ini (Gabungan Baru + Lama)
        $responseKunjungan = $client->get('http://localhost:5022/kunjungan/All', [
            'timeout' => 30,
            'headers' => ['Accept' => 'application/json']
        ]);

        $kunjunganSemua = json_decode($responseKunjungan->getBody(), true);

        // Handle response structure
        if (isset($kunjunganSemua['data'])) {
            $kunjunganSemua = $kunjunganSemua['data'];
        } elseif (isset($kunjunganSemua['result'])) {
            $kunjunganSemua = $kunjunganSemua['result'];
        }

        if (!is_array($kunjunganSemua)) $kunjunganSemua = [];

        // Filter kunjungan hari ini
        $kunjunganHariIni = array_filter($kunjunganSemua, function($item) use ($today) {
            $dateFields = ['Tanggal', 'tanggal', 'created_at', 'tgl_kunjungan'];

            foreach ($dateFields as $field) {
                if (isset($item[$field]) && !empty($item[$field])) {
                    $visitDate = date('Y-m-d', strtotime($item[$field]));
                    return $visitDate === $today;
                }
            }
            return false;
        });

        $stats['total_kunjungan'] = count($kunjunganHariIni);
        $stats['kunjungan_hari_ini'] = array_values($kunjunganHariIni);

    } catch (\Exception $e) {
        \Log::error('Error mengambil data kunjungan: ' . $e->getMessage());
        $stats['total_kunjungan'] = 0;
        $stats['kunjungan_hari_ini'] = [];
    }

    try {
        // Data Dokter Tersedia Hari Ini
        $response = $client->get('http://localhost:5022/dokter', [
            'timeout' => 30,
            'headers' => ['Accept' => 'application/json']
        ]);

        $allDokter = json_decode($response->getBody(), true);

        if (isset($allDokter['data'])) {
            $allDokter = $allDokter['data'];
        } elseif (isset($allDokter['result'])) {
            $allDokter = $allDokter['result'];
        }

        if (!is_array($allDokter)) {
            $allDokter = [];
        }

        // Filter dokter yang berpraktik hari ini
        $dayOfWeek = date('N');
        $daysMap = [
            1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis',
            5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'
        ];
        $todayName = $daysMap[$dayOfWeek];

        $dokterTersedia = array_filter($allDokter, function($dokter) use ($todayName) {
            if (!isset($dokter['hariPraktek'])) return false;

            $hariPraktek = is_array($dokter['hariPraktek'])
                ? $dokter['hariPraktek']
                : explode(',', $dokter['hariPraktek']);

            // Bersihkan spasi di setiap hari
            $hariPraktek = array_map('trim', $hariPraktek);

            return in_array($todayName, $hariPraktek);
        });

        $stats['total_dokter'] = count($dokterTersedia);
        $stats['dokter_tersedia'] = array_values($dokterTersedia);

        // Data dokter baru hari ini
        $dokterBaruHariIni = array_filter($allDokter, function($item) use ($today) {
            if (isset($item['created_at'])) {
                $createdDate = date('Y-m-d', strtotime($item['created_at']));
                return $createdDate === $today;
            }
            return false;
        });

        $stats['dokter_baru_hari_ini'] = count($dokterBaruHariIni);

    } catch (\Exception $e) {
        \Log::error('Error mengambil data dokter: ' . $e->getMessage());
        $stats['total_dokter'] = 0;
        $stats['dokter_tersedia'] = [];
        $stats['dokter_baru_hari_ini'] = 0;
    }

    try {
        // Data Klinik
        $response = $client->get('http://localhost:5022/klinik', [
            'timeout' => 30,
            'headers' => ['Accept' => 'application/json']
        ]);

        $allKlinik = json_decode($response->getBody(), true);

        if (isset($allKlinik['data'])) {
            $allKlinik = $allKlinik['data'];
        } elseif (isset($allKlinik['result'])) {
            $allKlinik = $allKlinik['result'];
        }

        if (!is_array($allKlinik)) {
            $allKlinik = [];
        }

        // Hitung kunjungan per klinik hari ini
        $kunjunganPerKlinik = [];
        if (isset($stats['kunjungan_hari_ini']) && is_array($stats['kunjungan_hari_ini'])) {
            foreach ($stats['kunjungan_hari_ini'] as $kunjungan) {
                if (isset($kunjungan['klinik']['id_Klinik'])) {
                    $klinikId = $kunjungan['klinik']['id_Klinik'];
                    if (!isset($kunjunganPerKlinik[$klinikId])) {
                        $kunjunganPerKlinik[$klinikId] = [
                            'klinik' => $kunjungan['klinik'],
                            'jumlah' => 0
                        ];
                    }
                    $kunjunganPerKlinik[$klinikId]['jumlah']++;
                }
            }
        }

        $stats['kunjungan_per_klinik'] = $kunjunganPerKlinik;
        $stats['total_klinik_aktif'] = count($kunjunganPerKlinik);

        // Klinik baru hari ini
        $klinikBaruHariIni = array_filter($allKlinik, function($item) use ($today) {
            if (isset($item['created_at'])) {
                $createdDate = date('Y-m-d', strtotime($item['created_at']));
                return $createdDate === $today;
            }
            return false;
        });

        $stats['klinik_baru_hari_ini'] = count($klinikBaruHariIni);

    } catch (\Exception $e) {
        \Log::error('Error mengambil data klinik: ' . $e->getMessage());
        $stats['kunjungan_per_klinik'] = [];
        $stats['total_klinik_aktif'] = 0;
        $stats['klinik_baru_hari_ini'] = 0;
    }

    // Data tambahan untuk statistik
    $stats['tanggal_hari_ini'] = $today;
    $stats['hari_ini'] = $this->getIndonesianDay(date('N'));
    $stats['bulan_tahun'] = $this->getIndonesianMonth(date('n')) . ' ' . date('Y');

    // Debug final stats
    \Log::info('Final stats:', $stats);

    return view("home", ["key" => "home", "stats" => $stats]);
}

    // Fungsi helper untuk mendapatkan nama hari dalam Bahasa Indonesia
    private function getIndonesianDay($dayNumber) {
        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];
        return $days[$dayNumber] ?? 'Unknown';
    }

    // Fungsi helper untuk mendapatkan nama bulan dalam Bahasa Indonesia
    private function getIndonesianMonth($monthNumber) {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        return $months[$monthNumber] ?? 'Unknown';
    }

    public function login(){
        return view("login",["key"=>"login"]);
    }

    public function tambahuser(){
        return view("tambahuser",["key"=>"tambahuser"]);
    }

    public function lupapassword()
    {
        return view("lupapassword", ["key" => "lupa-password"]);
    }

    public function cariusername()
    {
        return view("cariusername", ["key" => "cari-username"]);
    }

    public function proseslupapassword(Request $request)
    {
        $client = new Client();

        try {
            $response = $client->post('http://localhost:5022/user/forgot-password', [
                'json' => [
                    'emailOrUsername' => $request->emailOrUsername
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'timeout' => 30
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['success']) && $data['success']) {
                // Untuk development, tampilkan token
                if (isset($data['token'])) {
                    session()->flash('alert-info',
                        "Klik tombol dibawah untuk reset password <br>
                        <a href='" . url('/reset-password/' . $data['token']) . "' class='btn btn-primary btn-sm'> reset password</a>");
                } else {
                    session()->flash('alert-danger', $data['message']);
                }
            } else {
                session()->flash('alert-danger', $data['message'] ?? 'Terjadi kesalahan');
            }

            return back();

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $error = json_decode($response->getBody()->getContents(), true);
            return back()->with('alert-danger', $error['message'] ?? 'Terjadi kesalahan')->withInput();
        } catch (\Exception $e) {
            return back()->with('alert-danger', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }


    public function resetpassword($token)
    {
        $client = new Client();

        try {
            // Validasi token
            $response = $client->get("http://localhost:5022/user/validate-reset-token/{$token}");
            $data = json_decode($response->getBody(), true);

            if (!$data['valid']) {
                return redirect('/lupa-password')->with('alert-danger', 'Token tidak valid atau sudah kedaluwarsa');
            }

            return view("resetpassword", [
                "key" => "reset-password",
                "token" => $token
            ]);

        } catch (\Exception $e) {
            return redirect('/lupa-password')->with('alert-danger', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function prosesresetpassword(Request $request)
    {
        $client = new Client();

        try {
            $response = $client->post('http://localhost:5022/user/reset-password', [
                'json' => [
                    'token' => $request->token,
                    'newPassword' => $request->newPassword
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'timeout' => 30
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['success']) && $data['success']) {
                return redirect('/login')->with('alert-success', 'Password berhasil direset. Silakan login dengan password baru.');
            } else {
                return back()->with('alert-danger', $data['message'] ?? 'Gagal reset password')->withInput();
            }

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $error = json_decode($response->getBody()->getContents(), true);
            return back()->with('alert-danger', $error['message'] ?? 'Terjadi kesalahan')->withInput();
        } catch (\Exception $e) {
            return back()->with('alert-danger', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

public function simpanuser(Request $request)
{
    $client = new Client();

    try {
        $response = $client->post('http://localhost:5022/user', [
            'json' => [
                'username' => $request->username,
                'password' => $request->password,
                'namaUser' => $request->namaUser,
                'email' => $request->email,
                'nomorHP' => $request->nomorHp
            ],
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'timeout' => 30
        ]);

        $data = json_decode($response->getBody(), true);

        if (isset($data['success']) && $data['success']) {
            return redirect('/login')->with('alert-success',
                'User berhasil ditambahkan! Silakan login dengan akun baru.');
        } else {
            return back()->with('alert-danger',
                $data['message'] ?? 'Gagal menambahkan user')->withInput();
        }

    } catch (\GuzzleHttp\Exception\ClientException $e) {
        $response = $e->getResponse();
        $error = json_decode($response->getBody()->getContents(), true);

        $errorMessage = $error['message'] ?? 'Terjadi kesalahan';

        // Handle specific error cases
        if (str_contains($errorMessage, 'Username')) {
            return back()->with('alert-danger', $errorMessage)->withInput();
        } elseif (str_contains($errorMessage, 'Email')) {
            return back()->with('alert-danger', $errorMessage)->withInput();
        } else {
            return back()->with('alert-danger', $errorMessage)->withInput();
        }

    } catch (\Exception $e) {
        return back()->with('alert-danger',
            'Terjadi kesalahan: ' . $e->getMessage())->withInput();
    }
}

    public function prosescariusername(Request $request)
    {
        $client = new Client();

        try {
            $response = $client->post('http://localhost:5022/user/find-username', [
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

    public function proseslogin(Request $request)
    {
        $client = new Client();

        try {
            $response = $client->post('http://localhost:5022/user/login', [
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

}
