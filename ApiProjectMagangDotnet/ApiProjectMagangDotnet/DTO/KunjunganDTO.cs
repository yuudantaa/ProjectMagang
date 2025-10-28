using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace ApiProjectMagangDotnet.DTO
{
    public class KunjunganDTO
    {
        public string Id_Kunjungan { get; set; } = null!;
        public int NoAntrian { get; set; } 
        public DateOnly Tanggal { get; set; }        
        public string Keluhan { get; set; } = null!;
        public string Diagnosis { get; set; } = null!;
        public string JenisKunjungan { get; set; } = "Baru";
        public RekamMedisDTO? RekamMedis { get; set; } = null!;
        public KlinikDTO? Klinik { get; set; } = null!;
        public DokterDTO? Dokter { get; set; } = null!;
    }
}