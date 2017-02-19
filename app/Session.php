<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model {

	protected $table = 'sessions';
	public $timestamps = true;
	protected $fillable = array('number','title');

	public function ue()
	{
		return $this->belongsTo(Ue::class);
	}

	public function teacher()
	{
		return $this->belongsTo(User::class);
	}

	public function questions()
	{
		return $this->hasMany('Question');
	}

	public function students()
	{
		return $this->belongsToMany(User::class);
	}


}