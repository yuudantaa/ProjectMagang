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
    public class RekamMedisEF : IRekamMedis
    {
        private readonly ApplicationDbContext _context;
        private readonly IUserPasien _userPasien;

        public RekamMedisEF(ApplicationDbContext context, IUserPasien userPasien)
        {
            _context = context;
            _userPasien = userPasien;
        }
        public RekamMedis AddRekamMedis(RekamMedis rekamMedis)
        {
            using var transaction = _context.Database.BeginTransaction();
            try
            {
                // Cek apakah ID sudah ada
                var existingRecord = _context.RekamMediss
                    .FirstOrDefault(r => r.Id_RekamMedis == rekamMedis.Id_RekamMedis);
                
                if (existingRecord != null)
                {
                    throw new Exception($"Rekam medis dengan ID {rekamMedis.Id_RekamMedis} sudah ada");
                }

                // Cek apakah no ktp sudah ada
                var existingNoKTP = _context.RekamMediss
                    .FirstOrDefault(r => r.NoKTP == rekamMedis.NoKTP);
                
                if (existingNoKTP != null)
                {
                    throw new Exception($"Pasien dengan No KTP {rekamMedis.NoKTP} sudah ada");
                }

                _context.RekamMediss.Add(rekamMedis);
                _context.SaveChanges();

                // BUAT USER PASIEN OTOMATIS SETELAH REKAM MEDIS BERHASIL DIBUAT
                var userPasien = _userPasien.CreateUserPasienFromRekamMedis(rekamMedis);

                transaction.Commit();
                return rekamMedis;
            }
            catch(Exception ex)
            {
                transaction.Rollback();
                throw new Exception("Tidak bisa menambah rekam medis: " + ex.Message, ex);
            }
        }

        public void DeleteRekamMedis(string Id_RekamMedis)
        {
            var rekamMedis = GetRekamMedisById(Id_RekamMedis);
            if(rekamMedis == null)
            {
                throw new Exception("tidak ada");
            }

            try
            {
                _context.RekamMediss.Remove(rekamMedis);
                _context.SaveChanges();
            }

            catch (Exception ex)
            {
                throw new Exception("tidak ada",ex);
            }
        }

        public IEnumerable<RekamMedis> GetRekamMedis()
        {
            var rekam = _context.RekamMediss
            .OrderByDescending(c=>c.Id_RekamMedis);
            return rekam;
        }

        public RekamMedis GetRekamMedisById(string Id_RekamMedis)
        {
            var rekam = _context.RekamMediss
            .FirstOrDefault(c => c.Id_RekamMedis == Id_RekamMedis);
            if (rekam == null)
            {
                throw new Exception("rekam medis not found");
            }
            return rekam; 
        }

        public RekamMedis UpdateRekamMedis(RekamMedis rekamMedis)
        {
            var existingrekam = GetRekamMedisById(rekamMedis.Id_RekamMedis);
            if (existingrekam == null)
            {
                throw new Exception ("not found");
            }

            try
            {
                existingrekam.Nama = rekamMedis.Nama;
                existingrekam.Gender = rekamMedis.Gender;
                existingrekam.NoKTP = rekamMedis.NoKTP;
                existingrekam.NoTelp = rekamMedis.NoTelp;
                existingrekam.Email = rekamMedis.Email;
                existingrekam.TanggalLahir = rekamMedis.TanggalLahir;
                existingrekam.TempatLahir = rekamMedis.TempatLahir;
                existingrekam.Umur = rekamMedis.Umur;
                existingrekam.Bulan = rekamMedis.Bulan;

                existingrekam.Pekerjaan = rekamMedis.Pekerjaan;
                existingrekam.Pendidikan = rekamMedis.Pendidikan;
                existingrekam.Agama = rekamMedis.Agama;
                existingrekam.StatusKawin = rekamMedis.StatusKawin;
                existingrekam.Bahasa = rekamMedis.Bahasa;
                existingrekam.ButuhPenerjemah = rekamMedis.ButuhPenerjemah;
                existingrekam.Alamat = rekamMedis.Alamat;

                existingrekam.Kabupaten = rekamMedis.Kabupaten;
                existingrekam.Kecamatan = rekamMedis.Kecamatan;
                existingrekam.Provinsi = rekamMedis.Provinsi;
               
                _context.RekamMediss.Update(existingrekam);
                _context.SaveChanges();
                return existingrekam;
            }

            catch(Exception ex)
            {
                throw new Exception("Could not update sale",ex);
            }

        }
    }
}