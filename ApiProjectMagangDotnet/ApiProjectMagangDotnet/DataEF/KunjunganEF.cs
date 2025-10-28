using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using ApiProjectMagangDotnet.AppDbProfile;
using ApiProjectMagangDotnet.Data;
using ApiProjectMagangDotnet.Models;
using Microsoft.EntityFrameworkCore;

namespace ApiProjectMagangDotnet.DataEF
{
    public class KunjunganEF : IKunjungan
    {
        private readonly ApplicationDbContext _context;
        private readonly IIdGeneratorService _idGenerator;
        private readonly IAntrianService _antrianService;

        public KunjunganEF(ApplicationDbContext context, 
                        IIdGeneratorService idGenerator,
                        IAntrianService antrianService)
        {
            _context = context;
            _idGenerator = idGenerator;
            _antrianService = antrianService;
        }
        public async Task<Kunjungan> AddKunjungan(Kunjungan kunjungan)
        {
            try
            {
                kunjungan.Id_Kunjungan = _idGenerator.GenerateKunjunganId(
                    kunjungan.Id_RekamMedis, 
                    kunjungan.Id_Dokter, 
                    kunjungan.Id_Klinik, 
                    kunjungan.Tanggal
                );

                // Cek apakah ID sudah ada
                var existingRecord = await _context.Kunjungans
                    .FirstOrDefaultAsync(r => r.Id_Kunjungan == kunjungan.Id_Kunjungan);
                
                if (existingRecord != null)
                {
                    int counter = 1;
                    string originalId = kunjungan.Id_Kunjungan;
                    
                    while (await _context.Kunjungans.AnyAsync(r => r.Id_Kunjungan == kunjungan.Id_Kunjungan))
                    {
                        kunjungan.Id_Kunjungan = $"{originalId}_{counter}";
                        counter++;
                    }
                }

                // Validasi foreign keys
                var existingRekamMedis = await _context.RekamMediss
                    .FirstOrDefaultAsync(r => r.Id_RekamMedis == kunjungan.Id_RekamMedis);
                
                if (existingRekamMedis == null)
                {
                    throw new Exception($"Rekam medis dengan ID {kunjungan.Id_RekamMedis} tidak ada");
                }

                var existingDokter = await _context.Dokters
                    .FirstOrDefaultAsync(d => d.Id_Dokter == kunjungan.Id_Dokter);
                
                if (existingDokter == null)
                {
                    throw new Exception($"Dokter dengan ID {kunjungan.Id_Dokter} tidak ada");
                }

                var existingKlinik = await _context.Kliniks
                    .FirstOrDefaultAsync(k => k.Id_Klinik == kunjungan.Id_Klinik);
                
                if (existingKlinik == null)
                {
                    throw new Exception($"Klinik dengan ID {kunjungan.Id_Klinik} tidak ada");
                }

                // Generate nomor antrian otomatis
                kunjungan.NoAntrian = await _antrianService.GenerateNextAntrian(
                    kunjungan.Id_Klinik, 
                    kunjungan.Tanggal
                );

                // VALIDASI UNIK: Pasien, Dokter, Klinik, dan Tanggal harus unik
                var existingKunjungan = await _context.Kunjungans
                    .FirstOrDefaultAsync(k => k.Id_RekamMedis == kunjungan.Id_RekamMedis &&
                                            k.Id_Dokter == kunjungan.Id_Dokter &&
                                            k.Id_Klinik == kunjungan.Id_Klinik &&
                                            k.Tanggal == kunjungan.Tanggal);

                if (existingKunjungan != null)
                {
                    throw new Exception($"Sudah ada kunjungan untuk pasien, dokter, dan klinik ini pada tanggal {kunjungan.Tanggal}");
                }

                await _context.Kunjungans.AddAsync(kunjungan);
                await _context.SaveChangesAsync();
                return kunjungan;
            }
            catch(Exception ex)
            {
                Console.WriteLine($"Error AddKunjungan: {ex.Message}");
                Console.WriteLine($"Stack Trace: {ex.StackTrace}");
                throw new Exception("Tidak bisa menambah kunjungan: " + ex.Message, ex);
            }
        }

