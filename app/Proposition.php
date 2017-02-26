<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proposition extends Model {

	protected $table = 'propositions';
	public $timestamps = true;
	protected $fillable = array('verdict', 'number', 'title');

	public function question()
	{
		return $this->belongsTo(Question::class);
	}

}