using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using ApiProjectMagangDotnet.AppDbProfile;
using ApiProjectMagangDotnet.Data;
using ApiProjectMagangDotnet.Models;

namespace ApiProjectMagangDotnet.DataEF
{
    public class KlinikEF : IKlinik
    {
        private readonly ApplicationDbContext _context;
        public KlinikEF(ApplicationDbContext context)
        {
            _context = context;
        }

        public Klinik AddKlinik(Klinik klinik)
        {
            try
            {
                // Cek apakah ID sudah ada
                var existingRecord = _context.Kliniks
                    .FirstOrDefault(r => r.Id_Klinik == klinik.Id_Klinik);
                
                if (existingRecord != null)
                {
                    throw new Exception($"Rekam medis dengan ID {klinik.Id_Klinik} sudah ada");
                }

                _context.Kliniks.Add(klinik);
                _context.SaveChanges();
                return klinik;
            }
            catch(Exception ex)
            {
                throw new Exception("tidak bisa menambah rekam medis", ex);
            }
        }

        public void DeleteKlinik(string Id_Klinik)
        {
            var klinik = GetKlinikById(Id_Klinik);
            if(klinik == null)
            {
                throw new Exception("tidak ada");
            }

            try
            {
                _context.Kliniks.Remove(klinik);
                _context.SaveChanges();
            }

            catch (Exception ex)
            {
                throw new Exception("tidak ada",ex);
            }
        }

        public IEnumerable<Klinik> GetKlinik()
        {
            var klinik = _context.Kliniks
            .OrderByDescending(c=>c.Id_Klinik);
            return klinik;
        }

        public Klinik GetKlinikById(string Id_Klinik)
        {
            var klinik = _context.Kliniks
            .FirstOrDefault(c => c.Id_Klinik == Id_Klinik);
            if (klinik == null)
            {
                throw new Exception("Klinik not found");
            }
            return klinik; 
        }

        public Klinik UpdateKlinik(Klinik klinik)
        {
            var existingklinik = GetKlinikById(klinik.Id_Klinik);
            if (existingklinik == null)
            {
                throw new Exception ("not found");
            }

            try
            {
                existingklinik.Nama = klinik.Nama;
                existingklinik.Jenis = klinik.Jenis;
                existingklinik.Gedung = klinik.Gedung;
                existingklinik.Lantai = klinik.Lantai;
                existingklinik.Kapasitas = klinik.Kapasitas;
                existingklinik.Keterangan = klinik.Keterangan;

                _context.Kliniks.Update(existingklinik);
                _context.SaveChanges();
                return existingklinik;
            }

            catch(Exception ex)
            {
                throw new Exception("Could not update sale",ex);
            }

        }
    }
}