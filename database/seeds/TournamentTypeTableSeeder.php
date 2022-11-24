<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TournamentTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tournament_types')->insert([
            'tournament_type' => 'Prima Fase',            
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('tournament_types')->insert([
            'tournament_type' => 'Seconda Fase',            
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
