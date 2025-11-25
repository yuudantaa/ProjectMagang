using ApiProjectMagangDotnet.AppDbProfile;
using ApiProjectMagangDotnet.Data;
using ApiProjectMagangDotnet.DataEF;
using ApiProjectMagangDotnet.DTO;
using ApiProjectMagangDotnet.Models;
using AutoMapper;
using Microsoft.AspNetCore.Diagnostics;
using Microsoft.AspNetCore.Http.Json;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using System.ComponentModel.DataAnnotations;

var builder = WebApplication.CreateBuilder(args);

builder.Services.AddCors(options =>
{
    options.AddPolicy("AllowFrontend",
        policy => policy.WithOrigins("http://127.0.0.1:8000","http://127.0.0.1:8001")
                        .AllowAnyHeader()
                        .AllowAnyMethod()
                        .AllowCredentials());
});

builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen();

builder.Services.AddDbContext<ApplicationDbContext>(options =>
    options.UseSqlServer(builder.Configuration.GetConnectionString("DefaultConnection")));

builder.Services.AddAutoMapper(typeof(IniProfile));
builder.Services.AddScoped<IIdPasienGeneratorService, IdPasienGeneratorService>();
builder.Services.AddScoped<IAntrianService, AntrianService>();
builder.Services.AddScoped<IIdGeneratorService, IdGeneratorService>();
builder.Services.AddScoped<IDokter,DokterEF>();
builder.Services.AddScoped<IKlinik, KlinikEF>();
builder.Services.AddScoped<IRekamMedis, RekamMedisEF>();
builder.Services.AddScoped<IKunjungan, KunjunganEF>();
builder.Services.AddScoped<IAspUser, UserEF>();
builder.Services.AddScoped<IUserPasien, UserPasienEF>();
builder.Services.AddScoped<interfaceIdDokter, IdDokterGeneratorService>();
builder.Services.AddScoped<InterfaceIdKlinik, IdKlinikGeneratorService>();
builder.Services.AddScoped<IRekamMedis>(provider => 
    new RekamMedisEF(
        provider.GetRequiredService<ApplicationDbContext>(),
        provider.GetRequiredService<IUserPasien>()
    )
);
// Add services to the container.
// Learn more about configuring OpenAPI at https://aka.ms/aspnet/openapi
builder.Services.AddOpenApi();

var app = builder.Build();

// Configure the HTTP request pipeline.
if (app.Environment.IsDevelopment())
{
    app.UseSwagger();
    app.UseSwaggerUI();
}
app.UseCors("AllowFrontend");

app.UseMiddleware<InputSanitizerMiddleware>();
app.UseHttpsRedirection();
//user
app.MapGet("/cekpassword/{password}", (string password) =>
{
    var pass = ApiProjectMagangDotnet.AppDbProfile.HashHelpers.HashPassword(password);
    return Results.Ok($"Password:{password} Hass:{pass}");
});

app.MapGet("/user", (IAspUser aspUserData, IMapper mapper) =>
{
    var user = aspUserData.GetAllUser();
    var userDTOs = mapper.Map<List<AspUserDTO>>(user);
    return Results.Ok(userDTOs);
});

app.MapGet("/user/{username}",(IAspUser aspUserData,IMapper mapper, string username)=>
{
    var user = aspUserData.GetAspUserByUsername(username);
    if (user == null)
    {
        return Results.NotFound();
    }
    var userDTO = mapper.Map<AspUserDTO>(user);
    return Results.Ok(userDTO);
});

