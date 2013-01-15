<?php
namespace TeamWork\Model;

use Common\Grandmaster;

class Supplier extends Grandmaster
{
	protected $select;
	public function __construct()
	{
		parent::getAdapter();
		$this->table = 'supplier';
		$this->select = $this->sqlObj->select();
	}
}