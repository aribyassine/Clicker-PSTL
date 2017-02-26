<?php

use App\Question;
use App\Session;
use Illuminate\Database\Seeder;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Session::all()->each(function (Session $session) {
            $questions = factory(Question::class, 3)->make();
            for ($i = 1; $i <= 3; $i++) {
                $question = $questions->get($i-1);
                $question->session()->associate($session);
                $question->number = $i;
                $question->save();
            }
        });
    }
}

