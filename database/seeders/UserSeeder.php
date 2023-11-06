<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sex = ["Men","Women"];
        $user = new User();
        $user->email = "wongkum55@gmail.com";
        $user->fullname = "JOJO jonathan";
        $user->phoneNumber = "0618204866";
        $user->birthdate = fake()->dateTimeBetween('-100000 days','-90000 days');;
        $user->gender="men";
        $user->password = "password";
        $user->save();

        for ($i=0; $i < 20 ; $i++) { 
            $user = new User();
            $user->email = fake()->unique()->safeEmail();
            $user->fullname = "fake()->name()";
            $user->phoneNumber = '0' . random_int(200000000, 999999999);
            $user->birthdate = fake()->dateTimeBetween('-100000 days','-90000 days');;
            $user->gender= $sex[array_rand($sex)];
            $user->password = "password";
            $user->save();
        }
    }
}
