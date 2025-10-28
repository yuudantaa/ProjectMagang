using System;
using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace ApiProjectMagangDotnet.Migrations
{
    /// <inheritdoc />
    public partial class migrationBaru : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.CreateTable(
                name: "AspUsers",
                columns: table => new
                {
                    Username = table.Column<string>(type: "nvarchar(450)", nullable: false),
                    Password = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    NamaUser = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Email = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    NomorHP = table.Column<string>(type: "nvarchar(max)", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_AspUsers", x => x.Username);
                });

            migrationBuilder.CreateTable(
                name: "Dokters",
                columns: table => new
                {
                    Id_Dokter = table.Column<string>(type: "nvarchar(450)", nullable: false),
                    Nama = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Spesialisasi = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    NoHP = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Email = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    HariPraktek = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    JamMulai = table.Column<TimeOnly>(type: "time", nullable: false),
                    JamSelesai = table.Column<TimeOnly>(type: "time", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_Dokters", x => x.Id_Dokter);
                });

            migrationBuilder.CreateTable(
                name: "Kliniks",
                columns: table => new
                {
                    Id_Klinik = table.Column<string>(type: "nvarchar(450)", nullable: false),
                    Nama = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Jenis = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Gedung = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Lantai = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Kapasitas = table.Column<int>(type: "int", nullable: false),
                    Keterangan = table.Column<string>(type: "nvarchar(max)", nullable: true)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_Kliniks", x => x.Id_Klinik);
                });

            migrationBuilder.CreateTable(
                name: "PasswordResets",
                columns: table => new
                {
                    Id = table.Column<int>(type: "int", nullable: false)
                        .Annotation("SqlServer:Identity", "1, 1"),
                    Username = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Token = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    ExpiryDate = table.Column<DateTime>(type: "datetime2", nullable: false),
                    IsUsed = table.Column<bool>(type: "bit", nullable: false),
                    CreatedAt = table.Column<DateTime>(type: "datetime2", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_PasswordResets", x => x.Id);
                });

            migrationBuilder.CreateTable(
                name: "RekamMediss",
                columns: table => new
                {
                    Id_RekamMedis = table.Column<string>(type: "nvarchar(450)", nullable: false),
                    NoKTP = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Nama = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    TempatLahir = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    TanggalLahir = table.Column<DateOnly>(type: "date", nullable: false),
                    Gender = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Umur = table.Column<int>(type: "int", nullable: false),
                    Bulan = table.Column<int>(type: "int", nullable: false),
                    Agama = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    StatusKawin = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Pendidikan = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Pekerjaan = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Bahasa = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    ButuhPenerjemah = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    NoTelp = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Email = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Alamat = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Kecamatan = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Kabupaten = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Provinsi = table.Column<string>(type: "nvarchar(max)", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_RekamMediss", x => x.Id_RekamMedis);
                });

            migrationBuilder.CreateTable(
                name: "Kunjungan",
                columns: table => new
                {
                    Id_Kunjungan = table.Column<string>(type: "nvarchar(450)", nullable: false),
                    NoAntrian = table.Column<int>(type: "int", nullable: false),
                    Tanggal = table.Column<DateOnly>(type: "date", nullable: false),
                    JenisKunjungan = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Id_RekamMedis = table.Column<string>(type: "nvarchar(450)", nullable: false),
                    Id_Dokter = table.Column<string>(type: "nvarchar(450)", nullable: false),
                    Id_Klinik = table.Column<string>(type: "nvarchar(450)", nullable: false),
                    Keluhan = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Diagnosis = table.Column<string>(type: "nvarchar(max)", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_Kunjungan", x => x.Id_Kunjungan);
                    table.ForeignKey(
                        name: "FK_Kunjungan_Dokters_Id_Dokter",
                        column: x => x.Id_Dokter,
                        principalTable: "Dokters",
                        principalColumn: "Id_Dokter",
                        onDelete: ReferentialAction.Cascade);
                    table.ForeignKey(
                        name: "FK_Kunjungan_Kliniks_Id_Klinik",
                        column: x => x.Id_Klinik,
                        principalTable: "Kliniks",
                        principalColumn: "Id_Klinik",
                        onDelete: ReferentialAction.Cascade);
                    table.ForeignKey(
                        name: "FK_Kunjungan_RekamMediss_Id_RekamMedis",
                        column: x => x.Id_RekamMedis,
                        principalTable: "RekamMediss",
                        principalColumn: "Id_RekamMedis",
                        onDelete: ReferentialAction.Cascade);
                });

            migrationBuilder.CreateTable(
                name: "UserPasiens",
                columns: table => new
                {
                    Username = table.Column<string>(type: "nvarchar(450)", nullable: false),
                    Password = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    NamaUser = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Email = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    NomorHP = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Id_RekamMedis = table.Column<string>(type: "nvarchar(450)", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_UserPasiens", x => x.Username);
                    table.ForeignKey(
                        name: "FK_UserPasiens_RekamMediss_Id_RekamMedis",
                        column: x => x.Id_RekamMedis,
                        principalTable: "RekamMediss",
                        principalColumn: "Id_RekamMedis",
                        onDelete: ReferentialAction.Cascade);
                });

            migrationBuilder.CreateIndex(
                name: "IX_Kunjungan_Id_Dokter",
                table: "Kunjungan",
                column: "Id_Dokter");

            migrationBuilder.CreateIndex(
                name: "IX_Kunjungan_Id_Klinik",
                table: "Kunjungan",
                column: "Id_Klinik");

            migrationBuilder.CreateIndex(
                name: "IX_Unique_Kunjungan",
                table: "Kunjungan",
                columns: new[] { "Id_RekamMedis", "Id_Dokter", "Id_Klinik", "Tanggal" },
                unique: true);

            migrationBuilder.CreateIndex(
                name: "IX_UserPasiens_Id_RekamMedis",
                table: "UserPasiens",
                column: "Id_RekamMedis");
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropTable(
                name: "AspUsers");

            migrationBuilder.DropTable(
                name: "Kunjungan");

            migrationBuilder.DropTable(
                name: "PasswordResets");

            migrationBuilder.DropTable(
                name: "UserPasiens");

            migrationBuilder.DropTable(
                name: "Dokters");

            migrationBuilder.DropTable(
                name: "Kliniks");

            migrationBuilder.DropTable(
                name: "RekamMediss");
        }
    }
}