app.MapPost("/user", (IAspUser aspUserData, AspUserAddDTO userAddDTO, IMapper mapper) =>
{
    var validationResults = new List<ValidationResult>();
    var validationContext = new ValidationContext(userAddDTO);
    bool isValid = Validator.TryValidateObject(userAddDTO, validationContext, validationResults, true);

    if (!isValid)
    {
        var errors = validationResults.ToDictionary(
            v => v.MemberNames.FirstOrDefault() ?? "Error",
            v => new[] { v.ErrorMessage ?? "Validation error" });

        return Results.ValidationProblem(errors);
    }

    try
    {
        // Cek apakah username sudah ada - handle null dengan benar
        var existingUser = aspUserData.GetAspUserByUsername(userAddDTO.Username);
        if (existingUser != null)
        {
            return Results.Conflict(new
            {
                success = false,
                message = $"Username {userAddDTO.Username} sudah digunakan"
            });
        }

        // Cek apakah email sudah ada - handle null dengan benar
        var existingEmail = aspUserData.GetAspUserByEmail(userAddDTO.Email);
        if (existingEmail != null)
        {
            return Results.Conflict(new
            {
                success = false,
                message = $"Email {userAddDTO.Email} sudah terdaftar"
            });
        }

        var user = mapper.Map<AspUser>(userAddDTO);

        var newUser = aspUserData.AddUser(user);
        var userDTOs = mapper.Map<AspUserDTO>(newUser);
        return Results.Created($"/user/{newUser.Username}", new
        {
            success = true,
            message = "User berhasil ditambahkan",
            data = userDTOs
        });
    }
    catch (DbUpdateException dbex)
    {
        return Results.Problem($"Database error occurred: {dbex.Message}");
    }
    catch (System.Exception ex)
    {
        return Results.Problem($"An error occurred: {ex.Message}");
    }
});

app.MapPut("/user",(IAspUser userData, AspUser user)=>{
    var putUser = userData.UpdateUser(user);
    return putUser;
});

app.MapDelete("/user/{username}", (IAspUser userData, string username) =>
{
   userData.DeleteUser(username);
    return Results.Json(new { success = true, message = "Data deleted successfully" });
});


app.MapPost("/user/login", (IAspUser aspUserData, LoginDTO loginRequest) =>
{
    try
    {
        var isAuthenticated = aspUserData.Login(loginRequest.Username, loginRequest.Password);
        if (isAuthenticated)
        {
            // Dapatkan user data untuk role
            var user = aspUserData.GetAspUserByUsername(loginRequest.Username);
            return Results.Ok(new
            {
                success = true,
                message = "Login successful",
                username = user.Username,
                nama = user.NamaUser
            });
        }
        return Results.Unauthorized();
    }
    catch (ArgumentException ex)
    {
        return Results.BadRequest(ex.Message);
    }
    catch (Exception ex)
    {
        return Results.Problem(ex.Message);
    }
});

app.MapPost("/user/forgot-password", async (IAspUser aspUserData, ForgotPasswordDTO forgotPasswordDto) =>
{
    try
    {
        var user = aspUserData.FindUserByEmailOrUsername(forgotPasswordDto.EmailOrUsername);
        if (user == null)
        {
            return Results.Ok(new { 
                success = true, 
                message = $"Akun dengan email {forgotPasswordDto.EmailOrUsername} tidak terdaftar, Silahkan buat akun baru" 
            });
        }

        var resetToken = await aspUserData.CreatePasswordResetToken(user.Username);
        
        return Results.Ok(new { 
            success = true, 
            message = "Token reset password telah dibuat",
            token = resetToken.Token, 
            expiry = resetToken.ExpiryDate
        });
    }
    catch (Exception ex)
    {
        return Results.Problem($"Terjadi kesalahan: {ex.Message}");
    }
});

app.MapPost("/user/find-username", (IAspUser aspUserData, FindUsernameDTO findUsernameDto) =>
{
    try
    {
        var user = aspUserData.GetAspUserByEmail(findUsernameDto.Email);
        if (user == null)
        {
            return Results.Ok(new
            {
                success = true,
                message = $"Akun dengan email {findUsernameDto.Email} tidak terdaftar, Silahkan buat akun baru" 
            });
        }

        return Results.Ok(new
        {
            success = true,
            message = "Username telah ditemukan",
            username = user.Username 
        });
    }
    catch (Exception ex)
    {
        return Results.Problem($"Terjadi kesalahan: {ex.Message}");
    }
});

