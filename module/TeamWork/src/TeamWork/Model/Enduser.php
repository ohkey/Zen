<?php
namespace TeamWork\Model;

use Common\Grandmaster;

class Enduser extends Grandmaster
{
	public function __construct()
	{
		parent::getAdapter();
		$this->table = 'enduser';
	}
}