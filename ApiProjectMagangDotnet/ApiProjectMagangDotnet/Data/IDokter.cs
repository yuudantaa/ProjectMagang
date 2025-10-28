using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using ApiProjectMagangDotnet.Models;

namespace ApiProjectMagangDotnet.Data
{
    public interface IDokter
    {
        IEnumerable<Dokter>GetDokters();
        Dokter GetDoktersById(string id_Dokter);
        Dokter AddDokter(Dokter dokter);
        Dokter UpdateDokter(Dokter dokter);
        void DeleteDokter(string id_Dokter);
    }
}