app.MapPost("/user/reset-password", (IAspUser aspUserData, ResetPasswordDTO resetPasswordDto) =>
{
    try
    {
        var success = aspUserData.ResetPassword(resetPasswordDto.Token, resetPasswordDto.NewPassword);
        if (success)
        {
            return Results.Ok(new
            {
                success = true,
                message = "Password berhasil direset"
            });
        }
        else
        {
            return Results.BadRequest(new
            {
                success = false,
                message = "Token tidak valid atau sudah kedaluwarsa"
            });
        }
    }
    catch (Exception ex)
    {
        return Results.Problem($"Terjadi kesalahan: {ex.Message}");
    }
});

app.MapGet("/user/validate-reset-token/{token}", (IAspUser aspUserData, string token) =>
{
    try
    {
        var resetRequest = aspUserData.GetValidPasswordResetToken(token);
        return Results.Ok(new
        {
            valid = resetRequest != null,
            message = resetRequest != null ? "Token valid" : "Token tidak valid"
        });
    }
    catch (Exception ex)
    {
        return Results.Problem($"Terjadi kesalahan: {ex.Message}");
    }
});


// UserPasien
app.MapGet("/userpasien", (IUserPasien userPasienData, IMapper mapper) =>
{
    try
    {
        var userPasiens = userPasienData.GetAllUserPasien();
        var userPasienDTOs = mapper.Map<List<UserPasienDTO>>(userPasiens);
        return Results.Ok(new
        {
            success = true,
            data = userPasienDTOs
        });
    }
    catch (Exception ex)
    {
        return Results.Problem($"Terjadi kesalahan: {ex.Message}");
    }
}).WithTags("UserPasien");

app.MapGet("/userpasien/{username}", (IUserPasien userPasienData, IMapper mapper, string username) =>
{
    try
    {
        var userPasien = userPasienData.GetUserPasienByUsername(username);
        if (userPasien == null)
        {
            return Results.NotFound(new
            {
                success = false,
                message = $"UserPasien dengan username {username} tidak ditemukan"
            });
        }
        var userPasienDTO = mapper.Map<UserPasienDTO>(userPasien);
        return Results.Ok(new
        {
            success = true,
            data = userPasienDTO
        });
    }
    catch (Exception ex)
    {
        return Results.Problem($"Terjadi kesalahan: {ex.Message}");
    }
}).WithTags("UserPasien");

app.MapGet("/userpasien/by-rekam-medis/{idRekamMedis}", (IUserPasien userPasienData, IMapper mapper, string idRekamMedis) =>
{
    try
    {
        var userPasien = userPasienData.GetUserPasienByIdRekamMedis(idRekamMedis);
        if (userPasien == null)
        {
            return Results.NotFound(new
            {
                success = false,
                message = $"UserPasien dengan ID Rekam Medis {idRekamMedis} tidak ditemukan"
            });
        }
        var userPasienDTO = mapper.Map<UserPasienDTO>(userPasien);
        return Results.Ok(new
        {
            success = true,
            data = userPasienDTO
        });
    }
    catch (Exception ex)
    {
        return Results.Problem($"Terjadi kesalahan: {ex.Message}");
    }
}).WithTags("UserPasien");

