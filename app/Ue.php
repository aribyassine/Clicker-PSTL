<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ue extends Model
{
    protected $hidden = ['pivot'];
    protected $fillable = ['name', 'code_ue'];

    public function students()
    {
        return $this->belongsToMany(User::class)
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.*')->where('roles.name', 'student');
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class)
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.*')->where('roles.name', 'teacher');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
    /**
     * Get all of the Question for the ue.
     */
    public function questions()
    {
        return $this->hasManyThrough(Question::class, Session::class);
    }
}