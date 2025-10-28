using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Linq;
using System.Threading.Tasks;

namespace ApiProjectMagangDotnet.Models
{
    public class Klinik
    {
        [Key]
        public string Id_Klinik { get; set; } = null!;

        public string Nama { get; set; } = null!;
        public string Jenis { get; set; } = null!;
        public string Gedung { get; set; } = null!;
        public string Lantai { get; set; } = null!;
        public int Kapasitas { get; set; }
        public string? Keterangan { get; set; }
    }
}