<?php

namespace App\Http\Controllers;

use Adldap\Exceptions\ModelNotFoundException;
use App\Http\Requests\QuestionRequest;
use App\Question;
use App\Session;
use Illuminate\Auth\Access\AuthorizationException;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($session_id)
    {
        try {
           return  Session::with('questions')->findORFail($session_id);
        } catch (ModelNotFoundException $exception) {
            abort(404, "Not found Session with id $session_id");
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  QuestionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionRequest $request, $session_id)
    {
        try {
            $session = Session::findOrFail($session_id);
            $this->authorize('create', [Question::class, $session]);
            $title = $request->get('title');
            $max = $session->questions()->get()->max('number');
            $question = new Question();
            $question->number = $max + 1;
            $question->title = $title;
            $question->session()->associate($session);
            $question->save();
            return $this->response->array($question);

        } catch (ModelNotFoundException $exception) {
            abort(404, "Not found Session with id $session_id");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //TODO
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  QuestionRequest $request
     * @param  int $question_id
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionRequest $request, $question_id)
    {
        try {
            $question = Question::findOrFail($question_id);
            $this->authorize('update', $question);
            $title = $request->get('title');
            if ($title != $question->title) {
                $question->update(['title' => $title]);
            }
            return $this->response->array($question);
        } catch (ModelNotFoundException $exception) {
            abort(404, "Not found Question with id $question_id");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $question_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($question_id)
    {
        try {
            $question = Question::findOrFail($question_id);
            $this->authorize('delete', $question);
            $question->delete();
            $nextQuestions = Question::where('number', '>', $question->number)->get();
            $nextQuestions->each(function (Question $quest) {
                $quest->number--;
                $quest->save();
            });
            return $this->response->noContent();

        } catch (AuthorizationException $exception) {
            abort(403, "Access defined : you don't have ability to delete the Question with id $question_id");
        } catch (ModelNotFoundException $exception) {
            abort(404, "Not found Question with id $question_id");
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $question_id
     * @return QuestionRequest question
     */
    public function switchState($question_id){
        try{
            $question = Question::findOrFail($question_id);
            $this->authorize('update',$question);
            if($question->opened == 0)
                $question->update(['opened' => 1]);
            else
                $question->update(['opened' => 0]);
            return $this->response->array($question);
        } catch (AuthorizationException $exception) {
            abort(403, "Access defined : you don't have ability to close the Question with id $question_id");
        } catch (ModelNotFoundException $exception) {
            abort(404, "Not found Question with id $question_id");
        }

    }
}
