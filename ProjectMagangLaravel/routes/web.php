<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get("/login","MagangController@login");
Route::get("/tambah-user","MagangController@tambahuser");
Route::get("/logout","MagangController@logout");
Route::post('/login', "MagangController@proseslogin");
Route::post('/simpan-user', "MagangController@simpanuser");
// routes/web.php
Route::get('/lupa-password', "MagangController@lupapassword");
Route::post('/proses-lupa-password', "MagangController@proseslupapassword");
Route::get('/reset-password/{token}',"MagangController@resetpassword");
Route::post('/proses-reset-password', "MagangController@prosesresetpassword");
Route::get('/cari-username', "MagangController@cariusername");
Route::post('/proses-cari-username', "MagangController@prosescariusername");

Route::middleware(['auth.user'])->group(function () {

 Route::get("/","MagangController@Home");
Route::get("/laporan","LaporanController@laporan");
 Route::get('/pasien',"PasienController@pasien");
Route::get('/pasien/{id}', "PasienController@tampilpasien");
Route::get('/kunjungan/{id}', "KunjunganController@tampilkunjungan");
 Route::get('/klinik',"KlinikController@klinik");
 Route::get('/dokter',"DokterController@dokter");
Route::get('/kunjungan',"KunjunganController@kunjungan");
Route::get('/kunjungan-lama',"KunjunganController@kunjunganlama");

Route::post('/save-klinik', "KlinikController@simpanklinik");
Route::post('/save-dokter', "DokterController@simpandokter");
Route::post('/save-kunjungan', "KunjunganController@simpankunjungan");
Route::post('/save-kunjungan-lama', "KunjunganController@simpankunjunganlama");

Route::get('/export-laporan', "LaporanController@exportlaporan");
Route::get('/export-laporan-pdf', "LaporanController@exportlaporanpdf");

 Route::get('/userpasien',"MagangController@userpasien");

 Route::get('/tambahpasien',"PasienController@tambahpasien");
  Route::get('/tambahkunjungan',"KunjunganController@tambahkunjungan");
  Route::get('/tambahkunjunganlama',"KunjunganController@tambahkunjunganlama");
 Route::get('/dokter/tambahdokter',"DokterController@tambahdokter");
 Route::get('/klinik/tambahklinik',"KlinikController@tambahklinik");

Route::get('/dokter/edit/{id}', "DokterController@editdokter");
Route::put('/update-dokter/{id}', "DokterController@updatedokter");

Route::delete("/delete-dokter/{id}","DokterController@deletedokter");
Route::delete('/delete-pasien/{id}', "PasienController@deletepasien");
Route::delete("/delete-klinik/{id}","KlinikController@deleteklinik");
Route::delete('/delete-kunjungan/{id}', "KunjunganController@deletekunjungan");
Route::delete('/delete-kunjungan-lama/{id}', "KunjunganController@deletekunjunganlama");

Route::get('/klinik/edit/{id}', "KlinikController@editklinik");
Route::put('/update-klinik/{id}', "KlinikController@updateklinik");

Route::get('/kunjungan/edit/{id}', "KunjunganController@editkunjungan");
Route::put('/update-kunjungan/{id}', "KunjunganController@updatekunjungan");

Route::get('/kunjunganlama/edit/{id}', "KunjunganController@editkunjunganlama");
Route::put('/update-kunjungan-lama/{id}', "KunjunganController@updatekunjunganlama");

Route::get('/pasien/edit/{id}', "PasienController@editPasien");
Route::post('/save-pasien', "PasienController@simpanpasien");
Route::put('/update-pasien/{id}', "MagangController@updatepasien");

Route::get('/check-duplicate-kunjungan', [KunjunganController::class, 'checkDuplicateKunjungan']);

Route::post('/clear-duplicate-session', function() {
    session()->forget('duplicate_data');
    return response()->json(['success' => true]);
});

Route::post('/clear-patient-session', function() {
    session()->forget(['new_patient_data', 'patient_added_success']);
    return response()->json(['success' => true]);
});

Route::post('/clear-success-session', function() {
    session()->forget(['show_success_modal', 'success_message', 'new_patient_data']);
    return response()->json(['success' => true]);
});

Route::get('/get-filtered-data', [LaporanController::class, 'getFilteredDataForModal']);

});
