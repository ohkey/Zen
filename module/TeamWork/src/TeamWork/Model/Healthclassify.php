<?php
namespace TeamWork\Model;

use Common\Grandmaster;

class Healthclassify extends Grandmaster
{
	protected $select;
	public function __construct()
	{
		parent::getAdapter();
		$this->table = 'healthclassify';
		$this->select = $this->sqlObj->select();
	}
}