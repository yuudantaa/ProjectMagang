using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Linq;
using System.Threading.Tasks;

namespace ApiProjectMagangDotnet.Models
{
    public class AspUser
    {
        [Key]
        public string Username { get; set; } = null!;
        public string Password { get; set; } = null!;
        public string NamaUser { get; set; } = null!;
        public string Email { get; set; } = null!;
        public string NomorHP { get; set; } = null!;
    }
}