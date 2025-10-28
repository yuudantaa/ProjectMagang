using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using ApiProjectMagangDotnet.DTO;
using ApiProjectMagangDotnet.Models;
using AutoMapper;

namespace ApiProjectMagangDotnet.AppDbProfile
{
    public class IniProfile : Profile
    {
        public IniProfile()
        {
            //Rekam Medis
            CreateMap<RekamMedis, RekamMedisDTO>();
            CreateMap<RekamMedisDTO, RekamMedis>();

            CreateMap<RekamMedis, RekamMedisAddDTO>();
            CreateMap<RekamMedisAddDTO, RekamMedis>();


            //Kunjungan
            CreateMap<Kunjungan, KunjunganDTO>()
                .ForMember(dest => dest.Klinik, opt => opt.MapFrom(src => src.Klinik))
                .ForMember(dest => dest.RekamMedis, opt => opt.MapFrom(src => src.RekamMedis))
               .ForMember(dest => dest.Dokter, opt => opt.MapFrom(src => src.Dokter));

            CreateMap<Kunjungan, KunjunganAddDTO>();
            CreateMap<KunjunganAddDTO, Kunjungan>();

            CreateMap<KunjunganUpdateDTO, Kunjungan>();
            CreateMap<Kunjungan, KunjunganUpdateDTO>();

            //Dokter
            CreateMap<Dokter, DokterDTO>();
            CreateMap<DokterDTO, Dokter>();

            CreateMap<Dokter, DokterAddDTO>();
            CreateMap<DokterAddDTO, Dokter>();

            //Klink
            CreateMap<Klinik, KlinikDTO>();
            CreateMap<KlinikDTO, Klinik>();

            CreateMap<Klinik, KlinikAddDTO>();
            CreateMap<KlinikAddDTO, Klinik>();

            // User Mapping
            CreateMap<AspUser, AspUserDTO>();
            CreateMap<AspUserDTO, AspUser>();

            CreateMap<AspUser, AspUserAddDTO>();
            CreateMap<AspUserAddDTO, AspUser>();

            //userPasien mapping
            CreateMap<UserPasien, UserPasienDTO>()
               .ForMember(dest => dest.RekamMedis, opt => opt.MapFrom(src => src.RekamMedis));

            CreateMap<UserPasien, UserPasienAddDTO>();
            CreateMap<UserPasienAddDTO, UserPasien>();
            
        }
    }
}