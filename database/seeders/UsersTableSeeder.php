<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'id'=> 2,
        	'nik' => 3562730003,
        	'nama' => 'Kim Seungmin',
        	'telp' => '082361872593',
            'username' => 'seungm00',
            'password' => bcrypt('123456'),
        	'level' => 'admin'
        ]);
    }
}
