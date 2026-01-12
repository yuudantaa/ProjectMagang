using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace ApiProjectMagangDotnet.Data
{
    public interface IIdGeneratorService
    {
     string GenerateKunjunganId(string idRekamMedis, string idDokter, string idKlinik, DateOnly tanggal);
    }
    public class IdGeneratorService : IIdGeneratorService
    {
        public string GenerateKunjunganId(string idRekamMedis, string idDokter, string idKlinik, DateOnly tanggal)
        {
        string tahun = tanggal.Year.ToString("D4");
        string bulan = tanggal.Month.ToString("D2");
        string hari = tanggal.Day.ToString("D2");
        
        // Mengambil bagian unik dari foreign keys (misalnya 4 karakter terakhir)
        string rekamMedisSuffix = idRekamMedis.Length > 4 ? idRekamMedis.Substring(idRekamMedis.Length - 4) : idRekamMedis;
        string dokterSuffix = idDokter.Length > 4 ? idDokter.Substring(idDokter.Length - 4) : idDokter;
        string klinikSuffix = idKlinik.Length > 4 ? idKlinik.Substring(idKlinik.Length - 4) : idKlinik;
        
        return $"{tahun}{bulan}{hari}_{rekamMedisSuffix}_{dokterSuffix}_{klinikSuffix}";
    }
    
    }
}
