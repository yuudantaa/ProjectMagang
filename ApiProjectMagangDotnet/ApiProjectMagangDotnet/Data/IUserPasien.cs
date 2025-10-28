using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using ApiProjectMagangDotnet.Models;

namespace ApiProjectMagangDotnet.Data
{
    public interface IUserPasien
    {
        IEnumerable<UserPasien> GetAllUserPasien();
        UserPasien GetUserPasienByUsername(string username);
        UserPasien GetUserPasienByIdRekamMedis(string idRekamMedis);
        UserPasien AddUserPasien(UserPasien userPasien);
        UserPasien UpdateUserPasien(UserPasien userPasien);
        void DeleteUserPasien(string username);
        bool Login(string username, string password);
        UserPasien CreateUserPasienFromRekamMedis(RekamMedis rekamMedis);

        UserPasien GetUserPasienByEmail(string email);
        UserPasien FindUserByEmail(string email);
    }
}