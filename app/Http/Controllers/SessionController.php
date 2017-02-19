<?php

namespace App\Http\Controllers;

use App\Http\Requests\SessionRequest;
use App\Session;
use App\Ue;
use App\User;
use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($ue_id)
    {
        try {
            $ue = Ue::findOrFail($ue_id);
            return $ue->sessions()->get();
        } catch (ModelNotFoundException $exception) {
            abort(404, "Not found Ue with id $ue_id");
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SessionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SessionRequest $request, $ue_id)
    {
        try {
            $ue = Ue::findOrFail($ue_id);
            $this->authorize('create', $ue);
            $params = $request->only(['title', 'number']);
            if ($ue->sessions()->whereNumber($params['number'])->get()->isEmpty()) {
                $session = new Session();
                $session->fill($params);
                $session->teacher()->associate(User::authenticated());
                $session->ue()->associate($ue);
                $session->save();
                return $this->response->array($session);
            } else
                throw new StoreResourceFailedException("This session number already exists");

        } catch (ModelNotFoundException $exception) {
            abort(404, "Not found Ue with id $ue_id");
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
     * @param  SessionRequest $request
     * @param  int $session_id
     * @return \Illuminate\Http\Response
     */
    public function update(SessionRequest $request,$session_id)
    {
        try {
            $session = Session::findOrFail($session_id);
            $ue = $session->ue;
            $this->authorize('update',$session);
            $params = $request->only(['title', 'number']);
            if ($ue->sessions()->whereNumber($params['number'])->get()->isEmpty() || $params['number'] == $session->number) {
               $session->update($params);
                return $this->response->array($session);
            } else
                throw new StoreResourceFailedException("This session number already exists");

        } catch (ModelNotFoundException $exception) {
            abort(404, "Not found Session with id $session_id");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $session_id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $session_id)
    {
        try {
            //$ue = Ue::findOrFail($id);
            $session = Session::findOrFail($session_id);
            $this->authorize('delete', $session);
            $session->delete();
            return $this->response->noContent();
        } catch (AuthorizationException $exception) {
            abort(403, "Access defined : you don't have ability to delete the Session with id $session_id");
        } catch (ModelNotFoundException $exception) {
            abort(404, "Not found Session with id $session_id");
        }
    }
}
