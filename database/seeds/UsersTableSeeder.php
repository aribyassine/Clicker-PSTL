<?php

use App\Role;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 5; $i++){
            $user = factory(App\User::class)->make();
            $user->save();
            $user->attachRole(Role::where('name','teacher')->firstOrFail());
        }
    }
}
