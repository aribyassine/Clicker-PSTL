<?php

namespace App\Http\Controllers;

use App\Http\Requests\PropositionRequest;
use App\Proposition;
use App\Question;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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
            $question = Question::findOrFail($question_id);
            return $question->propositions()->get();
        } catch (ModelNotFoundException $exeption) {
            abort(404, "Not found Session with id $question_id");
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
            abort(404, "Not found Session with id $question_id");
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
