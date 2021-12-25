<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<=100;$i++){
            User::create([
                'first_name'=>"Lara".$i,
                'last_name'=>"jalloul".$i,
                'email'=>"Lara.jalloul".$i."@gmail.com",
                'password'=>bcrypt('password'),
            ]);
        }
    }
}
