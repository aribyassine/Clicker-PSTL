<?php

namespace App\Http\Controllers;

use App\Question;
use App\Response;
use App\Ue;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($question_id)
    {
        try {
            $question = Question::findOrFail($question_id);
            if ($question->session->teacher != User::authenticated())
                throw new AuthorizationException();
            return $question->responses()->get();

        } catch (AuthorizationException $exception) {
            abort(403, "Access defined : you don't have ability to see responses to the question with id $question_id");
        } catch (ModelNotFoundException $exception) {
            abort(404, "Not found Question with id $question_id");
        }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $question_id)
    {
        try {
            $question = Question::findOrFail($question_id);

            $this->authorize('create', [Response::class, $question->session->ue]);
            $rep = $request->all();
            $user = User::authenticated();
            if (in_array('true', $rep))
                foreach ($rep as $key => $value) {
                    $value = $value === 'true' ? true : false;
                    if (is_numeric($key) && $value) {
                        $reponse = new Response();
                        $reponse->question()->associate($question);
                        $reponse->user()->associate($user);
                        $reponse->response = $key;
                        $reponse->save();
                    }
                }
            else {
                $reponse = new Response();
                $reponse->question()->associate($question);
                $reponse->user()->associate($user);
                $reponse->save();
            }
            return $this->response->noContent();
        } catch (AuthorizationException $exception) {
            abort(403, "Access defined : you don't have ability to create responses in the question with id : $question_id");
        } catch (ModelNotFoundException $exception) {
            abort(404, "Not found Question with id $question_id");
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
