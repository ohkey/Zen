<?php
namespace TeamWork\Model;

use Zend\Debug\Debug;

use Common\Grandmaster;

class Groups extends Grandmaster
{
	protected $select;
	protected $groups;
	protected $access;
	protected $teamuser;
	
	public function __construct()
	{
		parent::getAdapter();
		$this->table = 'groups';
		$this->select = $this->sqlObj->select();
		//$this->groups = new Groups();
		//$this->access = new Access();
		//$this->teamuser = new Teamuser();
	}
	
	public function getAllGroups()
	{
		$data = $this->fetchAll();
		foreach($data as $v) {
			$allcount[] = $v;
		}
		return $allcount;
	}
	
	/**
	 * 根据角色来获取数据
	 * 
	 * @param string $role
	 * @return array $row
	 */
	public function getGroupByRole($role)
	{
		$Action = array();
		$where['gp_role'] = $role;
		$data = $this->fetchAll($where);
		foreach($data as $v) {
			$Action[] = $v;
		}
		return $Action[0];
	}
	
	/**
	 * 删除组
	 * 将组别状态更改，删除当前组别下的访问列表并加入一条总览的访问路径
	 * 
	 * @param int $id
	 * @return array $ret
	 * @author Zen
	 * @since 2013-01-14
	 */
	public function deleteGroup($id)
	{
		if(!isset($id) || empty($id)) {
			throw new \Exception('参数有误！');
		}
		
		$ret = array();
		$ret['retcode'] = 0;
		$ret['message'] = '';

		$groupinfo = $this->fetchRow(array('gp_id'=>$id));
		
		//查询当前分组下是否还存在用户
		$teamuser = new Teamuser();
		$users = $teamuser->fetchAll(array('role'=>$groupinfo['gp_role']));
		//如果没有用户，直接删除角色
		if(!$users->count()) {
			$de = $this->delete(array('gp_id'=>$id));
			if($de < 0) {
				$ret['retcode'] = -1;
				$ret['message'] = '角色删除失败';
				return $ret;
			}
		}
		//如果还存在用户，则更新分组状态为已删除，删除该分组的访问列表并插入一条总览的访问路径
		else {
			$this->adapter->getDriver()->getConnection()->beginTransaction();
			//更新分组的状态
			$groups = new Groups();
			$access = new access();
			$up = $groups->update(array('gp_isdelete'=>Grandmaster::_switch_on), 'gp_id='.$id);
			if($up < 0) {
				$ret['retcode'] = -2;
				$ret['message'] = '分组状态更新失败';
			}
			
			//将分组下的访问列表删除并插入一条总览的访问路径
			$de = $access->delete('as_groupid='.$id);
			if($de < 0) {
				$ret['retcode'] = -3;
				$ret['message'] = '访问列表清理失败';
			}
			
			$data = array();
			$data['as_resource'] = 2;
			$data['as_action'] = serialize(array('index'));
			$data['as_act'] = 'allow';
			$data['as_groupid'] = $id;
			$data['as_group'] = $groupinfo['gp_role'];
			
			$in = $access->insert($data);
			if($in < 0) {
				$ret['retcode'] = -4;
				$ret['message'] = '创建总览访问列表失败';
			}
			
			if($ret['retcode'] == 0) {
				$this->adapter->getDriver()->getConnection()->commit();
			} else {
				$this->adapter->getDriver()->getConnection()->rollback();
			}
		}
		return $ret;
	}
}