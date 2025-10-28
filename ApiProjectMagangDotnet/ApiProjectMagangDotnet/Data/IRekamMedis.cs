using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using ApiProjectMagangDotnet.Models;

namespace ApiProjectMagangDotnet.Data
{
    public interface IRekamMedis
    {
        IEnumerable<RekamMedis> GetRekamMedis();
        RekamMedis GetRekamMedisById(string Id_RekamMedis);
        RekamMedis AddRekamMedis(RekamMedis rekamMedis);
        RekamMedis UpdateRekamMedis(RekamMedis rekamMedis);
        void DeleteRekamMedis(string Id_RekamMedis);
    }
}