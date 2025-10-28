<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasienStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'NoKTP' => 'required|digits:16|numeric',
            'Nama' => 'required|string|max:100',
            'TempatLahir' => 'required|string|max:50',
            'TanggalLahir' => 'required|date',
            'Gender' => 'required|in:Pria,Wanita',
            'Umur' => 'required|integer|between:0,150',
            'Bulan' => 'required|integer|between:0,11',
            'Agama' => 'required|in:Kristen,Katholik,Islam,Hindu,Budha,Konghuchu',
            'StatusKawin' => 'required|in:SudahMenikah,BelumMenikah,Bercerai',
            'Pendidikan' => 'required|in:BelumSekolah,SD,SMP,SMA,SMK,D4,D3,S1,S2,S3',
            'Pekerjaan' => 'required|string|max:12',
            'Bahasa' => 'required|string|max:10',
            'ButuhPenerjemah' => 'required|in:Butuh,TidakButuh',
            'NoTelp' => 'required|digits_between:10,12|numeric',
            'Email' => 'required|email|max:40',
            'Alamat' => 'required|string|max:20',
            'Kecamatan' => 'required|string|max:16',
            'Kabupaten' => 'required|string|max:16',
            'Provinsi' => 'required|string|max:16'
        ];
    }

        public function messages()
    {
        return [
            'NoKTP.digits' => 'NoKTP harus 16 digit',
            'NoKTP.numeric' => 'NoKTP hanya boleh angka',
            'Gender.in' => 'Gender harus Pria atau Wanita',
            'Umur.between' => 'Umur harus antara 0-150',
            'Bulan.between' => 'Bulan harus antara 0-11',
            'Agama.in' => 'Agama harus pilih salah satu dari pilihan yang tersedia',
            'StatusKawin.in' => 'Status Pernikahan harus diisi',
            'Pendidikan.in' => 'Pendidikan harus diisi',
            'NoTelp.numeric' => 'NoTelp hanya boleh angka',
            'NoTelp.digits_between' => 'NoTelp harus antara 10-12 digit',
            'Email.email' => 'Format email tidak valid'
        ];
    }
}
