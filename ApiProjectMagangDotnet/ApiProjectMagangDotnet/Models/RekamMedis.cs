using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Linq;
using System.Threading.Tasks;

namespace ApiProjectMagangDotnet.Models
{
    public class RekamMedis
    {

        [Key]
        public string Id_RekamMedis { get; set; } = null!;
        public string NoKTP { get; set; } = null!;
        public string Nama { get; set; } = null!;
        public string TempatLahir { get; set; } = null!;
        public DateOnly TanggalLahir { get; set; }
        public string Gender { get; set; } = null!;
        public int Umur { get; set; }
        public int Bulan { get; set; }
        public string Agama { get; set; } = null!;
        public string StatusKawin { get; set; } = null!;
        public string Pendidikan { get; set; } = null!;
        public string Pekerjaan { get; set; } = null!;
        public string Bahasa { get; set; } = null!;
        public string ButuhPenerjemah { get; set; } = null!;
        public string NoTelp { get; set; } = null!;
        public string Email { get; set; } = null!;
        public string Alamat { get; set; } = null!;
        public string Kecamatan { get; set; } = null!;
        public string Kabupaten { get; set; } = null!;
        public string Provinsi { get; set; } = null!;
    }
}