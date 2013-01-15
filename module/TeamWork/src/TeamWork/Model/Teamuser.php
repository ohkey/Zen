<?php
namespace TeamWork\Model;

use Common\Grandmaster;

class Teamuser extends Grandmaster
{
	public function __construct()
	{
		parent::getAdapter();
		$this->table = 'teamuser';
	}
}