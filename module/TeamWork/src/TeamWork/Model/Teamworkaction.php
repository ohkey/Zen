<?php
namespace TeamWork\Model;

use Zend\Debug\Debug;
use Common\Grandmaster;

class Teamworkaction extends Grandmaster
{
	protected $select;
	public function __construct()
	{
		parent::getAdapter();
		$this->table = 'teamworkaction';
		$this->select = $this->sqlObj->select();
	}
	
	/**
	 * 根据条件获取
	 *
	 * @param array $param
	 * @return object $row
	 * @author Zen
	 * @since 2013-01-07
	 */
	public function getActionByWhere($param)
	{
		$select = $this->sqlObj->select();
		$select->from($this->table);
		
		!empty($param['parentaction']) && $select->where(array('parentaction'=>$param['parentaction']));
		
		$statement = $this->sqlObj->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		
		if(!$results) {
			return false;
		}
		
		$rows = array();
		//转换为数组
		foreach ($results as $v) {
			$rows[]=$v;
		}
		return $rows;
	}
}