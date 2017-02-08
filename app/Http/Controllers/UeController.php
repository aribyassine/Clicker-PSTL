<?php

namespace App\Http\Controllers;

use App\Http\Requests\UeStoreRequest;
use App\Ue;
use App\User;
use Dingo\Api\Exception\ResourceException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class UeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $myUes = User::authenticated()->ues()->get();
        $otherUes = Ue::whereNotIn('id', $myUes)->get();
        return $this->response->array(['my_ues' => $myUes , 'other_ues' => $otherUes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     * @param UeStoreRequest $request
     * @return Response
     */
    public function store(UeStoreRequest $request)
    {
        $this->authorize('create', Ue::class);
        $params = $request->only(['code_ue','name']);
        try {
            $ue = Ue::create($params);
            User::authenticated()->ues()->attach($ue);
            return $this->response->array($ue);
        }catch (QueryException $exception){
            $code = $params['code_ue'];
            throw new StoreResourceFailedException("$code : This ue already exists");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        try {
        $ue = Ue::findOrFail($id);
        $ue->students = $ue->students()->get();
        $ue->teachers = $ue->teachers()->get();
        return $ue;
        }catch (ModelNotFoundException $exception){
            throw new ResourceException("Not found Ue with id $id");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {

    }

}

?>