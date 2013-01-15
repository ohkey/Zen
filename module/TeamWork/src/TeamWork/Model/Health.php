<?php
namespace TeamWork\Model;

use Common\Grandmaster;

class Health extends Grandmaster
{
	protected $select;
	public function __construct()
	{
		parent::getAdapter();
		$this->table = 'health';
		$this->select = $this->sqlObj->select();
	}
}