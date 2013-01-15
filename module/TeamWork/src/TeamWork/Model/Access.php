<?php
namespace TeamWork\Model;

use Zend\Debug\Debug;

use Common\Grandmaster;

class Access extends Grandmaster
{
	protected $select;
	public function __construct()
	{
		parent::getAdapter();
		$this->table = 'access';
		$this->select = $this->sqlObj->select();
	}
	
	public function getAccess($group=array())
	{
		$select = $this->sqlObj->select();
		$select->from($this->table);
		$select->join('resource', 'access.as_resource=resource.um_id', array('um_url','um_name'), $select::JOIN_LEFT);

		!empty($group['as_group']) && $select->where(array('as_group'=>$group['as_group']));
		!empty($group['as_resource']) && $select->where(array('as_resource'=>$group['as_resource']));
		
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
	
	public function getAccessListByGroup($group)
	{
		$select = $this->sqlObj->select();
		$select->from($this->table);
		$select->join('resource', 'access.as_resource=resource.um_id', array('um_url','um_name'), $select::JOIN_LEFT);
		$select->where($group);
		$statement = $this->sqlObj->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		
		//$list = $this->fetchAll(array('as_group'=>$group));
		foreach($results as $v) {
			$accesslist[] = $v;
		}
		Debug::dump($accesslist);die;
	}
	
	/**
	 * 根据条件获取访问列表
	 * 
	 * @param array $param
	 * @return object $row
	 * @author Zen
	 * @since 2013-01-07
	 */
	public function getRowByWhere($param)
	{
		if(!isset($param)) {
			throw new \Exception('参数有误！需要资源id');
		}
		
		!empty($param['as_group']) && $where['as_group'] = $param['as_group'];
		!empty($param['as_resource']) && $where['as_resource'] = $param['as_resource'];
		
		return $this->fetchRow($where);
	}
}