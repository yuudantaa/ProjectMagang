using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using ApiProjectMagangDotnet.AppDbProfile;
using ApiProjectMagangDotnet.Data;
using ApiProjectMagangDotnet.Models;

namespace ApiProjectMagangDotnet.DataEF
{
    public class DokterEF : IDokter
    {
        private readonly ApplicationDbContext _context;
        public DokterEF(ApplicationDbContext context)
        {
            _context = context;
        }

        public Dokter AddDokter(Dokter dokter)
        {
            try
            {
                // Cek apakah ID sudah ada
                var existingRecord = _context.Dokters
                    .FirstOrDefault(r => r.Id_Dokter == dokter.Id_Dokter);
                
                if (existingRecord != null)
                {
                    throw new Exception($"Rekam medis dengan ID {dokter.Id_Dokter} sudah ada");
                }

                _context.Dokters.Add(dokter);
                _context.SaveChanges();
                return dokter;
            }
            catch(Exception ex)
            {
                throw new Exception("tidak bisa menambah rekam medis", ex);
            }

        }

        public void DeleteDokter(string id_Dokter)
        {
            var dokter = GetDoktersById(id_Dokter);
            if(dokter == null)
            {
                throw new Exception("tidak ada");
            }

            try
            {
                _context.Dokters.Remove(dokter);
                _context.SaveChanges();
            }

            catch (Exception ex)
            {
                throw new Exception("tidak ada",ex);
            }

        }

        public IEnumerable<Dokter> GetDokters()
        {
            var dokter = _context.Dokters
            .OrderByDescending(c=>c.Id_Dokter);
            return dokter;

        }

        public Dokter GetDoktersById(string id_Dokter)
        {
            var dokter = _context.Dokters
            .FirstOrDefault(c => c.Id_Dokter == id_Dokter);
            if (dokter == null)
            {
                throw new Exception("Sale not found");
            }
            return dokter; 
        }

        public Dokter UpdateDokter(Dokter dokter)
        {
            var existingdokter = GetDoktersById(dokter.Id_Dokter);
            if (existingdokter == null)
            {
                throw new Exception ("not found");
            }

            try
            {
                existingdokter.Nama = dokter.Nama;
                existingdokter.Spesialisasi = dokter.Spesialisasi;
                existingdokter.NoHP = dokter.NoHP;
                existingdokter.Email = dokter.Email;
                existingdokter.HariPraktek = dokter.HariPraktek;
                existingdokter.JamMulai = dokter.JamMulai;
                existingdokter.JamSelesai = dokter.JamSelesai;
                _context.Dokters.Update(existingdokter);
                _context.SaveChanges();
                return existingdokter;
            }

            catch(Exception ex)
            {
                throw new Exception("Could not update sale",ex);
            }

        }
    }
}