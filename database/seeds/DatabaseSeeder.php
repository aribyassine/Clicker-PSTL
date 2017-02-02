<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(UsersTableSeeder::class);
        DB::table('users')->truncate();
        for ($i = 0; $i < 5; $i++){
            $user = factory(App\User::class)->make();
            $user->save();
        }
    }
}