        public async Task<Kunjungan> AddKunjunganLama(Kunjungan kunjungan)
        {
            try
            {
                kunjungan.Id_Kunjungan = _idGenerator.GenerateKunjunganId(
                    kunjungan.Id_RekamMedis, 
                    kunjungan.Id_Dokter, 
                    kunjungan.Id_Klinik, 
                    kunjungan.Tanggal
                );

                // Cek apakah ID sudah ada
                var existingRecord = await _context.Kunjungans
                    .FirstOrDefaultAsync(r => r.Id_Kunjungan == kunjungan.Id_Kunjungan);
                
                if (existingRecord != null)
                {
                    int counter = 1;
                    string originalId = kunjungan.Id_Kunjungan;
                    
                    while (await _context.Kunjungans.AnyAsync(r => r.Id_Kunjungan == kunjungan.Id_Kunjungan))
                    {
                        kunjungan.Id_Kunjungan = $"{originalId}_{counter}";
                        counter++;
                    }
                }

                // Validasi foreign keys
                var existingRekamMedis = await _context.RekamMediss
                    .FirstOrDefaultAsync(r => r.Id_RekamMedis == kunjungan.Id_RekamMedis);
                
                if (existingRekamMedis == null)
                {
                    throw new Exception($"Rekam medis dengan ID {kunjungan.Id_RekamMedis} tidak ada");
                }

                var existingDokter = await _context.Dokters
                    .FirstOrDefaultAsync(d => d.Id_Dokter == kunjungan.Id_Dokter);
                
                if (existingDokter == null)
                {
                    throw new Exception($"Dokter dengan ID {kunjungan.Id_Dokter} tidak ada");
                }

                var existingKlinik = await _context.Kliniks
                    .FirstOrDefaultAsync(k => k.Id_Klinik == kunjungan.Id_Klinik);
                
                if (existingKlinik == null)
                {
                    throw new Exception($"Klinik dengan ID {kunjungan.Id_Klinik} tidak ada");
                }

                // Generate nomor antrian otomatis
                kunjungan.NoAntrian = await _antrianService.GenerateNextAntrian(
                    kunjungan.Id_Klinik, 
                    kunjungan.Tanggal
                );

                // VALIDASI UNIK: Pasien, Dokter, Klinik, dan Tanggal harus unik
                var existingKunjungan = await _context.Kunjungans
                    .FirstOrDefaultAsync(k => k.Id_RekamMedis == kunjungan.Id_RekamMedis &&
                                            k.Id_Dokter == kunjungan.Id_Dokter &&
                                            k.Id_Klinik == kunjungan.Id_Klinik &&
                                            k.Tanggal == kunjungan.Tanggal);

                if (existingKunjungan != null)
                {
                    throw new Exception($"Sudah ada kunjungan untuk pasien, dokter, dan klinik ini pada tanggal {kunjungan.Tanggal}");
                }

                await _context.Kunjungans.AddAsync(kunjungan);
                await _context.SaveChangesAsync();
                return kunjungan;
            }
            catch(Exception ex)
            {
                Console.WriteLine($"Error AddKunjunganLama: {ex.Message}");
                Console.WriteLine($"Stack Trace: {ex.StackTrace}");
                throw new Exception("Tidak bisa menambah kunjungan lama: " + ex.Message, ex);
            }
        }

        public async Task DeleteKunjungan(string Id_Kunjungan)
        {
            var kunjungan = await _context.Kunjungans
                .FirstOrDefaultAsync(c => c.Id_Kunjungan == Id_Kunjungan); 
            
            if(kunjungan == null)
            {
                throw new Exception("Kunjungan tidak ditemukan");
            }

            try
            {
                _context.Kunjungans.Remove(kunjungan);
                await _context.SaveChangesAsync();
            }
            catch (Exception ex)
            {
                throw new Exception("Tidak bisa menghapus kunjungan: " + ex.Message, ex);
            }
        }

