<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Services\ApiService;

class DokterController extends Controller
{
        //Dokter
    public function dokter(Request $request)
    {
        $client = new Client();

        try {
            // Gunakan endpoint untuk kunjungan ALL
            $response = $client->get('http://localhost:5022/Dokter', [
                'timeout' => 30,
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]);

            $dokter = json_decode($response->getBody(), true);

            if (!is_array($dokter)) {
                $dokter = [];
                session()->flash('alert-warning', 'Format data dokter tidak valid');
            }

            // Filter data berdasarkan pencarian
            $searchDokter = $request->get('nama');
            $searchSpesialisasi = $request->get('spesialisasi');

            if ($searchDokter || $searchSpesialisasi ) {
                $dokter = array_filter($dokter, function($item) use ($searchDokter, $searchSpesialisasi) {
                    $match = true;

                    // Filter berdasarkan Dokter
                    if ($searchDokter && $match) {
                        $namaDokter = $item['nama'] ?? '';
                        $match = $match && (stripos($namaDokter, $searchDokter) !== false);
                    }

                    // Filter berdasarkan spesialisasi
                    if ($searchSpesialisasi && $match) {
                        $spesialisasiDokter = $item['spesialisasi'] ?? '';
                        $match = $match && (stripos($spesialisasiDokter, $searchSpesialisasi) !== false);
                    }

                    return $match;
                });
            }

            // Get unique values for dropdown suggestions
            $namaDokterList = [];
            $spesiaslisasiDokterList = [];

            foreach ($dokter as $item) {
                // Extract klinik
                $iniNamaDokter = $item['nama'] ?? '';
                if ($iniNamaDokter && !in_array($iniNamaDokter, $namaDokterList)) {
                    $namaDokterList[] = $iniNamaDokter;
                }

                // Extract jenis
                $iniSpesialisasi = $item['jenis'] ?? '';
                if ($iniSpesialisasi && !in_array($iniSpesialisasi, $spesiaslisasiDokterList)) {
                    $spesiaslisasiDokterList[] = $iniSpesialisasi;
                }
            }

            // Sort lists
            sort($spesiaslisasiDokterList);
            sort($namaDokterList);

            return view("dokter", [
                "key" => "dokter",
                "kl" => array_values($dokter),
                "spesiaslisasiDokterList" => $spesiaslisasiDokterList,
                "namaDokterList" => $namaDokterList,
                "searchDokter" => $searchDokter,
                "searchSpesialisasi" => $searchSpesialisasi,
            ]);

        } catch (\Exception $e) {
            session()->flash('alert-danger', 'Gagal mengambil data dokter: ' . $e->getMessage());
            return view("dokter", [
                "key" => "dokter",
                "kl" => [],
                "spesiaslisasiDokterList" => [],
                "namaDokterList" => [],
                "searchDokter" => '',
                "searchSpesialisasi" => ''
            ]);
        }
    }

    public function tambahdokter(){
        return view("tambahdokter",["key"=>"dokter"]);
    }

public function simpandokter(Request $request)
{
    $client = new \GuzzleHttp\Client();

    try {
        // Konversi array hari menjadi string yang dipisahkan koma
        $hariPraktek = is_array($request->hariPraktek)
            ? implode(',', $request->hariPraktek)
            : $request->hariPraktek;

        $response = $client->post('http://localhost:5022/dokter', [
            'json' => [
                'Id_Dokter' => $request->Id_Dokter,
                'nama' => $request->nama,
                'spesialisasi' => $request->spesialisasi,
                'noHP' => $request->noHP,
                'email' => $request->email,
                'hariPraktek' => $hariPraktek,
                'jamMulai' => $request->jamMulai,
                'jamSelesai' => $request->jamSelesai,
            ]
        ]);

        if ($response->getStatusCode() === 201) {
            return redirect('/dokter')->with('alert-success', 'Data dokter berhasil disimpan');
        } else {
            return back()->with('alert-danger', 'Gagal menyimpan data dokter')->withInput();
        }

    } catch (\Exception $e) {
        return back()->with('alert-danger', 'Gagal menyimpan data dokter: ' . $e->getMessage())->withInput();
    }
}

    public function editdokter($id)
    {
        $client = new Client();

        try {
            // Ambil data dokter
            $response = $client->get("http://localhost:5022/dokter/{$id}");
            $dokter = json_decode($response->getBody(), true);

            return view("editdokter", [
                "key" => "dokter",
                "dokter" => $dokter,
            ]);
        } catch (\Exception $e) {
            session()->flash('alert', 'Gagal mengambil data: ' . $e->getMessage());
            return redirect('/dokter');
        }
    }

public function updatedokter($id, Request $request)
{
    $client = new Client();

    try {
        // Konversi array hari menjadi string
        $hariPraktek = is_array($request->hariPraktek)
            ? implode(',', $request->hariPraktek)
            : $request->hariPraktek;

        $response = $client->put("http://localhost:5022/dokter", [
            'json' => [
                'Id_Dokter' => $id,
                'Nama' => $request->nama,
                'Spesialisasi' => $request->spesialisasi,
                'NoHP' => $request->noHP,
                'Email' => $request->email,
                'HariPraktek' => $hariPraktek,
                'JamMulai' => $request->jamMulai,
                'JamSelesai' => $request->jamSelesai
            ]
        ]);

        session()->flash('alert', 'Data Dokter berhasil diupdate');
    } catch (\Exception $e) {
        session()->flash('alert', 'Gagal update data: ' . $e->getMessage());
    }

    return redirect('/dokter');
}

    public function deletedokter($id)
    {
        $client = new Client();

        try {
            $response = $client->delete("http://localhost:5022/dokter/{$id}");
            session()->flash('alert', 'Data dokter berhasil dihapus');
        } catch (\Exception $e) {
            session()->flash('alert', 'Gagal menghapus data: ' . $e->getMessage());
        }

        return redirect('/dokter');
    }
}
