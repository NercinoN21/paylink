<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class ContaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $users = DB::table('users')->pluck('id')->toArray();
        $agencias = DB::table('agencias')->pluck('id')->toArray();

        foreach (range(1, 10) as $index) {
            DB::table('contas')->insert([
                'user_id' => $faker->randomElement($users),
                'agencia_id' => $faker->randomElement($agencias),
                'numero' => $faker->unique()->numerify('##########'),
                'saldo' => $faker->randomFloat(2, 0, 10000),
            ]);
        }
    }
}
