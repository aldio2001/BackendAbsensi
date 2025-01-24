<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Aldio Yohanes',
            'email' => 'aldioguire@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        // data dummy for company
        \App\Models\Company::create([
            'name' => 'Dinas Pendidikan Pekanbaru',
            'email' => 'aldioguire@gmail.com',
            'address' => 'Jl. H. Samsul Bahri No. 8 Kelurahan Sungai Sibam Kecamatan Payung Sekaki Kode Pos : 28293.',
            'latitude' => '0.5134915',
            'longitude' => '101.4146',
            'radius_km' => '0.5',
            'time_in' => '08:00',
            'time_out' => '17:00',
        ]);

        $this->call([
            AttendanceSeeder::class,
            PermissionSeeder::class,
        ]);
    }
}
