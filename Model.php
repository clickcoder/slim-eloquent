<?php
namespace Slim\Eloquent;

class Model extends \Illuminate\Database\Eloquent\Model {

	public function newEloquentBuilder($query)
	{
		return new Builder($query);
	}

}
