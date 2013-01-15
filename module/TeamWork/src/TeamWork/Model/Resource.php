<?php
namespace TeamWork\Model;

use Common\Grandmaster;
use Zend\Debug\Debug;

class Resource extends Grandmaster
{
	protected $_select;
	
	public function __construct()
	{
		parent::getAdapter();
		$this->table = 'resource';
	}
	
	/**
	 * 根据pid获取Action
	 * 
	 * @param 
	 * @return Obj $result
	 * @author Zen
	 * @since 2012-12-26
	 */
	public function getActionByPid($pid)
	{
		$Action = array();
		$where['um_pid'] = $pid;
		$data = $this->fetchAll($where, array('*'), 'um_order asc');
		foreach($data as $v) {
			$Action[] = $v;
		}
		return $Action;
	}
	
	/**
	 * 左菜单的配置
	 * 
	 * @author Zen
	 * @since 2012-12-27
	 */
	public function getMenu($aclcontrol, $controller, $action, $group)
	{
		$data = $this->fetchAll(array('um_isurl' => 1), array('*'), 'um_order asc');
		foreach($data as $v) {
			$allmenu[] = $v;
		}
		
		unset($data);
		$currentMenu = array();

		if(!empty($allmenu)) {
			foreach($allmenu as $k => $v) {
				if($v['um_pid'] == 1) {
					$FidUrl = $v['um_url'];
					if(!$aclcontrol->_acl->isAllowed($group, $v['um_url'], 'index')) continue;
				} elseif($v['um_pid'] != 0 && $v['um_pid'] != 1) {
					$aclaction = explode('/', $v['um_url']);
					if(!$aclcontrol->_acl->isAllowed($group, $FidUrl, $aclaction[3])) continue;
				}
				$currentMenu[$k] = $v;
			}
		}
		return $currentMenu;
	}
	
	/**
	 * 获取所有菜单
	 * 
	 * @author Zen
	 * @since 2013-01-05
	 */
	public function getAllMenu()
	{
		$data = $this->fetchAll(array('um_isurl' => 1), array('*'), 'um_order asc');
		foreach($data as $v) {
			$allmenu[] = $v;
		}
		return $allmenu;
	}
	
	/**
	 * 根据id获取一条数据
	 * 
	 * @param int $id
	 * @return object $row
	 * @author Zen
	 * @since 2013-01-07
	 */
	public function getRowById($id)
	{
		if(!isset($id)) {
			throw new \Exception('参数有误！需要资源id');
		}
		
		$where['um_id'] = $id;
		return $this->fetchRow($where);
	}
}