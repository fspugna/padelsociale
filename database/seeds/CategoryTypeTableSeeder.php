<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CategoryTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('category_types')->insert([
            'name' => 'maschile',    
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),        
        ]);

        DB::table('category_types')->insert([
            'name' => 'femminile',    
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),        
        ]);

        DB::table('category_types')->insert([
            'name' => 'mista',    
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),        
        ]);
    }
}
