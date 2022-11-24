<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@amatoripadel.it',
            'password' => bcrypt('admin'),
            'id_role' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);     
        */

        for($i=50; $i<=200; $i++){
            DB::table('users')->insert([
                'name' => 'player'.$i,
                'email' => 'player'.$i.'@amatoripadel.it',
                'password' => bcrypt('12345678'),
                'id_role' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);     
        }
    }
}
