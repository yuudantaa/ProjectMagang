using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.Linq;
using System.Threading.Tasks;

namespace ApiProjectMagangDotnet.DTO
{
    public class KlinikAddDTO
    {
        public string? Id_Klinik { get; set; } 
        public string Nama { get; set; } = null!;
        public string Jenis { get; set; } = null!;
        public string Gedung { get; set; } = null!;

        public string Lantai { get; set; } = null!;
        public int Kapasitas { get; set; } 
        public string? Keterangan { get; set; }
    }
}