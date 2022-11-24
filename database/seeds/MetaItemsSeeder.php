<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MetaItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_meta_items')->insert([
            'meta_key' => 'mobile_phone', 
            'meta_type' => 'string',	
            'meta_values' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('user_meta_items')->insert([
            'meta_key' => 'residenza', 
            'meta_type' => 'string',	
            'meta_values' => '', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);            

        DB::table('user_meta_items')->insert([
            'meta_key' => 'quartiere', 
            'meta_type' => 'string',	
            'meta_values' => '', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);            
        DB::table('user_meta_items')->insert([
            'meta_key' => 'luogo_nascita', 
            'meta_type' => 'string',	
            'meta_values' => '', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);            
        DB::table('user_meta_items')->insert([
            'meta_key' => 'data_nascita',
            'meta_type' => 'date', 
            'meta_values' => '', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);            
        DB::table('user_meta_items')->insert([
            'meta_key' => 'scarpe', 
            'meta_type' => 'string', 
            'meta_values' => '', 	
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);            
        DB::table('user_meta_items')->insert([
            'meta_key' => 'racchetta', 
            'meta_type' => 'string',	
            'meta_values' => '', 		
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);            
        DB::table('user_meta_items')->insert([
            'meta_key' => 'gioco', 
            'meta_type' => 'select', 
            'meta_values' => 'Destro,Mancino,Ambidestro',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);            
        DB::table('user_meta_items')->insert([
            'meta_key' => 'dritto', 
            'meta_type' => 'slider',	
            'meta_values' => '', 		
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);            
        DB::table('user_meta_items')->insert([
            'meta_key' => 'rovescio', 
            'meta_type' => 'slider',	
            'meta_values' => '', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);            
        DB::table('user_meta_items')->insert([                
            'meta_key' => 'velocita', 
            'meta_type' => 'slider',	
            'meta_values' => '', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);            
        DB::table('user_meta_items')->insert([
            'meta_key' => 'potenza', 
            'meta_type' => 'slider',
            'meta_values' => '', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);            
        DB::table('user_meta_items')->insert([
            'meta_key' => 'servizio', 
            'meta_type' => 'slider',	
            'meta_values' => '', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);            
        DB::table('user_meta_items')->insert([
            'meta_key' => 'attacco'	, 
            'meta_type' => 'slider',	
            'meta_values' => '', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);            
        DB::table('user_meta_items')->insert([
            'meta_key' => 'difesa', 
            'meta_type' => 'slider',	
            'meta_values' => '', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);            
        DB::table('user_meta_items')->insert([
            'meta_key' => 'esperienza', 
            'meta_type' => 'slider',	
            'meta_values' => '', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);            
        DB::table('user_meta_items')->insert([
            'meta_key' => 'livello_complessivo',
            'meta_type' => 'slider',
            'meta_values' => '', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
    }
}
