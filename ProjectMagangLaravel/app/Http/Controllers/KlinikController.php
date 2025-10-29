<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Services\ApiService;

class KlinikController extends Controller
{
        //Klinik
    public function klinik(Request $request)
    {
        $client = new Client();

        try {
            // Gunakan endpoint untuk kunjungan ALL
            $response = $client->get('http://localhost:5022/Klinik', [
                'timeout' => 30,
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]);

            $klinik = json_decode($response->getBody(), true);

            if (!is_array($klinik)) {
                $klinik = [];
                session()->flash('alert-warning', 'Format data klinik tidak valid');
            }

            // Filter data berdasarkan pencarian
            $searchKlinik = $request->get('nama');
            $searchJenis = $request->get('jenis');

            if ($searchKlinik || $searchJenis ) {
                $klinik = array_filter($klinik, function($item) use ($searchKlinik, $searchJenis) {
                    $match = true;

                    // Filter berdasarkan pasien
                    if ($searchKlinik && $match) {
                        $namaKlinik = $item['nama'] ?? '';
                        $match = $match && (stripos($namaKlinik, $searchKlinik) !== false);
                    }

                    // Filter berdasarkan jenis klinik
                    if ($searchJenis && $match) {
                        $jenisKlinik = $item['jenis'] ?? '';
                        $match = $match && (stripos($jenisKlinik, $searchJenis) !== false);
                    }

                    return $match;
                });
            }

            // Get unique values for dropdown suggestions
            $namaKlinikList = [];
            $jenisKlinikList = [];

            foreach ($klinik as $item) {
                // Extract klinik
                $iniNamaKlinik = $item['nama'] ?? '';
                if ($iniNamaKlinik && !in_array($iniNamaKlinik, $namaKlinikList)) {
                    $namaKlinikList[] = $iniNamaKlinik;
                }

                // Extract jenis
                $iniJenisKlinik = $item['jenis'] ?? '';
                if ($iniJenisKlinik && !in_array($iniJenisKlinik, $jenisKlinikList)) {
                    $jenisKlinikList[] = $iniJenisKlinik;
                }
            }

            // Sort lists
            sort($namaKlinikList);
            sort($jenisKlinikList);

            return view("klinik", [
                "key" => "klinik",
                "kl" => array_values($klinik),
                "namaKlinikList" => $namaKlinikList,
                "jenisKlinikList" => $jenisKlinikList,
                "searchKlinik" => $searchKlinik,
                "searchJenis" => $searchJenis,
            ]);

        } catch (\Exception $e) {
            session()->flash('alert-danger', 'Gagal mengambil data klinik: ' . $e->getMessage());
            return view("klinik", [
                "key" => "klinik",
                "kl" => [],
                "namaKlinikList" => [],
                "jenisKlinikList" => [],
                "searchKlinik" => '',
                "searchJenis" => ''
            ]);
        }
    }

    public function tambahklinik(){
        return view("tambahklinik",["key"=>"klinik"]);
    }

        public function simpanklinik(Request $request)
        {
            $client = new \GuzzleHttp\Client();

            try {
                $response = $client->post('http://localhost:5022/klinik', [
                    'json' => [
                        'Id_Klinik' =>$request->Id_Klinik,
                        'nama' => $request->nama,
                        'jenis' => $request->jenis,
                        'gedung' => $request->gedung,
                        'lantai' => $request->lantai,
                        'kapasitas' => $request->kapasitas,
                        'keterangan' => $request->keterangan,
                    ]
                ]);
                if ($response->getStatusCode() === 201) {
                    return redirect('/klinik')->with('alert-success', 'Data klinik berhasil disimpan');
                } else {
                    return back()->with('alert-danger', 'Gagal menyimpan data klinik')->withInput();
                }

            } catch (\Exception $e) {
                return back()->with('alert-danger', 'Gagal menyimpan data klinik: ' . $e->getMessage())->withInput();
            }
        }

    public function editklinik($id)
    {
        $client = new Client();

        try {
            // Ambil data klinik
            $response = $client->get("http://localhost:5022/klinik/{$id}");
            $klinik = json_decode($response->getBody(), true);

            return view("editklinik", [
                "key" => "klinik",
                "klinik" => $klinik,
            ]);
        } catch (\Exception $e) {
            session()->flash('alert', 'Gagal mengambil data: ' . $e->getMessage());
            return redirect('/klinik');
        }
    }

    public function updateklinik($id, Request $request)
    {
        $client = new Client();

        try {
            $response = $client->put("http://localhost:5022/klinik", [
                'json' => [
                    'Id_Klinik' => $id,
                    'Nama' => $request->nama,
                    'Jenis' => $request->jenis,
                    'Gedung' => $request->gedung,
                    'Lantai' => $request->lantai,
                    'Kapasitas' => (int)$request->kapasitas,
                    'Keterangan' => $request->keterangan
                ]
            ]);

            session()->flash('alert', 'Data Klinik berhasil diupdate');
        } catch (\Exception $e) {
            session()->flash('alert', 'Gagal update data: ' . $e->getMessage());
        }

        return redirect('/klinik');
    }


    public function deleteklinik($id)
    {
        $client = new Client();

        try {
            $response = $client->delete("http://localhost:5022/klinik/{$id}");
            session()->flash('alert', 'Data klinik berhasil dihapus');
        } catch (\Exception $e) {
            session()->flash('alert', 'Gagal menghapus data: ' . $e->getMessage());
        }

        return redirect('/klinik');
    }
}
