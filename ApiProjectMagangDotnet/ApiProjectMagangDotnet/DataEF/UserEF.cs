using System;
using System.Collections.Generic;
using System.Linq;
using System.Security.Cryptography;
using System.Threading.Tasks;
using ApiProjectMagangDotnet.AppDbProfile;
using ApiProjectMagangDotnet.Data;
using ApiProjectMagangDotnet.DTO;
using ApiProjectMagangDotnet.Models;
using Microsoft.EntityFrameworkCore;

namespace ApiProjectMagangDotnet.DataEF
{

    public class UserEF : IAspUser
    {
        private readonly ApplicationDbContext _context;
  
        public UserEF(ApplicationDbContext context)
        {
            _context = context;

        }


        public AspUser AddUser(AspUser user)
        {
            try
            {
                // Cek apakah Username sudah ada
                var existingUsername = _context.AspUsers
                    .FirstOrDefault(r => r.Username == user.Username);
                
                if (existingUsername != null)
                {
                    throw new Exception($"Username {user.Username} sudah ada");
                }

                // Cek apakah Email sudah ada
                var existingEmail = _context.AspUsers
                    .FirstOrDefault(r => r.Email == user.Email);
                
                if (existingEmail != null)
                {
                    throw new Exception($"Email {user.Email} sudah terdaftar");
                }

                // Hash password sebelum disimpan
                user.Password = HashHelpers.HashPassword(user.Password);

                _context.AspUsers.Add(user);
                _context.SaveChanges();
                return user;
            }
            catch(Exception ex)
            {
                throw new Exception("Tidak bisa menambah User: " + ex.Message, ex);
            }
        }

        public async Task<PasswordReset> CreatePasswordResetToken(string username)
        {
                       var oldTokens = _context.PasswordResets
                .Where(t => t.Username == username && !t.IsUsed && t.ExpiryDate > DateTime.UtcNow);
            _context.PasswordResets.RemoveRange(oldTokens);
            await _context.SaveChangesAsync();

            // Buat token baru
            var token = GenerateResetToken();
            var resetRequest = new PasswordReset
            {
                Username = username,
                Token = token,
                ExpiryDate = DateTime.UtcNow.AddHours(24), // Token berlaku 24 jam
                IsUsed = false
            };

            _context.PasswordResets.Add(resetRequest);
            await _context.SaveChangesAsync();

            return resetRequest;
        }

        public void DeleteUser(string username)
        {
            var user = GetAspUserByUsername(username);
            if (user == null)
            {
                throw new Exception($"User dengan username {username} tidak ditemukan");
            }

            try
            {
                _context.AspUsers.Remove(user);
                _context.SaveChanges();
            }

            catch (Exception ex)
            {
                throw new Exception("tidak ada",ex);
            }
        }

        public AspUser FindUserByEmailOrUsername(string emailOrUsername)
        {
                return _context.AspUsers.FirstOrDefault(u => 
                u.Email == emailOrUsername || u.Username == emailOrUsername);
        }

        public IEnumerable<AspUser> GetAllUser()
        {
            var user = _context.AspUsers.OrderByDescending(c=>c.Username);
            return user;
        }

        public AspUser GetAspUserByEmail(string email)
        {
            return _context.AspUsers.FirstOrDefault(u => u.Email == email);
        }

        public AspUser GetAspUserByUsername(string username)
        {
            var user = _context.AspUsers.FirstOrDefault(c => c.Username == username);
            if (user == null)
            {
                return null;
            }
            return user;
        }

        public PasswordReset GetValidPasswordResetToken(string token)
        {
                return _context.PasswordResets
                .FirstOrDefault(t => t.Token == token && 
                                   !t.IsUsed && 
                                   t.ExpiryDate > DateTime.UtcNow);
        }

        public bool Login(string username, string password)
        {
            if (string.IsNullOrWhiteSpace(username) || string.IsNullOrWhiteSpace(password))
            {
                throw new ArgumentException("Username and password cannot be empty");
            }

            var user = _context.AspUsers.FirstOrDefault(u => u.Username == username);
            if (user == null)
            {
                return false;
            }

            return HashHelpers.VerifyPassword(password, user.Password);
        }

        public void MarkTokenAsUsed(string token)
        {
                        var resetRequest = _context.PasswordResets.FirstOrDefault(t => t.Token == token);
            if (resetRequest != null)
            {
                resetRequest.IsUsed = true;
                _context.PasswordResets.Update(resetRequest);
                _context.SaveChanges();
            }
        }
        
        private string GenerateResetToken()
        {
            using var rng = RandomNumberGenerator.Create();
            var tokenData = new byte[32];
            rng.GetBytes(tokenData);
            return Convert.ToBase64String(tokenData)
                .Replace("+", "-")
                .Replace("/", "_")
                .Replace("=", "");
        }
        
        public bool ResetPassword(string token, string newPassword)
        {
            var resetRequest = GetValidPasswordResetToken(token);
            if (resetRequest == null)
                return false;

            var user = GetAspUserByUsername(resetRequest.Username);
            if (user == null)
                return false;

            // Update password
            user.Password = HashHelpers.HashPassword(newPassword);
            _context.AspUsers.Update(user);

            // Tandai token sebagai digunakan
            resetRequest.IsUsed = true;
            _context.PasswordResets.Update(resetRequest);

            _context.SaveChanges();
            return true;
        }

        public AspUser UpdateUser(AspUser user)
        {
            var existingUser = GetAspUserByUsername(user.Username);
            if (existingUser == null)
            {
                throw new Exception($"User dengan username {user.Username} tidak ditemukan");
            }

            try
            {
                existingUser.Username = user.Username;
                existingUser.Password = user.Password;
                existingUser.Email = user.Email;
                existingUser.NomorHP = user.NomorHP;
                existingUser.Password=AppDbProfile.HashHelpers.HashPassword(user.Password);
                _context.AspUsers.Update(existingUser);
                _context.SaveChanges();
                return existingUser;
            }

            catch(Exception ex)
            {
                throw new Exception("Could not update user",ex);
            }
        }
    }
}