<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class RwSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menggunakan Faker untuk mengisi data
        $faker = Faker::create();

        // Ambil semua nik yang ada
        $nikRw = DB::table('penduduk')->pluck('nik')->toArray();

        // Loop untuk mengisi data sebanyak yang diinginkan
        foreach (range(1, 1) as $index) {
            // Insert data baru ke tabel rw
            DB::table('rw')->insert([
                'no_rw' => 6,
                'nik_rw' => $faker->randomElement($nikRw),
                'jumlah_rt' => 10,
                'jumlah_keluarga_rw' => $faker->numberBetween(50, 100),
                'jumlah_penduduk_rw' => $faker->numberBetween(200, 300),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}