<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class StatController extends Controller
{
    /**
     * @param $id
     */
    public function question($id){
        try{
            $question = Question::with('propositions','responses')->findOrfail($id);
            $propositions = $question->propositions;
            $responses = $question->responses;

            $propositions->each(function ($proposition) use ($responses) {

                $proposition->stat = new Collection();
                $proposition->stat["responses_count"] = $responses->where('response',$proposition->number)->count();
            });
            return $question;
        }catch (ModelNotFoundException $exception) {
            abort(404, "Not found question with id $id");
        }
    }
}