        public async Task DeleteKunjunganLama(string Id_Kunjungan)
        {
            var kunjungan = await _context.Kunjungans
                .FirstOrDefaultAsync(c => c.Id_Kunjungan == Id_Kunjungan); 
            
            if(kunjungan == null)
            {
                throw new Exception("Kunjungan tidak ditemukan");
            }

            try
            {
                // Validasi: Pastikan ini adalah kunjungan pasien lama
                if (kunjungan.JenisKunjungan != "Lama")
                {
                    throw new Exception("Hanya kunjungan pasien lama yang dapat dihapus dengan metode ini");
                }

                // Hitung jumlah kunjungan untuk pasien ini
                var jumlahKunjunganPasien = await _context.Kunjungans
                    .CountAsync(k => k.Id_RekamMedis == kunjungan.Id_RekamMedis);
                
                if (jumlahKunjunganPasien <= 1)
                {
                    throw new Exception("Tidak dapat menghapus kunjungan karena pasien hanya memiliki satu kunjungan");
                }

                _context.Kunjungans.Remove(kunjungan);
                await _context.SaveChangesAsync();
            }
            catch (Exception ex)
            {
                throw new Exception("Tidak bisa menghapus kunjungan pasien lama: " + ex.Message, ex);
            }
        }

        public async Task<IEnumerable<Kunjungan>> GetAllKunjungan()
        {
                return await _context.Kunjungans
                .Include(s => s.Klinik)
                .Include(s => s.Dokter)
                .Include(s => s.RekamMedis)
                .OrderByDescending(k => k.Tanggal)
                .ThenByDescending(k => k.NoAntrian)
                .ToListAsync();
        }

        public async Task<IEnumerable<Kunjungan>> GetKunjunganBaruAll()
        {
                    return await _context.Kunjungans
            .Include(s => s.Klinik)
            .Include(s => s.Dokter)
            .Include(s => s.RekamMedis)
            .Where(k => k.JenisKunjungan == "Baru")
            .OrderByDescending(c => c.Tanggal)
            .ThenByDescending(c => c.NoAntrian)
            .ToListAsync();

        }

        public async Task<Kunjungan> GetKunjunganById(string Id_Kunjungan)
        {
                var kunjungan = await _context.Kunjungans
                .Include(s => s.Klinik)
                .Include(s => s.Dokter)
                .Include(s => s.RekamMedis)
                .FirstOrDefaultAsync(c => c.Id_Kunjungan == Id_Kunjungan);
            
            if (kunjungan == null)
            {
                throw new Exception("Kunjungan not found");
            }
            return kunjungan;
        }

        public async Task<IEnumerable<Kunjungan>> GetKunjunganLamaAll()
        {
                   return await _context.Kunjungans
            .Include(s => s.Klinik)
            .Include(s => s.Dokter)
            .Include(s => s.RekamMedis)
            .Where(k => k.JenisKunjungan == "Lama")
            .OrderByDescending(k => k.Tanggal)
            .ThenByDescending(k => k.NoAntrian)
            .ToListAsync();
        }

        public async Task<bool> IsDuplicateKunjungan(string idRekamMedis, string idDokter, string idKlinik, DateOnly tanggal)
        {
                try
            {
                var existingKunjungan = await _context.Kunjungans
                    .FirstOrDefaultAsync(k => k.Id_RekamMedis == idRekamMedis &&
                                            k.Id_Dokter == idDokter &&
                                            k.Id_Klinik == idKlinik &&
                                            k.Tanggal == tanggal);
                
                return existingKunjungan != null;
            }
            catch (Exception ex)
            {
                throw new Exception($"Error checking duplicate kunjungan: {ex.Message}");
            }
        }

