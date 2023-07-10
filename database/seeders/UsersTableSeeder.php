<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Seeders\Factory;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //factory(\App\Users::class, 2)->create();

        $faker = Faker::create();

        $enderecos = DB::table('enderecos')->pluck('id')->toArray();

        foreach (range(1, 10) as $index) {
            DB::table('users')->insert([
                'nome' => $faker->name,
                'data_nascimento' => $faker->date,
                'cpf' => $faker->unique()->numerify('###########'),
                'email' => $faker->email,
                'senha' => Hash::make('password'),
                'endereco_cobranca_id' => $faker->randomElement($enderecos),
            ]);
        }
    }
}
