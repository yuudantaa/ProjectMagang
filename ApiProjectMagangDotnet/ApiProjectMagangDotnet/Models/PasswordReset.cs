using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.Linq;
using System.Threading.Tasks;

namespace ApiProjectMagangDotnet.Models
{
    public class PasswordReset
    {
        [Key]
        public int Id { get; set; }
        
        [Required]
        public string Username { get; set; } = null!;
        
        [Required]
        public string Token { get; set; } = null!;
        
        public DateTime ExpiryDate { get; set; }
        
        public bool IsUsed { get; set; } = false;
        
        public DateTime CreatedAt { get; set; } = DateTime.UtcNow;
    }
}