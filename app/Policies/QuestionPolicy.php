<?php

namespace App\Policies;

use App\Question;
use App\Session;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the question.
     *
     * @param  \App\User $user
     * @param  \App\Question $question
     * @return mixed
     */
    public function view(User $user, Question $question)
    {
        return $question->session->ue->users()->get()->contains($user);
    }

    /**
     * Determine whether the user can create questions.
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
     * Determine whether the user can update the question.
     *
     * @param  \App\User $user
     * @param  \App\Question $question
     * @return mixed
     */
    public function update(User $user, Question $question)
    {
        return $question->session->teacher == $user;
    }

    /**
     * Determine whether the user can delete the question.
     *
     * @param  \App\User $user
     * @param  \App\Question $question
     * @return mixed
     */
    public function delete(User $user, Question $question)
    {
        return $question->session->teacher == $user;
    }
}
