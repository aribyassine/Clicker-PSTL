<?php

namespace App\Http\Controllers;

use App\Http\Requests\PropositionRequest;
use App\Proposition;
use App\Question;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PropositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($question_id)
    {
        try {
            $questions = Question::with('propositions')->findOrFail($question_id);
            return $this->response->array($questions);
        } catch (ModelNotFoundException $exeption) {
            abort(404, "Not found Question with id $question_id");
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PropositionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PropositionRequest $request, $question_id)
    {
        try {
            $question = Question::findOrFail($question_id);
            $this->authorize('create', [Proposition::class, $question->session]);
            $proposition = new Proposition();

            $proposition->title = $request->get('title');
            $verdict = $request->get('verdict');
            if (in_array($verdict, ['no', 'false', '0']))
                $proposition->verdict = false;
            if (in_array($verdict, ['yes', 'true', '1']))
                $proposition->verdict = true;
            $proposition->question()->associate($question);
            $proposition->number = $max = $question->propositions()->get()->max('number') + 1;
            $proposition->save();
            return $this->response->array($proposition);
        } catch (ModelNotFoundException $exception) {
            abort(404, "Not found Question with id $question_id");
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
     * @param  PropositionRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(PropositionRequest $request, $id)
    {
        try {
            $proposition = Proposition::findOrFail($id);
            $this->authorize('update', $proposition);
            $proposition->update($request->only(['title', 'verdict']));
            return $this->response->array($proposition);

        } catch (ModelNotFoundException $exception) {
            abort(404, "Not found Proposition with id $id");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $proposition = Proposition::findOrFail($id);
            $this->authorize('delete', $proposition);
            $proposition->delete();
            $nextPropositions = Proposition::where('number', '>', $proposition->number)->get();
            $nextPropositions->each(function (Proposition $prop) {
                $prop->number--;
                $prop->save();
            });
            return $this->response->noContent();

        } catch (AuthorizationException $exception) {
            abort(403, "Access defined : you don't have ability to delete the Proposition with id $id");
        } catch (ModelNotFoundException $exception) {
            abort(404, "Not found Proposition with id $id");
        }
    }
}
