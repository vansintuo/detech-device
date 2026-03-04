<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'id' => 1,
                'name' => 'John',
                'email' => 'admin@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => null,
                'created_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Tuo',
                'email' => 'Dev@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => null,
                'created_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Tuo',
                'email' => 'Dev@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => null,
                'created_at' => now(),
            ]
        ];
        DB::table('users')->where('id', 1)->delete();
        DB::table('users')->insert($users);
    }
}
