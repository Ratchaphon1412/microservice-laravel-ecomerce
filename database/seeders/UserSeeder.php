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
        $user = new User();
        $user->email = "wongkum55@gmail.com";
        $user->fullname = "JOJO jonathan";
        $user->phoneNumber = "0618204866";
        $user->birthdate = fake()->dateTimeBetween('-100000 days','-90000 days');;
        $user->gender="men";
        $user->password = "password";
        $user->save();
    
        $user = new User();
        $user->email = "tae@gmail.com";
        $user->fullname = "toto zaza";
        $user->phoneNumber = "0618204866";
        $user->birthdate = fake()->dateTimeBetween('-1000000 days','-900000 days');;
        $user->gender="men";
        $user->password = "password";
        $user->save();
    }
}
