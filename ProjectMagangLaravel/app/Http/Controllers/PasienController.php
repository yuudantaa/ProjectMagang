<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Services\ApiService;
use App\Exports\PasienExport;
use Maatwebsite\Excel\Facades\Excel;

class PasienController extends Controller
{
    //pasien
    public function pasien(Request $request)
    {
        $client = new Client();

        try {
            // Gunakan endpoint untuk kunjungan ALL
            $response = $client->get('http://localhost:5022/Pasien', [
                'timeout' => 30,
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]);

            $pasien = json_decode($response->getBody(), true);

            if (!is_array($pasien)) {
                $pasien = [];
                session()->flash('alert-warning', 'Format data pasien tidak valid');
            }

            // Filter data berdasarkan pencarian
            $searchPasien = $request->get('nama');
            $searchAlamat = $request->get('alamat');
            $searchKecamatan = $request->get('kecamatan');

            if ($searchPasien || $searchAlamat || $searchKecamatan) {
                $pasien = array_filter($pasien, function($item) use ($searchPasien, $searchAlamat, $searchKecamatan) {
                    $match = true;

                    // Filter berdasarkan pasien
                    if ($searchPasien && $match) {
                        $namaPasien = $item['nama'] ?? '';
                        $match = $match && (stripos($namaPasien, $searchPasien) !== false);
                    }

                    // Filter berdasarkan alamat pasien
                    if ($searchAlamat && $match) {
                        $alamatPasien = $item['alamat'] ?? '';
                        $match = $match && (stripos($alamatPasien, $searchAlamat) !== false);
                    }

                    // Filter berdasarkan kecamatan
                    if ($searchKecamatan && $match) {
                        $kecamatanPasien = $item['kecamatan'] ?? '';
                        $match = $match && (stripos($kecamatanPasien, $searchKecamatan) !== false);
                    }

                    return $match;
                });
            }

            // Get unique values for dropdown suggestions
            $namaPasienList = [];
            $alamatPasienList = [];
            $kecamatanPasienList = [];

            foreach ($pasien as $item) {
                // Extract pasien
                $iniNamaPasien = $item['nama'] ?? '';
                if ($iniNamaPasien && !in_array($iniNamaPasien, $namaPasienList)) {
                    $namaPasienList[] = $iniNamaPasien;
                }

                // Extract dokter
                $iniAlamatPasien = $item['alamat'] ?? '';
                if ($iniAlamatPasien && !in_array($iniAlamatPasien, $alamatPasienList)) {
                    $alamatPasienList[] = $iniAlamatPasien;
                }

                // Extract klinik
                $iniKecamatanPasien = $item['kecamatan'] ?? '';
                if ($iniKecamatanPasien && !in_array($iniKecamatanPasien, $kecamatanPasienList)) {
                    $kecamatanPasienList[] = $iniKecamatanPasien;
                }
            }

            // Sort lists
            sort($namaPasienList);
            sort($alamatPasienList);
            sort($kecamatanPasienList);

            return view("pasien", [
                "key" => "pasien",
                "ps" => array_values($pasien),
                "namaPasienList" => $namaPasienList,
                "alamatPasienList" => $alamatPasienList,
                "kecamatanPasienList" => $kecamatanPasienList,
                "searchPasien" => $searchPasien,
                "searchAlamat" => $searchAlamat,
                "searchKecamatan" => $searchKecamatan
            ]);

        } catch (\Exception $e) {
            session()->flash('alert-danger', 'Gagal mengambil data pasien: ' . $e->getMessage());
            return view("pasien", [
                "key" => "pasien",
                "ps" => [],
                "namaPasienList" => [],
                "alamatPasienList" => [],
                "kecamatanPasienList" => [],
                "searchPasien" => '',
                "searchAlamat" => '',
                "searchKecamatan" => ''
            ]);
        }
    }

    public function tampilpasien($id)
    {
        $client = new Client();

        try {
            // Mengambil data pasien berdasarkan ID dari API
            $response = $client->get("http://localhost:5022/pasien/{$id}");
            $pasien = json_decode($response->getBody(), true);

            return view("tampilpasien", [
                "key" => "pasien",
                "pasien" => $pasien
            ]);
        } catch (\Exception $e) {
            session()->flash('alert', 'Gagal mengambil data pasien: ' . $e->getMessage());
            return redirect('/pasien');
        }
    }

