<?php

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
        // $this->call(UsersTableSeeder::class);
        App\ResourceCategory::all()->each(function($cat){
        	$cat->resources()->saveMany(factory(App\Resource::class,rand(2,5))->make());
        });
    }
}
