<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(1)->create();

        \App\Models\Service::factory(5)->create();

        \App\Models\Customer::factory(20)->create()->each(function ($customer) {
            
            $arr = [];

            for($i=0; $i<rand(1,5); $i++) {

                $rand = rand(1,5);

                while(in_array($rand, $arr)) {
                    $rand = rand(1,5);
                }

                array_push($arr, $rand);
            }
            
            $customer->services()->attach($arr);
        });
    }
}
