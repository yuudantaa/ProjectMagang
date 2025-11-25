using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace ApiProjectMagangDotnet.DTO
{
    public class KunjunganAddDTO
    {
    public string Id_Kunjungan { get; set; } = null!; 
    public string Id_RekamMedis { get; set; } = null!;
    public string Id_Dokter { get; set; } = null!;
    public string Id_Klinik { get; set; } = null!;
    public string JenisKunjungan { get; set; } = "Baru";
    public string? Keluhan { get; set; }
    public string? Diagnosis { get; set; } 
    public int NoAntrian { get; set; } 
    public DateOnly Tanggal { get; set; }
    }
}