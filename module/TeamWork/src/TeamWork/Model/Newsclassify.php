<?php
namespace TeamWork\Model;

use Common\Grandmaster;

class Newsclassify extends Grandmaster
{
	protected $select;
	public function __construct()
	{
		parent::getAdapter();
		$this->table = 'newsclassify';
		$this->select = $this->sqlObj->select();
	}
}