<?php

use App\Proposition;
use App\Question;
use Illuminate\Database\Seeder;

class PropositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Question::all()->each(function (Question $question) {
            $propositions = factory(Proposition::class, 3)->make();
            for ($i = 1; $i <= 3; $i++) {
                $proposition = $propositions->get($i - 1);
                if ($i==1)
                    $proposition->verdict = true;
                elseif ($i==2 )
                    $proposition->verdict = (bool)random_int(0,1);
                else
                    $proposition->verdict = false;
                $proposition->question()->associate($question);
                $proposition->number = $i;
                $proposition->save();
            }
        });
    }
}
