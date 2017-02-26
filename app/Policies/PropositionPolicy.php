<?php

namespace App\Policies;

use App\Session;
use App\User;
use App\Proposition;
use Illuminate\Auth\Access\HandlesAuthorization;

class PropositionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the proposition.
     *
     * @param  \App\User  $user
     * @param  \App\Proposition  $proposition
     * @return mixed
     */
    public function view(User $user, Proposition $proposition)
    {
        return $proposition->question->session->ue->users()->get()->contains($user);
    }

    /**
     * Determine whether the user can create propositions.
     *
     * @param  \App\User $user
     * @param  \App\Session $session
     * @return mixed
     */
    public function create(User $user, Session $session)
    {
        return $session->teacher == $user;
    }

    /**
     * Determine whether the user can update the proposition.
     *
     * @param  \App\User  $user
     * @param  \App\Proposition  $proposition
     * @return mixed
     */
    public function update(User $user, Proposition $proposition)
    {
        return $proposition->question->session->teacher == $user;
    }

    /**
     * Determine whether the user can delete the proposition.
     *
     * @param  \App\User  $user
     * @param  \App\Proposition  $proposition
     * @return mixed
     */
    public function delete(User $user, Proposition $proposition)
    {
        return $proposition->question->session->teacher == $user;
    }

}
