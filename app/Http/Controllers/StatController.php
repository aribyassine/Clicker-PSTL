<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Support\Collection;

class StatController extends Controller
{
    /**
     * @param $id
     */
    public function question($id)
    {
        try {
            $question = Question::with('propositions')->findOrfail($id);
            $propositions = $question->propositions;
            $responses = $question->responses()->get();

            $user_rep = $responses->groupBy('user_id');
            $tours_count = max($user_rep->map(function ($item) {
                return $item->count();
            })->values()->toArray());
            for ($i = 1; $i <= $tours_count; $i++) {
                $tours[$i] = $user_rep->reduce(function ($carry, $item) use ($i) {
                    if (isset($item[$i - 1]))
                        array_push($carry, $item[$i - 1]);
                    return $carry;
                }, []);
            }

            /*            foreach ($responses as $response) {
                            $user_rep = $responses->where('user_id', $response->user_id);
                            $i = 0;
                            foreach ($user_rep as $rep) {
                                $i++;
                                isset($tours[$i]) ? array_push($tours, $i ) : $tours[$i] = $rep;
                            }
                        }*/
            foreach ($propositions as $proposition ){
                $tour = [];
                $proposition->stat = new Collection();
                $proposition->stat["responses_count"] = $responses->where('response', $proposition->number)->count();
                foreach ($tours as $key => $value) {
                    $tour = array_add(
                        $tour,
                        $key,
                        collect($value)->filter(function ($value) use ($proposition) {
                            return $value->response == $proposition->number;
                        })->count()
                    );
                }
                $proposition->stat["tour"] = $tour;
            }
            return $question;
        } catch (ModelNotFoundException $exception) {
            abort(404, "Not found question with id $id");
        }
    }
}
