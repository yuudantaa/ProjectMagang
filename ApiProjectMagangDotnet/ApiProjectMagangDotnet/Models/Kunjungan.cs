using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.EntityFrameworkCore;

namespace ApiProjectMagangDotnet.Models
{
    [Table("Kunjungan")]
    [Index(nameof(Id_RekamMedis), nameof(Id_Dokter), nameof(Id_Klinik), nameof(Tanggal), 
          IsUnique = true, Name = "IX_Unique_Kunjungan")]
    public class Kunjungan
    {
        [Key]
        public string Id_Kunjungan { get; set; } = null!;
        public int NoAntrian { get; set; }
        public DateOnly Tanggal { get; set; }
        public string JenisKunjungan { get; set; } = "Baru";

        [ForeignKey("RekamMedis")]
        public string Id_RekamMedis { get; set; } = null!;

        [ForeignKey("Dokter")]
        public string Id_Dokter { get; set; } = null!;

        [ForeignKey("Klinik")]
        public string Id_Klinik { get; set; } = null!;

        public string? Keluhan { get; set; } 
        public string? Diagnosis { get; set; } 
        public RekamMedis? RekamMedis { get; set; } = null!;
        public Klinik? Klinik { get; set; } = null!;
        public Dokter? Dokter { get; set; } = null!;
    }
}