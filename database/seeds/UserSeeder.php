<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            "username" => "johnatanlopes",
            "name" => "Johnatan Lopes",
            "email" => "johnatanlopes@gmail.com",
            "password" => Hash::make("123456"),
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s")
        ]);

        DB::table('users')->insert([
            "username" => "demo",
            "name" => "demo",
            "email" => "demo@demo.com",
            "password" => Hash::make("123456"),
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s")
        ]);
    }
}