        public async Task<Kunjungan> UpdateKunjungan(Kunjungan kunjungan)
        {
            var existingkunjungan = await _context.Kunjungans
                .FirstOrDefaultAsync(c => c.Id_Kunjungan == kunjungan.Id_Kunjungan); 
            
            if (existingkunjungan == null)
            {
                throw new Exception ("Kunjungan tidak ditemukan");
            }

            try
            {
                existingkunjungan.Id_Dokter = kunjungan.Id_Dokter;
                existingkunjungan.Id_RekamMedis = kunjungan.Id_RekamMedis;
                existingkunjungan.Id_Klinik = kunjungan.Id_Klinik;
                existingkunjungan.Tanggal = kunjungan.Tanggal;
                existingkunjungan.JenisKunjungan = kunjungan.JenisKunjungan;
                existingkunjungan.NoAntrian = kunjungan.NoAntrian;
                existingkunjungan.Keluhan = kunjungan.Keluhan;
                existingkunjungan.Diagnosis = kunjungan.Diagnosis;
                
                _context.Kunjungans.Update(existingkunjungan);
                await _context.SaveChangesAsync();
                return existingkunjungan;
            }
            catch(Exception ex)
            {
                throw new Exception("Tidak bisa mengupdate kunjungan: " + ex.Message, ex);
            }
        }

        public async Task<Kunjungan> UpdateKunjunganLama(Kunjungan kunjungan)
        {
            var existingKunjungan = await _context.Kunjungans
                .FirstOrDefaultAsync(c => c.Id_Kunjungan == kunjungan.Id_Kunjungan); 
            
            if (existingKunjungan == null)
            {
                throw new Exception("Kunjungan tidak ditemukan");
            }

            try
            {
                // Validasi foreign keys
                var existingRekamMedis = await _context.RekamMediss
                    .FirstOrDefaultAsync(r => r.Id_RekamMedis == kunjungan.Id_RekamMedis);
                
                if (existingRekamMedis == null)
                {
                    throw new Exception($"Rekam medis dengan ID {kunjungan.Id_RekamMedis} tidak ditemukan");
                }

                var existingDokter = await _context.Dokters
                    .FirstOrDefaultAsync(d => d.Id_Dokter == kunjungan.Id_Dokter);
                
                if (existingDokter == null)
                {
                    throw new Exception($"Dokter dengan ID {kunjungan.Id_Dokter} tidak ditemukan");
                }

                var existingKlinik = await _context.Kliniks
                    .FirstOrDefaultAsync(k => k.Id_Klinik == kunjungan.Id_Klinik);
                
                if (existingKlinik == null)
                {
                    throw new Exception($"Klinik dengan ID {kunjungan.Id_Klinik} tidak ditemukan");
                }

                // Validasi nomor antrian
                if (kunjungan.NoAntrian <= 0)
                {
                    throw new Exception("Nomor antrian harus lebih besar dari 0");
                }

                // Cek apakah nomor antrian sudah digunakan pada tanggal dan klinik yang sama (kecuali untuk kunjungan ini sendiri)
                var existingAntrian = await _context.Kunjungans
                    .FirstOrDefaultAsync(k => k.Tanggal == kunjungan.Tanggal && 
                                        k.Id_Klinik == kunjungan.Id_Klinik && 
                                        k.NoAntrian == kunjungan.NoAntrian &&
                                        k.Id_Kunjungan != kunjungan.Id_Kunjungan);
                
                if (existingAntrian != null)
                {
                    throw new Exception($"Nomor antrian {kunjungan.NoAntrian} sudah digunakan pada tanggal {kunjungan.Tanggal} untuk klinik ini");
                }

                // Update data
                existingKunjungan.Id_Dokter = kunjungan.Id_Dokter;
                existingKunjungan.Id_RekamMedis = kunjungan.Id_RekamMedis;
                existingKunjungan.Id_Klinik = kunjungan.Id_Klinik;
                existingKunjungan.Tanggal = kunjungan.Tanggal;
                existingKunjungan.NoAntrian = kunjungan.NoAntrian;
                existingKunjungan.Keluhan = kunjungan.Keluhan;
                existingKunjungan.Diagnosis = kunjungan.Diagnosis;

                _context.Kunjungans.Update(existingKunjungan);
                await _context.SaveChangesAsync();
                
                return existingKunjungan;
            }
            catch(Exception ex)
            {
                throw new Exception("Tidak bisa mengupdate kunjungan pasien lama: " + ex.Message, ex);
            }
        }
    }
}