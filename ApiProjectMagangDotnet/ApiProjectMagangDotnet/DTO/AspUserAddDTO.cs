using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace ApiProjectMagangDotnet.DTO
{
    public class AspUserAddDTO
    {
        public string Username { get; set; } = null!;
        public string Password { get; set; } = null!;
        public string NamaUser { get; set; } = null!;
        public string Email { get; set; } = null!;
        public string NomorHP { get; set; } = null!;
    }
}