<?php

namespace App\Policies;

use App\Session;
use App\Ue;
use App\User;
use App\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResponsePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the response.
     *
     * @param  \App\User  $user
     * @param  \App\Response  $response
     * @return mixed
     */
    public function view(User $user, Response $response)
    {
        return $response->question->session->teacher == $user;
    }

    /**
     * Determine whether the user can create responses.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user,Ue $ue)
    {   return $user->hasRole('student') && $ue->students()->get()->contains($user);
    }

    /**
     * Determine whether the user can update the response.
     *
     * @param  \App\User  $user
     * @param  \App\Response  $response
     * @return mixed
     */
    public function update(User $user, Response $response)
    {
        return $response->user() == $user;
    }

    /**
     * Determine whether the user can delete the response.
     *
     * @param  \App\User  $user
     * @param  \App\Response  $response
     * @return mixed
     */
    public function delete(User $user, Response $response)
    {
        return $response->user() == $user;
    }
}
