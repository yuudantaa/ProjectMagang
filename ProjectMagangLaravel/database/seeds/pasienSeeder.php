<?php
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class pasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker= Faker::create();
        for($i=0;$i<18;$i++){
            DB::table('obat')->insert([
                'Tanggal' => $faker->date(),
                'NoKTP' => $faker->word(),
                'nama' =>$faker->word(),
                'TempatLahir' => $faker->word(),
                'TanggalLahir' =>$faker->date(),
                'Agama' => $faker->word(),
                'Bahasa' =>$faker->word(),
                'Email' =>$faker->word(),
                'Alamat' =>$faker->word(),
                'Kecamatan' =>$faker->word(),
                'Kabupaten' =>$faker->word(),
                'Provinsi' =>$faker->word()
            ]);
    }
    }
}
