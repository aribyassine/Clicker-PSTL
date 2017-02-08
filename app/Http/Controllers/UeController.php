<?php

namespace App\Http\Controllers;

use App\Http\Requests\UeStoreRequest;
use App\Ue;
use App\User;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Http\Response;
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
            Ue::create($params);
            return $this->response->created();
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