    public function tambahpasien(){
        return view("tambahpasien",["key"=>"pasien"]);
    }

    public function simpanpasien(Request $request)
    {
        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->post('http://localhost:5022/pasien', [
                'json' => [
                    'Id_RekamMedis' => $request->Id_RekamMedis,
                    'NoKTP' => $request->NoKTP,
                    'Nama' => $request->Nama,
                    'TempatLahir' => $request->TempatLahir,
                    'TanggalLahir' => $request->TanggalLahir,
                    'Agama' => $request->Agama,
                    'Gender' => $request->Gender,
                    'StatusKawin' => $request->StatusKawin,
                    'Pendidikan' => $request->Pendidikan,
                    'Pekerjaan' => $request->Pekerjaan,
                    'ButuhPenerjemah' => $request->ButuhPenerjemah,
                    'Umur' => $request->Umur,
                    'Bulan' => $request->Bulan,
                    'Bahasa' => $request->Bahasa,
                    'NoTelp' => $request->NoTelp,
                    'Email' => $request->Email,
                    'Alamat' => $request->Alamat,
                    'Kecamatan' => $request->Kecamatan,
                    'Kabupaten' => $request->Kabupaten,
                    'Provinsi' => $request->Provinsi
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ]
            ]);

            if ($response->getStatusCode() === 201) {
                $newPatient = json_decode($response->getBody(), true);

                // Simpan data pasien baru ke session untuk digunakan di form kunjungan
                session([
                    'new_patient_data' => $newPatient,
                    'patient_added_success' => true,
                    'show_success_modal' => true,
                    'success_message' => 'Data pasien berhasil disimpan! Data telah otomatis dimuat ke form kunjungan.'
                ]);

                return redirect('/tambahkunjungan');
            } else {
                return back()->with('alert-danger', 'Gagal menyimpan data pasien')->withInput();
            }

        } catch (\Exception $e) {
            return back()->with('alert-danger', 'Gagal menyimpan data pasien: ' . $e->getMessage())->withInput();
        }
    }

    public function exportpasien()
    {
        return Excel::download(new PasienExport, 'pasien-' . date('Y-m-d') . '.xlsx');
    }

    public function editpasien($id)
    {
        $client = new Client();

        try {
            // Ambil data Pasien
            $response = $client->get("http://localhost:5022/pasien/{$id}");
            $pasien = json_decode($response->getBody(), true);

            return view("editpasien", [
                "key" => "pasien",
                "pasien" => $pasien,
            ]);
        } catch (\Exception $e) {
            session()->flash('alert', 'Gagal mengambil data: ' . $e->getMessage());
            return redirect('/pasien');
        }
    }


    public function updatepasien($id, Request $request)
    {
        $client = new Client();

        try {
            $response = $client->put("http://localhost:5022/pasien", [
                'json' => [
                    'Id_RekamMedis' => $id,
                    'NoKTP' => $request->NoKTP,
                    'Nama' => $request->Nama,
                    'TempatLahir' => $request->TempatLahir,
                    'TanggalLahir' => $request->TanggalLahir,
                    'Agama' => $request->Agama,
                    'Gender' => $request->Gender,
                    'StatusKawin' => $request->StatusKawin,
                    'Pendidikan' => $request->Pendidikan,
                    'Pekerjaan' => $request->Pekerjaan,
                    'ButuhPenerjemah' => $request->ButuhPenerjemah,
                    'Umur' => (int)$request->Umur,
                    'Bulan' => (int)$request->Bulan,
                    'Bahasa' => $request->Bahasa,
                    'NoTelp' => $request->NoTelp,
                    'Email' => $request->Email,
                    'Alamat' => $request->Alamat,
                    'Kecamatan' => $request->Kecamatan,
                    'Kabupaten' => $request->Kabupaten,
                    'Provinsi' => $request->Provinsi
                ]
            ]);

            session()->flash('alert', 'Data pasien berhasil diupdate');
        } catch (\Exception $e) {
            session()->flash('alert', 'Gagal update data: ' . $e->getMessage());
        }

        return redirect('/pasien');
    }



    public function deletepasien($id)
    {
        $client = new Client();

        try {
            $response = $client->delete("http://localhost:5022/pasien/{$id}");
            session()->flash('alert', 'Data pasien berhasil dihapus');
        } catch (\Exception $e) {
            session()->flash('alert', 'Gagal menghapus data: ' . $e->getMessage());
        }

        return redirect('/pasien');
    }
}
