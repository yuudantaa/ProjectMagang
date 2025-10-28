using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using ApiProjectMagangDotnet.DTO;
using ApiProjectMagangDotnet.Models;

namespace ApiProjectMagangDotnet.Data
{
    public interface IAspUser
    {
        IEnumerable<AspUser> GetAllUser();
        AspUser AddUser(AspUser user);
        AspUser GetAspUserByUsername(string username);
        AspUser UpdateUser(AspUser user);
        void DeleteUser(string username);
        bool Login(string username, string password);
        
        // Method baru untuk fitur lupa password
        AspUser GetAspUserByEmail(string email);
        Task<PasswordReset> CreatePasswordResetToken(string username);
        PasswordReset GetValidPasswordResetToken(string token);
        bool ResetPassword(string token, string newPassword);
        void MarkTokenAsUsed(string token);
        AspUser FindUserByEmailOrUsername(string emailOrUsername);
    }
}