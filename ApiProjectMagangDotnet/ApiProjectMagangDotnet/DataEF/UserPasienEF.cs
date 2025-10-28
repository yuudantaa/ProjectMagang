using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using ApiProjectMagangDotnet.AppDbProfile;
using ApiProjectMagangDotnet.Data;
using ApiProjectMagangDotnet.Models;
using Microsoft.EntityFrameworkCore;

namespace ApiProjectMagangDotnet.DataEF
{
    public class UserPasienEF : IUserPasien
    {
        private readonly ApplicationDbContext _context;

        public UserPasienEF(ApplicationDbContext context)
        {
            _context = context;
        }

        public UserPasien AddUserPasien(UserPasien userPasien)
        {
            try
            {
                // Cek apakah username sudah ada
                var existingUsername = _context.UserPasiens
                    .FirstOrDefault(u => u.Username == userPasien.Username);
                
                if (existingUsername != null)
                {
                    throw new Exception($"Username {userPasien.Username} sudah ada");
                }

                // Cek apakah rekam medis sudah memiliki user
                var existingByRekamMedis = _context.UserPasiens
                    .FirstOrDefault(u => u.Id_RekamMedis == userPasien.Id_RekamMedis);
                
                if (existingByRekamMedis != null)
                {
                    throw new Exception($"Rekam medis {userPasien.Id_RekamMedis} sudah memiliki user");
                }

                // Hash password sebelum disimpan
                userPasien.Password = HashHelpers.HashPassword(userPasien.Password);

                _context.UserPasiens.Add(userPasien);
                _context.SaveChanges();
                return userPasien;
            }
            catch (Exception ex)
            {
                throw new Exception("Tidak bisa menambah UserPasien: " + ex.Message, ex);
            }
        }

        public UserPasien CreateUserPasienFromRekamMedis(RekamMedis rekamMedis)
        {
            var username = $"{rekamMedis.Id_RekamMedis}";

            // Generate password dari tanggal lahir (format: DDMMYYYY)
            var password = rekamMedis.TanggalLahir.ToString("ddMMyyyy");

            var userPasien = new UserPasien
            {
                Username = username,
                Password = password, 
                NamaUser = rekamMedis.Nama,
                Email = rekamMedis.Email,
                NomorHP = rekamMedis.NoTelp,
                Id_RekamMedis = rekamMedis.Id_RekamMedis
            };

            return AddUserPasien(userPasien);
        }

        public void DeleteUserPasien(string username)
        {
                        var userPasien = GetUserPasienByUsername(username);
            if (userPasien == null)
            {
                throw new Exception($"UserPasien dengan username {username} tidak ditemukan");
            }

            try
            {
                _context.UserPasiens.Remove(userPasien);
                _context.SaveChanges();
            }
            catch (Exception ex)
            {
                throw new Exception("Tidak bisa menghapus UserPasien", ex);
            }
        }

        public UserPasien FindUserByEmail(string email)
        {
                return _context.UserPasiens.FirstOrDefault(u => 
                u.Email == email);
        }

        public IEnumerable<UserPasien> GetAllUserPasien()
        {
                return _context.UserPasiens
                .Include(u => u.RekamMedis)
                .OrderBy(u => u.Username)
                .ToList();
        }

        public UserPasien GetUserPasienByEmail(string email)
        {
            return _context.UserPasiens.FirstOrDefault(u => u.Email == email);
        }

        public UserPasien GetUserPasienByIdRekamMedis(string idRekamMedis)
        {
                return _context.UserPasiens
                .Include(u => u.RekamMedis)
                .FirstOrDefault(u => u.Id_RekamMedis == idRekamMedis);
        }

        public UserPasien GetUserPasienByUsername(string username)
        {
                return _context.UserPasiens
                .Include(u => u.RekamMedis)
                .FirstOrDefault(u => u.Username == username);
        }

        public bool Login(string username, string password)
        {
                        if (string.IsNullOrWhiteSpace(username) || string.IsNullOrWhiteSpace(password))
            {
                throw new ArgumentException("Username and password cannot be empty");
            }

            var userPasien = _context.UserPasiens.FirstOrDefault(u => u.Username == username);
            if (userPasien == null)
            {
                return false;
            }

            return HashHelpers.VerifyPassword(password, userPasien.Password);
        }

        public UserPasien UpdateUserPasien(UserPasien userPasien)
        {
            var existingUser = GetUserPasienByUsername(userPasien.Username);
            if (existingUser == null)
            {
                throw new Exception($"UserPasien dengan username {userPasien.Username} tidak ditemukan");
            }

            try
            {
    
                existingUser.NamaUser = userPasien.NamaUser;
                existingUser.Email = userPasien.Email;
                existingUser.NomorHP = userPasien.NomorHP;

                if (!string.IsNullOrEmpty(userPasien.Password) && userPasien.Password != existingUser.Password)
                {
                    existingUser.Password = HashHelpers.HashPassword(userPasien.Password);
                }

                _context.UserPasiens.Update(existingUser);
                _context.SaveChanges();
                return existingUser;
            }
            catch (Exception ex)
            {
                throw new Exception("Tidak bisa update UserPasien", ex);
            }
        }
    }
}