app.MapPost("/userpasien", (IUserPasien userPasienData, UserPasienAddDTO userPasienAddDTO, IMapper mapper) =>
{
    var validationResults = new List<ValidationResult>();
    var validationContext = new ValidationContext(userPasienAddDTO);
    bool isValid = Validator.TryValidateObject(userPasienAddDTO, validationContext, validationResults, true);

    if (!isValid)
    {
        var errors = validationResults.ToDictionary(
            v => v.MemberNames.FirstOrDefault() ?? "Error",
            v => new[] { v.ErrorMessage ?? "Validation error" });

        return Results.ValidationProblem(errors);
    }

    try
    {
        var rekamMedisData = app.Services.GetRequiredService<IRekamMedis>();
        var rekamMedis = rekamMedisData.GetRekamMedisById(userPasienAddDTO.Id_RekamMedis);
        
        if (rekamMedis == null)
        {
            return Results.NotFound(new
            {
                success = false,
                message = $"Rekam medis dengan ID {userPasienAddDTO.Id_RekamMedis} tidak ditemukan"
            });
        }

        // Buat user pasien dari data rekam medis
        var userPasien = userPasienData.CreateUserPasienFromRekamMedis(rekamMedis);
        var userPasienDTO = mapper.Map<UserPasienDTO>(userPasien);
        
        return Results.Created($"/userpasien/{userPasien.Username}", new
        {
            success = true,
            message = "UserPasien berhasil dibuat",
            data = userPasienDTO
        });
    }
    catch (Exception ex)
    {
        return Results.Problem($"Terjadi kesalahan: {ex.Message}");
    }
}).WithTags("UserPasien");

app.MapPost("/userpasien/login", (IUserPasien UserPasienData, LoginDTO loginRequest) =>
{
    try
    {
        var isAuthenticated = UserPasienData.Login(loginRequest.Username, loginRequest.Password);
        if (isAuthenticated)
        {
            // Dapatkan user data untuk role
            var user = UserPasienData.GetUserPasienByUsername(loginRequest.Username);
            return Results.Ok(new
            {
                success = true,
                message = "Login successful",
                username = user.Username,
                nama = user.NamaUser
            });
        }
        return Results.Unauthorized();
    }
    catch (ArgumentException ex)
    {
        return Results.BadRequest(ex.Message);
    }
    catch (Exception ex)
    {
        return Results.Problem(ex.Message);
    }
}).WithTags("UserPasien");

app.MapDelete("/userpasien/{username}", (IUserPasien userPasienData, string username) =>
{
    try
    {
        userPasienData.DeleteUserPasien(username);
        return Results.Ok(new
        {
            success = true,
            message = "UserPasien berhasil dihapus"
        });
    }
    catch (Exception ex)
    {
        return Results.Problem($"Terjadi kesalahan: {ex.Message}");
    }
}).WithTags("UserPasien");

app.MapPost("/userpasien/find-username", (IUserPasien userPasienData, FindUsernameDTO findUsernameDto) =>
{
    try
    {
        var user = userPasienData.GetUserPasienByEmail(findUsernameDto.Email);
        if (user == null)
        {
            return Results.Ok(new
            {
                success = true,
                message = "Jika email terdaftar, username akan dikirim ke email Anda"
            });
        }

        return Results.Ok(new
        {
            success = true,
            message = "Username telah ditemukan",
            username = user.Username
        });
    }
    catch (Exception ex)
    {
        return Results.Problem($"Terjadi kesalahan: {ex.Message}");
    }
}).WithTags("UserPasien");


//kunjungan
app.MapGet("/Kunjungan/All", async (IKunjungan kunjunganData, IMapper mapper) =>
{
    try
    {
        var kunjungan = await kunjunganData.GetAllKunjungan();
        var kunjunganDtos = mapper.Map<List<KunjunganDTO>>(kunjungan);
        return Results.Ok(kunjunganDtos);
    }
    catch (Exception ex)
    {
        return Results.Problem($"An error occurred: {ex.Message}");
    }
}).WithTags("Kunjungan");

app.MapGet("/Kunjungan/Baru", async (IKunjungan kunjunganData, IMapper mapper) =>
{
    var kunjungan = await kunjunganData.GetKunjunganBaruAll();
    var kunjunganDtos = mapper.Map<List<KunjunganDTO>>(kunjungan);
    return Results.Ok(kunjunganDtos);
}).WithTags("Kunjungan");

app.MapGet("/Kunjungan/{id}", async (IKunjungan kunjunganData, string id, IMapper mapper) =>
{
    var kunjungan = await kunjunganData.GetKunjunganById(id);
    if (kunjungan == null)
    {
        return Results.NotFound();
    }
    var kunjunganDtos = mapper.Map<KunjunganDTO>(kunjungan);
    return Results.Ok(kunjunganDtos);
}).WithTags("Kunjungan");

