<?php

namespace App\Policies;

use App\Ue;
use App\User;
use App\Session;
use Illuminate\Auth\Access\HandlesAuthorization;

class SessionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the session.
     *
     * @param  \App\User  $user
     * @param  \App\Session  $session
     * @return mixed
     */
    public function view(User $user, Session $session)
    {
        $isASubscriber = $session->students()->get()->contains($user);
        $isTheTeacher = $session->teacher == $user;
        return $isASubscriber || $isTheTeacher;
    }

    /**
     * Determine whether the user can create sessions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user,Session $session)
    {
        return $session->teacher  == $user;
    }

    /**
     * Determine whether the user can update the session.
     *
     * @param  \App\User  $user
     * @param  \App\Session  $session
     * @return mixed
     */
    public function update(User $user, Session $session)
    {
        return $session->teacher == $user;
    }

    /**
     * Determine whether the user can delete the session.
     *
     * @param  \App\User  $user
     * @param  \App\Session  $session
     * @return mixed
     */
    public function delete(User $user, Session $session)
    {
        return $session->teacher == $user;
    }
}
