using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using ApiProjectMagangDotnet.AppDbProfile;
using Microsoft.EntityFrameworkCore;

namespace ApiProjectMagangDotnet.Data
{
    public interface IIdPasienGeneratorService
    {
        Task<string> GenerateRekamMedisIdAsync();
        (int umur, int bulan) CalculateAge(DateOnly tanggalLahir);
    }
    public class IdPasienGeneratorService : IIdPasienGeneratorService
    {
        private readonly ApplicationDbContext _context;

        public IdPasienGeneratorService(ApplicationDbContext context)
        {
            _context = context;
        }

        public (int umur, int bulan) CalculateAge(DateOnly tanggalLahir)
        {
            var today = DateOnly.FromDateTime(DateTime.Today);
            
            int umur = today.Year - tanggalLahir.Year;
            int bulan = today.Month - tanggalLahir.Month;

            // Jika bulan lahir belum terjadi tahun ini, kurangi 1 tahun
            if (today.Month < tanggalLahir.Month || 
                (today.Month == tanggalLahir.Month && today.Day < tanggalLahir.Day))
            {
                umur--;
                bulan = 12 - tanggalLahir.Month + today.Month;
            }

            // Jika hari lahir belum terjadi bulan ini, kurangi 1 bulan
            if (today.Day < tanggalLahir.Day)
            {
                bulan--;
                if (bulan < 0)
                {
                    bulan = 11;
                    umur--;
                }
            }

            // Untuk bayi di bawah 1 tahun, hitung bulan saja
            if (umur == 0)
            {
                bulan = today.Month - tanggalLahir.Month;
                if (today.Day < tanggalLahir.Day)
                {
                    bulan--;
                }
                if (bulan < 0)
                {
                    bulan += 12;
                }
            }

            return (umur, bulan);
        }

        public async Task<string> GenerateRekamMedisIdAsync()
        {
                var lastId = await _context.RekamMediss
                .OrderByDescending(r => r.Id_RekamMedis)
                .Select(r => r.Id_RekamMedis)
                .FirstOrDefaultAsync();

            int nextNumber = 1;
            
            if (!string.IsNullOrEmpty(lastId) && int.TryParse(lastId, out int lastNumber))
            {
                nextNumber = lastNumber + 1;
            }

            // Format ke 8 digit dengan leading zeros
            return nextNumber.ToString("D8");

        }
    }
}