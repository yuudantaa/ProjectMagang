using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.Linq;
using System.Threading.Tasks;

namespace ApiProjectMagangDotnet.DTO
{
    public class DokterAddDTO
    {
        public string? Id_Dokter { get; set; } 
        public string Nama { get; set; } = null!;

        public string Spesialisasi { get; set; } = null!;

        public string NoHP { get; set; } = null!;

        public string Email { get; set; } = null!;
        public string HariPraktek { get; set; } = null!;
        public TimeOnly JamMulai { get; set; } 
        public TimeOnly JamSelesai { get; set; } 
    }
}