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

Route::get("/login","ControllerPasien@login");
Route::get("/logout","ControllerPasien@logout");
Route::post('/login', "ControllerPasien@proseslogin");

Route::get('/cari-username', "ControllerPasien@cariusername");
Route::post('/proses-cari-username', "ControllerPasien@prosescariusername");

Route::middleware(['api.auth'])->group(function () {

Route::get("/","ControllerPasien@home");
Route::get('/kunjungan',"ControllerPasien@kunjunganpasienlama");
Route::get('/tambah-kunjungan',"ControllerPasien@tambahkunjunganpasienlama");
Route::post('/save-kunjungan',"ControllerPasien@simpankunjunganpasienlama");
Route::get('/kunjungan/{id}', "ControllerPasien@tampilkunjunganpasienlama");
Route::get('/check-duplicate-kunjungan', [ControllerPasien::class, 'checkDuplicateKunjungan']);

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

});
