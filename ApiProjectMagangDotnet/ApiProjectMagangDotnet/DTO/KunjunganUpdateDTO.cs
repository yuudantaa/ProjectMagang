using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace ApiProjectMagangDotnet.DTO
{
    public class KunjunganUpdateDTO
    {
        public string Id_RekamMedis { get; set; } = null!;
        public string Id_Dokter { get; set; } = null!;
        public string Id_Klinik { get; set; } = null!;
        public string Keluhan { get; set; } = null!;
        public string Diagnosis { get; set; } = null!;
        public int NoAntrian { get; set; } 
        public DateOnly Tanggal { get; set; }
    }
}