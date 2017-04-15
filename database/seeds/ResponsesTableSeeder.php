<?php

use App\Question;
use App\Response;
use App\Session;
use App\Ue;
use App\User;
use Illuminate\Database\Seeder;

class ResponsesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = User::all()->filter(function (User $user) {
            return $user->hasRole('student');
        });
        $students->each(function (User $student) {
            $student->ues()->get()->each(function (Ue $ue) use ($student) {
                $ue->sessions()->get()->each(function (Session $session) use ($student) {
                    $session->questions()->get()->each(function (Question $question) use ($student) {
                        if (random_int(0, 4) != 0) {
                            $response = new Response();
                            $response->question()->associate($question);
                            $response->user()->associate($student);
                            $response->response = random_int(1, 3);
                            $response->save();
                            if (random_int(0, 1) != 0) {
                                $response = new Response();
                                $response->question()->associate($question);
                                $response->user()->associate($student);
                                $response->response = random_int(1, 3);
                                $response->save();
                            }
                        }
                    });
                });
            });
        });
    }
}
