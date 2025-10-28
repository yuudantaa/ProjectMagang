using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using ApiProjectMagangDotnet.Models;

namespace ApiProjectMagangDotnet.Data
{
    public interface IKunjungan
    {

        Task<IEnumerable<Kunjungan>> GetKunjunganBaruAll();
        Task<IEnumerable<Kunjungan>> GetKunjunganLamaAll();
        Task<Kunjungan> GetKunjunganById(string Id_Kunjungan);
        Task<Kunjungan> AddKunjungan(Kunjungan kunjungan);
        Task<Kunjungan> AddKunjunganLama(Kunjungan kunjungan);
        Task<Kunjungan> UpdateKunjungan(Kunjungan kunjungan);
        Task DeleteKunjungan(string Id_Kunjungan);
        Task<Kunjungan> UpdateKunjunganLama(Kunjungan kunjungan);
        Task DeleteKunjunganLama(string Id_Kunjungan);
        Task<bool> IsDuplicateKunjungan(string idRekamMedis, string idDokter, string idKlinik, DateOnly tanggal);
        Task<IEnumerable<Kunjungan>> GetAllKunjungan();
    }
}