using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace ApiProjectMagangDotnet.Data
{
    public interface IIdKlinikGeneratorService
    {
         Task<string> GenerateKlinikIdAsync();
    }
}