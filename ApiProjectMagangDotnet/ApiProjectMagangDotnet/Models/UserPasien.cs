using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Linq;
using System.Threading.Tasks;

namespace ApiProjectMagangDotnet.Models
{
    public class UserPasien
    {
        [Key]
        public string Username { get; set; } = null!;
        public string Password { get; set; } = null!;
        public string NamaUser { get; set; } = null!;
        public string Email { get; set; } = null!;
        public string NomorHP { get; set; } = null!;
        [ForeignKey("RekamMedis")]
        public string Id_RekamMedis { get; set; } = null!;
        public RekamMedis? RekamMedis { get; set; }
    }
}