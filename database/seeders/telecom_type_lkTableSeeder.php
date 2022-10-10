<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class telecom_type_lkTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('telecom_type_lk')->insert([['name' => 'email'],['name' => 'phone']]);
    }
}
