<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{

    protected $table = 'questions';
    public $timestamps = true;
    protected $fillable = array('title', 'number');

    public function propositions()
    {
        return $this->hasMany(Proposition::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

}