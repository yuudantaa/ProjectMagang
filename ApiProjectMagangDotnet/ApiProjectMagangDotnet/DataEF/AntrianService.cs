using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using ApiProjectMagangDotnet.AppDbProfile;
using Microsoft.EntityFrameworkCore;

namespace ApiProjectMagangDotnet.DataEF
{
    public interface IAntrianService
    {
        Task<int> GenerateNextAntrian(string idKlinik, DateOnly tanggal);
    }

    public class AntrianService : IAntrianService
    {
    private readonly ApplicationDbContext _context;

    public AntrianService(ApplicationDbContext context)
    {
        _context = context;
    }
        public async Task<int> GenerateNextAntrian(string idKlinik, DateOnly tanggal)
        {
            var lastAntrian = await _context.Kunjungans
            .Where(k => k.Id_Klinik == idKlinik && k.Tanggal == tanggal)
            .OrderByDescending(k => k.NoAntrian)
            .FirstOrDefaultAsync();

        // Jika tidak ada antrian sebelumnya, mulai dari 1
        if (lastAntrian == null)
        {
            return 1;
        }

        // Tambah 1 dari nomor antrian terakhir
        return lastAntrian.NoAntrian + 1;
        }
    }
}