using System;
using System.Collections.Generic;
using System.Globalization;
using System.Linq;
using System.Text.Json;
using System.Text.Json.Serialization;
using System.Threading.Tasks;

namespace ApiProjectMagangDotnet.AppDbProfile
{
    public class DateOnlyJsonConverter : JsonConverter<DateOnly>
    {
      public override DateOnly Read(ref Utf8JsonReader reader, Type typeToConvert, JsonSerializerOptions options)
        {
            var value = reader.GetString();
            if (string.IsNullOrEmpty(value))
                return default;

            // coba parse yyyy-MM-dd
            if (DateOnly.TryParseExact(value, "yyyy-MM-dd", CultureInfo.InvariantCulture, DateTimeStyles.None, out var date))
                return date;

            // coba parse dd/MM/yyyy
            if (DateOnly.TryParseExact(value, "dd/MM/yyyy", CultureInfo.InvariantCulture, DateTimeStyles.None, out date))
                return date;

            throw new JsonException($"Format tanggal tidak valid: {value}");
        }

        public override void Write(Utf8JsonWriter writer, DateOnly value, JsonSerializerOptions options)
        {
            // selalu kirim ke frontend format yyyy-MM-dd
            writer.WriteStringValue(value.ToString("yyyy-MM-dd"));
        }
    
    }
}