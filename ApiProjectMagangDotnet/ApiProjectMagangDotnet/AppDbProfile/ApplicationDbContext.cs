using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using ApiProjectMagangDotnet.Models;
using Microsoft.EntityFrameworkCore;

namespace ApiProjectMagangDotnet.AppDbProfile
{
    public class ApplicationDbContext : DbContext
    {
        public ApplicationDbContext(DbContextOptions<ApplicationDbContext> options) : base(options)
        {
        }

        public DbSet<Dokter> Dokters { get; set; } = null!;
        public DbSet<Klinik> Kliniks { get; set; } = null!;
        public DbSet<Kunjungan> Kunjungans { get; set; } = null!;
        public DbSet<RekamMedis> RekamMediss { get; set; } = null!;
        public DbSet<AspUser> AspUsers { get; set; } = null!;
        public DbSet<UserPasien> UserPasiens { get; set; } = null!;
        public DbSet<PasswordReset> PasswordResets { get; set; } = null!;
    }
} 
