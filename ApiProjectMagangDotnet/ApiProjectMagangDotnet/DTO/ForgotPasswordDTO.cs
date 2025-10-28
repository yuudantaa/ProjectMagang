using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.Linq;
using System.Threading.Tasks;

namespace ApiProjectMagangDotnet.DTO
{
    public class ForgotPasswordDTO
    {
        [Required(ErrorMessage = "Email atau username harus diisi")]
        public string EmailOrUsername { get; set; } = null!;
    }
}