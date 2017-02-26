<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Response extends Model {

	protected $table = 'response';
	public $timestamps = true;
	protected $fillable = array('response', 'answered');

	public function question()
	{
		return $this->belongsTo(Question::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

}