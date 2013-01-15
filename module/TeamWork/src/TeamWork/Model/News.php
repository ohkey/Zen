<?php
namespace TeamWork\Model;

use Common\Grandmaster;

class News extends Grandmaster
{
	protected $select;
	public function __construct()
	{
		parent::getAdapter();
		$this->table = 'news';
		$this->select = $this->sqlObj->select();
	}
}