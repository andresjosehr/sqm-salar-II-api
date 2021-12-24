<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        
        DB::table('users')->insert([
            [   
                "name"     => "José Andrés Hernández",
                "email"    => "joseandreshernandezross@gmail.com",
                "password" =>  Hash::make("Paralelepipe2")
            ],
            [   
                "name"     => "Hernan Canales",
                "email"    => "hernan.canales@proyex.cl",
                "password" =>  Hash::make("hcanales2021")
            ],
            [   
                "name"     => "SQM",
                "email"    => "sqm@proyex.cl",
                "password" =>  Hash::make("sqm2021")
            ],

            [   
                "name"     => "Ramon Queizal",
                "email"    => "ramon.queizal@sqm.com",
                "password" =>  Hash::make("ramonqueizal")
            ]
        ]);
    }
}
