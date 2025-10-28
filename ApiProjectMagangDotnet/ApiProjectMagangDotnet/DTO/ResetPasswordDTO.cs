using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.Linq;
using System.Threading.Tasks;

namespace ApiProjectMagangDotnet.DTO
{
    public class ResetPasswordDTO
    {
        [Required(ErrorMessage = "Token harus diisi")]
        public string Token { get; set; } = null!;
        
        [Required(ErrorMessage = "Password baru harus diisi")]
        [MinLength(6, ErrorMessage = "Password minimal 6 karakter")]
        public string NewPassword { get; set; } = null!;
        
        [Required(ErrorMessage = "Konfirmasi password harus diisi")]
        [Compare("NewPassword", ErrorMessage = "Password dan konfirmasi password tidak sama")]
        public string ConfirmPassword { get; set; } = null!;
    }
}