app.MapPost("/Kunjungan", async (IKunjungan kunjunganData, KunjunganAddDTO kunjunganAddDTO, IMapper mapper) =>
{
    try
    {
        // Cek duplikasi sebelum membuat kunjungan baru
        var isDuplicate = await kunjunganData.IsDuplicateKunjungan(
            kunjunganAddDTO.Id_RekamMedis,
            kunjunganAddDTO.Id_Dokter,
            kunjunganAddDTO.Id_Klinik,
            kunjunganAddDTO.Tanggal

        );

        if (isDuplicate)
        {
            return Results.Conflict(new { 
                success = false, 
                message = "Kunjungan sudah ada untuk pasien, dokter, dan klinik yang sama" 
            });
        }

        var kunjungan = mapper.Map<Kunjungan>(kunjunganAddDTO);
        kunjungan.JenisKunjungan = "Baru";

        var newKunjungan = await kunjunganData.AddKunjungan(kunjungan);
        var kunjunganDTOs = mapper.Map<KunjunganDTO>(newKunjungan);
        return Results.Created($"/Kunjungan/{newKunjungan.Id_Kunjungan}", kunjunganDTOs);
    }
    catch (Exception ex)
    {
        return Results.Problem($"An error occurred: {ex.Message}");
    }
}).WithTags("Kunjungan");

app.MapPost("/Kunjungan/PasienLama", async (IKunjungan kunjunganData, KunjunganAddDTO kunjunganAddDTO, IMapper mapper) =>
{
    try
    {
        // Cek duplikasi sebelum membuat kunjungan baru
        var isDuplicate = await kunjunganData.IsDuplicateKunjungan(
            kunjunganAddDTO.Id_RekamMedis, 
            kunjunganAddDTO.Id_Dokter, 
            kunjunganAddDTO.Id_Klinik,
            kunjunganAddDTO.Tanggal
        );

        if (isDuplicate)
        {
            return Results.Conflict(new { 
                success = false, 
                message = "Kunjungan sudah ada untuk pasien, dokter, dan klinik yang sama" 
            });
        }

        var kunjungan = mapper.Map<Kunjungan>(kunjunganAddDTO);
        kunjungan.JenisKunjungan = "Lama";
        
        var newKunjungan = await kunjunganData.AddKunjunganLama(kunjungan);
        var kunjunganDTOs = mapper.Map<KunjunganDTO>(newKunjungan);
        return Results.Created($"/Kunjungan/PasienLama/{newKunjungan.Id_Kunjungan}", kunjunganDTOs);
    }
    catch (Exception ex)
    {
        return Results.Problem($"An error occurred: {ex.Message}");
    }
}).WithTags("Kunjungan");

app.MapPut("/Kunjungan", async (IKunjungan kunjunganData, Kunjungan kunjungan) =>
{
    try
    {
        var updatedKunjungan = await kunjunganData.UpdateKunjungan(kunjungan);
        return Results.Ok(updatedKunjungan);
    }
    catch (Exception ex)
    {
        return Results.Problem($"An error occurred: {ex.Message}");
    }
}).WithTags("Kunjungan");

app.MapDelete("/Kunjungan/{id}", async (IKunjungan kunjunganData, string id) =>
{
    try
    {
        await kunjunganData.DeleteKunjungan(id);
        return Results.Json(new { success = true, message = "Data deleted successfully" });
    }
    catch (Exception ex)
    {
        return Results.Problem($"An error occurred: {ex.Message}");
    }
}).WithTags("Kunjungan");

app.MapGet("/Kunjungan/Lama", async (IKunjungan kunjunganData, IMapper mapper) =>
{
    var kunjungan = await kunjunganData.GetKunjunganLamaAll();
    var kunjunganDtos = mapper.Map<List<KunjunganDTO>>(kunjungan);
    return Results.Ok(kunjunganDtos);
}).WithTags("Kunjungan");

