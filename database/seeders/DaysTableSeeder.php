<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('days')->insert([
            ['id' => 0,'day' => 'Sunday'],['id' => 1,'day' => 'Monday'],['id' => 2,'day' => 'Tuesday'],['id' => 3,'day' => 'Wednesday'],
            ['id' => 4,'day' => 'Thursday'],['id' => 5,'day' => 'Friday'],['id' => 6,'day' => 'Saturday']
        ]);
    }
}
