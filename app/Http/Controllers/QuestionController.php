<?php

namespace App\Http\Controllers;

use Adldap\Exceptions\ModelNotFoundException;
use App\Http\Requests\QuestionRequest;
use App\Proposition;
use App\Question;
use App\Session;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Validator;

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
            return Session::with('questions')->findORFail($session_id);
        } catch (ModelNotFoundException $exception) {
            abort(404, "Not found Session with id $session_id");
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @param  int $session_id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $session_id)
    {
        try {
            $inputData = $request->json()->all();
            $validator = Validator::make($inputData, [
                'title' => 'bail|required|min:1',
                'propositions.*.title' => 'bail|required|min:1',
                'propositions.*.verdict' => 'bail|required|boolean',
            ]);
            $validator->validate();
            $session = Session::findOrFail($session_id);
            $this->authorize('create', [Question::class, $session]);
            $max = $session->questions()->get()->max('number');
            $question = new Question();
            $question->number = $max + 1;
            $question->title = $inputData["title"];
            $question->opened = false;
            $question->session()->associate($session);
            $question->save();
            foreach ($inputData["propositions"] as $key => $value) {
                $this->authorize('create', [Proposition::class, $question->session]);
                $proposition = new Proposition();
                $proposition->title = $value['title'];
                $verdict = $value['verdict'];
                if (in_array($verdict, ['no', 'false', '0']))
                    $proposition->verdict = false;
                if (in_array($verdict, ['yes', 'true', '1']))
                    $proposition->verdict = true;
                $proposition->question()->associate($question);
                $proposition->number = $max = $question->propositions()->get()->max('number') + 1;
                $proposition->save();
            }
            $question->propositions = $question->propositions()->get();
            return $this->response->array($question);

        } catch (ModelNotFoundException $exception) {
            abort(404, "Not found Session with id $session_id");
        }
    }


    /**
     * Display the open questions.
     */
    public function open()
    {
        $ues = User::authenticated()->ues()->with(['sessions' => function ($query) {
            $query->with(['questions'=>function ($query){
                $query->where('opened',1);
            }]);
        }])->get()->transform( function ($ue) {
            $empty = $ue->sessions->filter(function ($session){ return $session->questions->count() == 0;});
            foreach ($empty as $key => $value)
                $ue->sessions->pull($key);
            return $ue;
        });
        $empty_ues = $ues->filter(function ($ue){ return $ue->sessions->count() == 0;});
        foreach ($empty_ues as $key => $value)
            $ues->pull($key);
        return $ues;
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
    public function switchState($question_id)
    {
        try {
            $question = Question::findOrFail($question_id);
            $this->authorize('update', $question);
            if ($question->opened == 0)
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