app.MapPut("/Kunjungan/PasienLama", async (IKunjungan kunjunganData,Kunjungan kunjungan) =>
{
    try
    {
        var updatedKunjungan = await kunjunganData.UpdateKunjunganLama(kunjungan);
        return Results.Ok(updatedKunjungan);
    }
    catch (Exception ex)
    {
        return Results.Problem($"An error occurred: {ex.Message}");
    }
}).WithTags("Kunjungan");

app.MapDelete("/Kunjungan/PasienLama/{id}", async (IKunjungan kunjunganData, string id) =>
{
    try
    {
        await kunjunganData.DeleteKunjunganLama(id);
        return Results.Json(new
        {
            success = true,
            message = "Kunjungan pasien lama berhasil dihapus"
        });
    }
    catch (Exception ex)
    {
        return Results.Problem(
            detail: $"An error occurred: {ex.Message}",
            statusCode: StatusCodes.Status400BadRequest
        );
    }
}).WithTags("Kunjungan");

// Tambahkan endpoint ini sebelum endpoint POST kunjungan
app.MapGet("/kunjungan/check-duplicate", async (IKunjungan kunjunganData, 
    [FromQuery] string rekam_medis, 
    [FromQuery] string dokter, 
    [FromQuery] string klinik, 
    [FromQuery] string tanggal) =>
{
    try
    {
        var isDuplicate = await kunjunganData.IsDuplicateKunjungan(rekam_medis, dokter, klinik, DateOnly.Parse(tanggal));
        return Results.Ok(new { 
            duplicate = isDuplicate,
            message = isDuplicate ? "Sudah ada kunjungan untuk kombinasi pasien, dokter, klinik, dan tanggal ini" : "Tidak ada duplikasi"
        });
    }
    catch (Exception ex)
    {
        return Results.Problem($"Error checking duplicate: {ex.Message}");
    }
}).WithTags("Kunjungan");

app.MapGet("/kunjungan/user/{username}", async (IKunjungan kunjunganData, IUserPasien userPasienData, string username, IMapper mapper) =>
{
    try
    {
        var userPasien = userPasienData.GetUserPasienByUsername(username);
        if (userPasien == null)
        {
            return Results.NotFound(new
            {
                success = false,
                message = $"UserPasien dengan username {username} tidak ditemukan"
            });
        }

        var allKunjungan = await kunjunganData.GetAllKunjungan();
        
        var kunjunganUser = allKunjungan
            .Where(k => k.Id_RekamMedis == userPasien.Id_RekamMedis)
            .OrderByDescending(k => k.Tanggal)
            .ThenByDescending(k => k.NoAntrian)
            .ToList();

        var kunjunganDTOs = mapper.Map<List<KunjunganDTO>>(kunjunganUser);
        
        return Results.Ok(new
        {
            success = true,
            data = kunjunganDTOs,
            userInfo = new
            {
                username = userPasien.Username,
                nama = userPasien.NamaUser,
                idRekamMedis = userPasien.Id_RekamMedis
            }
        });
    }
    catch (Exception ex)
    {
        return Results.Problem($"Terjadi kesalahan: {ex.Message}");
    }
}).WithTags("Kunjungan");


//dokter
app.MapGet("/Dokter",(IDokter dokterData, IMapper mapper)=>{
    var dokter = dokterData.GetDokters();
    var dokterDtos = mapper.Map<List<DokterDTO>>(dokter);
    return Results.Ok(dokterDtos);
}).WithTags("Dokter");

app.MapGet("/Dokter/{id}",(IDokter dokterData, string id, IMapper mapper)=>
{
    var dokter = dokterData.GetDoktersById(id);
    if (dokter == null)
    {
        return Results.NotFound();
    }
    var dokterDtos = mapper.Map<DokterDTO>(dokter);
    return Results.Ok(dokterDtos);
}).WithTags("Dokter");

