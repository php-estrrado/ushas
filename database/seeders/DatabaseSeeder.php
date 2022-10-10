<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         DB::table('days')->insert([
            ['id' => 0,'day' => 'Sunday'],['id' => 1,'day' => 'Monday'],['id' => 2,'day' => 'Tuesday'],['id' => 3,'day' => 'Wednesday'],
            ['id' => 4,'day' => 'Thursday'],['id' => 5,'day' => 'Friday'],['id' => 6,'day' => 'Saturday']
        ]);
        DB::table('prd_product_types')->insert([['type_name' => 'Simple'],['type' => 'Configurable']]);
        DB::table('payment_status')->insert([['identifier' => 'pending','title'=>'Pending'],['identifier' => 'processing','title'=>'Processing'],['identifier' => 'success','title'=>'Success'],['identifier' => 'failed','title'=>'Failed'],['identifier' => 'cncelled','title'=>'Cncelled']]);
        DB::table('shipping_status')->insert([['title' => 'Pending'],['title' => 'Ready for Ship'],['title' => 'Shipped'],['title' => 'Reached'],['title' => 'Out of Delivery'],['title' => 'Delivered']]);
    }
}
