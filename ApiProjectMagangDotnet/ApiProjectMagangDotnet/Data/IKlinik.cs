using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using ApiProjectMagangDotnet.Models;

namespace ApiProjectMagangDotnet.Data
{
    public interface IKlinik
    {
        IEnumerable<Klinik>GetKlinik();
        Klinik GetKlinikById(string Id_Klinik);
        Klinik AddKlinik(Klinik klinik);
        Klinik UpdateKlinik(Klinik klinik);
        void DeleteKlinik(string Id_Klinik);
    }
}