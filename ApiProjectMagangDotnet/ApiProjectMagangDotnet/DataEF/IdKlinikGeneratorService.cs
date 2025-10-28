using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using ApiProjectMagangDotnet.AppDbProfile;
using ApiProjectMagangDotnet.Data;
using Microsoft.EntityFrameworkCore;

namespace ApiProjectMagangDotnet.DataEF
{
    public class IdKlinikGeneratorService : IIdKlinikGeneratorService
    {
        private readonly ApplicationDbContext _context;
        private readonly Random _random;

        public IdKlinikGeneratorService(ApplicationDbContext context)
        {
            _context = context;
            _random = new Random();
        }

        public async Task<string> GenerateKlinikIdAsync()
        {
            string newId;
            bool idExists;
            int attempts = 0;
            const int maxAttempts = 100;

            do
            {
                // Generate random 4-digit number
                int randomNumber = _random.Next(0, 10000);
                newId = randomNumber.ToString("D4"); // Format to 4 digits with leading zeros
                
                // Check if ID already exists
                idExists = await _context.Kliniks
                    .AnyAsync(d => d.Id_Klinik == newId);
                
                attempts++;
                
                if (attempts >= maxAttempts)
                {
                    throw new Exception("Cannot generate unique ID after multiple attempts");
                }
            }
            while (idExists);

            return newId;
        }
    }
}