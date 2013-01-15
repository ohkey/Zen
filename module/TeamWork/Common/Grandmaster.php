<?php
/**
 * model总控层
 * 
 * @author ohkey
 * @since 2012-12-21
 */
namespace Common;

use Zend\Debug\Debug;
use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;

class Grandmaster extends AbstractTableGateway
{
	const _switch_off = 0;
	const _switch_on = 1;
	
	public $sqlObj = null;
	public $table = null;
	public $tableObj = null;
	public $adapter = null;
	
	//获得适配器
	public function getAdapter($conifgname='master')
	{
		$configArray = require './common/config/config.php';
		$config = new \Zend\Config\Config($configArray);
		$config = $config->toArray();
		$this->adapter = new Adapter($config['adapter'][$conifgname]);
		$this->sqlObj = new Sql($this->adapter);
	}
	
	public function save($data)
	{
		if(empty($data)) throw new \Exception('参数有误！');

		if($this->insert($data)) {
			return $this->lastInsertValue;
		} else {
			return false;
		}
		$columns = array();
		$values = array();
		foreach($data as $k=>$v) {
			array_push($columns, $k);
			array_push($values, $v);
		}
		$insert = $this->sqlObj->insert();
		$insert->into($this->table);
		$insert->columns($columns);
		$insert->values($values);
		$statement = $this->sqlObj->prepareStatementForSqlObject($insert);

		return $statement->execute();
	}
	
	public function fetchAll($where = null,$columns = array('*'), $order=null, $limit=null, $offset=null)
	{
		$select = $this->sqlObj->select();
		$select->from($this->table);
		$select->columns($columns);
		if($where!=null) {
			$select->where($where);
		}
		if($order!=null) {
			$select->order($order);
		}
		if($limit!=null) {
			$select->limit($limit);
		}
		if($offset!=null) {
			$select->offset($offset);
		}
		
		$statement = $this->sqlObj->prepareStatementForSqlObject($select);
		return $statement->execute();
	}
	
	/**
	 * 查询一行
	 * 
	 * @param array $where
	 * @throws \Exception
	 * @return Ambigous <multitype:, ArrayObject, NULL, \ArrayObject, unknown>
	 */
	public function fetchRow($where)
	{
		if(!$this->table) {
			throw new \Exception('表不存在');
		}
		
		$rowset = $this->select($where);
		$row = $rowset->current();
		return $row;
	}
}