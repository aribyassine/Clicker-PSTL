<?php

use App\Session;
use App\Ue;
use Illuminate\Database\Seeder;

class SessionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ue::all()->each(function (Ue $ue){
            $sessions = factory(Session::class, 3)->make();
            foreach ($sessions as $session){
                $session->ue()->associate($ue);
                $session->teacher()->associate($ue->teachers()->first());
                $session->save();
            }
        });
    }
}