app.MapPost("/Dokter", async (IDokter dokterData, DokterAddDTO dokterAddDTO, 
    IMapper mapper, interfaceIdDokter idGenerator) =>
{
    var validationResults = new List<ValidationResult>();
    var validationContext = new ValidationContext(dokterAddDTO);
    bool isValid = Validator.TryValidateObject(dokterAddDTO, validationContext, validationResults, true);

    if (!isValid)
    {
        var errors = validationResults.ToDictionary(
            v => v.MemberNames.FirstOrDefault() ?? "Error",
            v => new[] { v.ErrorMessage ?? "Validation error" });

        return Results.ValidationProblem(errors);
    }

    // Generate ID otomatis
    var generatedId = await idGenerator.GenerateDokterIdAsync();

    var dokter = mapper.Map<Dokter>(dokterAddDTO);
    dokter.Id_Dokter = generatedId; // Set ID yang digenerate

    try
    {
        var newDokter = dokterData.AddDokter(dokter);
        var dokterDTOs = mapper.Map<DokterDTO>(newDokter);
        return Results.Created($"/dokter/{newDokter.Id_Dokter}", dokterDTOs);
    }
    catch (DbUpdateException dbex)
    {
        return Results.Problem("Database error occurred: " + dbex.Message);
    }
    catch (System.Exception ex)
    {
        return Results.Problem("An error occurred: " + ex.Message);
    }
}).WithTags("Dokter");

app.MapPut("/Dokter",(IDokter dokterData, Dokter dokter)=>{
    var putdokter = dokterData.UpdateDokter(dokter);
    return putdokter;
}).WithTags("Dokter");

app.MapDelete("/Dokter/{id}", (IDokter dokterData, string id) =>
{
   dokterData.DeleteDokter(id);
    return Results.Json(new { success = true, message = "Data deleted successfully" });
}).WithTags("Dokter");

//klinik
app.MapGet("/Klinik",(IKlinik klinikData, IMapper mapper)=>{
    var klink = klinikData.GetKlinik();
    var klinikDtos = mapper.Map<List<Klinik>>(klink);
    return Results.Ok(klinikDtos);
}).WithTags("Klinik");

app.MapGet("/Klinik/{id}",(IKlinik klinikData, string id, IMapper mapper)=>
{
    var klinik = klinikData.GetKlinikById(id);
    if (klinik == null)
    {
        return Results.NotFound();
    }
    var klinikDtos = mapper.Map<KlinikDTO>(klinik);
    return Results.Ok(klinikDtos);
}).WithTags("Klinik");

app.MapPost("/Klinik", async (IKlinik klinikData, KlinikAddDTO klinikAddDTO, 
    IMapper mapper, InterfaceIdKlinik idGenerator) =>
{
    var validationResults = new List<ValidationResult>();
    var validationContext = new ValidationContext(klinikAddDTO);
    bool isValid = Validator.TryValidateObject(klinikAddDTO, validationContext, validationResults, true);

    if (!isValid)
    {
        var errors = validationResults.ToDictionary(
            v => v.MemberNames.FirstOrDefault() ?? "Error",
            v => new[] { v.ErrorMessage ?? "Validation error" });

        return Results.ValidationProblem(errors);
    }

    // Generate ID otomatis
    var generatedId = await idGenerator.GenerateKlinikIdAsync();

    var klinik = mapper.Map<Klinik>(klinikAddDTO);
    klinik.Id_Klinik = generatedId; // Set ID yang digenerate

    try
    {
        var newKlinik = klinikData.AddKlinik(klinik);
        var klinikDTOs = mapper.Map<KlinikDTO>(newKlinik);
        return Results.Created($"/Klinik/{newKlinik.Id_Klinik}", klinikDTOs);
    }
    catch (DbUpdateException dbex)
    {
        return Results.Problem("Database error occurred: " + dbex.Message);
    }
    catch (System.Exception ex)
    {
        return Results.Problem("An error occurred: " + ex.Message);
    }
}).WithTags("Klinik");

