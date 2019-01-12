<?php

namespace App\Models;

use PhpParser\Node\Expr\Array_;

trait HasViewers {
	public function addViewers( Array $viewers ) {
			return $this->viewers()->sync($viewers);
	}

	public function removeViewers( Array $viewers ) {
		return $this->viewers()->detach ($viewers);
	}

}