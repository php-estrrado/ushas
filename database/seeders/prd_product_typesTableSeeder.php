<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class prd_product_typesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('prd_product_types')->insert([['type_name' => 'Simple','type' => 'Configurable']]);
    }
}
