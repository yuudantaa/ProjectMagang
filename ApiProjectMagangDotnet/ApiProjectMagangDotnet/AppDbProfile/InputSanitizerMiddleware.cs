using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace ApiProjectMagangDotnet.AppDbProfile
{
    public class InputSanitizerMiddleware
    {
            private readonly RequestDelegate _next;

    public InputSanitizerMiddleware(RequestDelegate next)
    {
        _next = next;
    }

    public async Task InvokeAsync(HttpContext context)
    {
        if (context.Request.HasFormContentType)
        {
            var form = await context.Request.ReadFormAsync();
            var sanitizedForm = new Dictionary<string, Microsoft.Extensions.Primitives.StringValues>();
            
            foreach (var field in form)
            {
                var sanitizedValue = SanitizeInput(field.Value.ToString());
                sanitizedForm[field.Key] = new Microsoft.Extensions.Primitives.StringValues(sanitizedValue);
            }
            
            context.Request.Form = new FormCollection(sanitizedForm);
        }
        
        await _next(context);
    }

    private string SanitizeInput(string input)
    {
        // Remove potentially dangerous characters
        return System.Web.HttpUtility.HtmlEncode(input)
            .Replace("'", "''")
            .Replace("--", "")
            .Replace(";", "")
            .Replace("/*", "")
            .Replace("*/", "");
    }
    }
}