app.MapPut("/Klinik",(IKlinik klinikData, Klinik klinik)=>{
    var putklinik = klinikData.UpdateKlinik(klinik);
    return putklinik;
}).WithTags("Klinik");

app.MapDelete("/Klinik/{id}", (IKlinik klinikData, string id) =>
{
   klinikData.DeleteKlinik(id);
    return Results.Json(new { success = true, message = "Data deleted successfully" });
}).WithTags("Klinik");

//RekamMedis
app.MapGet("/Pasien",(IRekamMedis rekamData, IMapper mapper)=>{
    var rekam = rekamData.GetRekamMedis();
    var rekamDtos = mapper.Map<List<RekamMedis>>(rekam);
    return Results.Ok(rekamDtos);
}).WithTags("Pasien");

app.MapGet("/Pasien/{id}",(IRekamMedis rekamData, string id, IMapper mapper)=>
{
    var rekam = rekamData.GetRekamMedisById(id);
    if (rekam == null)
    {
        return Results.NotFound();
    }
    var rekamDtos = mapper.Map<RekamMedisDTO>(rekam);
    return Results.Ok(rekamDtos);
}).WithTags("Pasien");

app.MapPost("/Pasien", async (IRekamMedis rekamMedisData, RekamMedisAddDTO rekamMedisAddDTO, 
    IMapper mapper, IIdPasienGeneratorService idPasienGenerator) =>
{
    var validationResults = new List<ValidationResult>();
    var validationContext = new ValidationContext(rekamMedisAddDTO);
    bool isValid = Validator.TryValidateObject(rekamMedisAddDTO, validationContext, validationResults, true);

    if (!isValid)
    {
        var errors = validationResults.ToDictionary(
            v => v.MemberNames.FirstOrDefault() ?? "Error",
            v => new[] { v.ErrorMessage ?? "Validation error" });

        return Results.ValidationProblem(errors);
    }

    // Generate ID otomatis
    var generatedId = await idPasienGenerator.GenerateRekamMedisIdAsync();
    
    // Hitung umur dan bulan otomatis berdasarkan tanggal lahir
    var (umur, bulan) = idPasienGenerator.CalculateAge(rekamMedisAddDTO.TanggalLahir);

    var rekam = mapper.Map<RekamMedis>(rekamMedisAddDTO);
    rekam.Id_RekamMedis = generatedId;
    rekam.Umur = umur; // Set umur yang dihitung otomatis
    rekam.Bulan = bulan; // Set bulan yang dihitung otomatis

    try
    {
        var newrekam = rekamMedisData.AddRekamMedis(rekam);
        var rekamDTOs = mapper.Map<RekamMedisDTO>(newrekam);
        return Results.Created($"/Pasien/{newrekam.Id_RekamMedis}", rekamDTOs);
    }
    catch (DbUpdateException dbex)
    {
        return Results.Problem("Database error occurred: " + dbex.Message);
    }
    catch (System.Exception ex)
    {
        return Results.Problem("An error occurred: " + ex.Message);
    }
}).WithTags("Pasien");


app.MapPut("/Pasien", (IRekamMedis rekamData, RekamMedis rekamMedis,
    IIdPasienGeneratorService idPasienGenerator) =>
{
    // Hitung ulang umur dan bulan ketika data diupdate
    var (umur, bulan) = idPasienGenerator.CalculateAge(rekamMedis.TanggalLahir);
    rekamMedis.Umur = umur;
    rekamMedis.Bulan = bulan;

    var putrekam = rekamData.UpdateRekamMedis(rekamMedis);
    return putrekam;
}).WithTags("Pasien");


app.MapDelete("/Pasien/{id}", (IRekamMedis rekamData, string id) =>
{
   rekamData.DeleteRekamMedis(id);
    return Results.Json(new { success = true, message = "Data deleted successfully" });

}).WithTags("Pasien");
app.Run();


