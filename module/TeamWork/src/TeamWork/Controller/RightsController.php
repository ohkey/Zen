<?php
namespace TeamWork\Controller;

use Common\Grandmaster;

use Common\Manager;
use Zend\Debug\Debug;
use TeamWork\Model\Resource;
use TeamWork\Model\Groups;
use TeamWork\Model\Access;
use TeamWork\Model\Teamworkaction;
use TeamWork\Model\Teamuser;

class RightsController extends Manager
{
	protected $groups;
	protected $resource;
	protected $access;
	protected $teamworkaction;
	protected $teamuser;
	
	public function __construct()
	{
		parent::init();
		$this->groups = new Groups();
		$this->resource = new Resource();
		$this->access = new Access();
		$this->teamworkaction = new Teamworkaction();
		$this->teamuser = new Teamuser();
	}
	
	/**
	 * 分组管理
	 * 
	 * @author Zen
	 * @since 2013-01-03
	 */
	public function groupsAction()
	{
		$allgroups = $this->groups->fetchAll();
		$this->view->setVariable('allgruops', $allgroups);
		return $this->view;
	}
	
	/**
	 * 添加组
	 */
	public function addgroupAction()
	{
		if($this->request->isPost()) {	
			$ret = array();
			$ret['code'] = 0;
			$ret['message'] = '';
			$params = $this->request->getPost();
			
			$exist = $this->groups->fetchRow(array('gp_role'=>$params->role));
			//如果已存在，并且被删除状态为1则恢复状态
			if ($exist->count() && $exist->gp_isdelete == Grandmaster::_switch_on) {
				$this->groups->update(array('gp_isdelete'=>Grandmaster::_switch_off), 'gp_id='.$exist->gp_id);
			} else {
				//否则，添加一条新的
				$data = array();
				$data['gp_role'] = $params->role;
				$data['gp_name'] = $params->name;
				$row = $this->groups->save($data);
				
				if($row == 0 || $row < 0) {
					$ret['code'] = -1;
					$ret['message'] = '添加失败！';
				}
			}
			echo json_encode($ret);die;
		}
		return $this->view;
	}
	
	/**
	 * 编辑组
	 * 
	 * @author Zen
	 * @since 2013-01-10
	 */
	public function editgroupAction()
	{
		$ret = array();
		$ret['retcode'] = 0;
		$ret['message'] = '';
		
		if($this->request->isPost()) {
			$param = $this->request->getPost();
			
			$row = $this->groups->update(array('gp_name'=>$param['name'], 'gp_role'=>$param['role']), 'gp_id='.$param['id']);
			
			if($row < 0) {
				$ret['retcode'] = -1;
				$ret['message'] = '修改失败，请刷新页面重试';
			}
			
			$row1 = $this->access->update(array('as_group'=>$param['role']), 'as_groupid='.$param['id']);
			echo json_encode($ret);die;
		}
		
		$id = $_GET['id'];
		
		$row = $this->groups->fetchRow(array('gp_id' => $id));

		$this->view->setVariable('id', $id);
		$this->view->setVariable('group', $row);
		return $this->view;
	}
	
	/**
	 * 删除组
	 * 
	 * @author Zen
	 * @since 2013-01-10
	 */
	public function deletegroupaction()
	{
		$param = $this->request->getPost();
		
		$ret = array();
		$ret['retcode'] = 0;
		$ret['message'] = '';
		
		$ret = $this->groups->deleteGroup($param['id']);
		
		echo json_encode($ret);die;
	}
	
	/**
	 * 分组权限
	 *
	 * @author Zen
	 * @since 2013-01-03
	 */
	public function privilegesAction()
	{
		$allgroups = $this->groups->fetchAll();
		$this->view->setVariable('allgruops', $allgroups);
		return $this->view;
	}
	
	/**
	 * 分组分配权限
	 * 
	 * @author Zen
	 * @since 2013-01-05
	 */
	public function groupauthorityAction()
	{
		$allmenu = $this->resource->getAllMenu();
		
		$group['as_group'] = $this->request->getPost('group');

		//得到当前用户组的访问列表
		$list = $this->access->getAccess($group);
		
		if($list) {			
			//处理分配权限所需数组，加入已选属性
			foreach($allmenu as $k=>$menu) {
				//如果是子菜单
				if($menu['um_level'] == 2) {
					//得到隶属于自己的action
					$where['parentaction'] = $menu['um_id'];
					$childaction = $this->teamworkaction->getActionByWhere($where);
					//加入到数组里
					$allmenu[$k]['actions'] = $childaction;
					
					//得到访问列表
					$group['as_resource'] = $menu['um_pid'];
					$currentAction = $this->access->getAccess($group);
					
					if($currentAction) {
						$actions = unserialize($currentAction[0]['as_action']);
						//为访问列表中存在的子action添加checked属性
						foreach ($actions as $v) {
							foreach($childaction as $keyforchild=>$child) {
								if($child['parentaction'] != $menu['um_id']) continue;
								if($child['action'] == $v) {
									$allmenu[$k]['actions'][$keyforchild]['checked'] = 'checked';
								}
							}
						}
					}
				} else {
					//当前用户组的访问列表，如果有已经分配的controller则加入已选属性
					foreach($list as $aclist) {
						//按照controller来分配
						if($menu['um_id'] == $aclist['as_resource']) {
							//获取当前controller下可访问的action
							$group['as_resource'] = $menu['um_id'];
							$currentAction = $this->access->getAccess($group);
							$actions = unserialize($currentAction[0]['as_action']);
							if($currentAction) {
								$allmenu[$k]['checked'] = 'checked';
								//如果是有二级菜单的controller
								if($menu['um_level'] == 1) {
									//根据当前controller下的action来判断是否加入checked属性
									foreach ($actions as $v) {
										foreach($allmenu as $k1=>$menu1) {
											//如果不是当前controller的子节点
											if($menu1['um_pid'] != $menu['um_id']) continue;
											//拆分url，判断是否已选择当前action
											$url = explode('/', $menu1['um_url']);
											if(!empty($url[3])) {
												if($url[3] == $v) {
													$allmenu[$k1]['checked'] = 'checked';
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}

		$this->view->setVariable('groupid', $this->request->getPost('id'));
		$this->view->setVariable('allmenu', $allmenu);
		$this->view->setVariable('group', $group);
		return $this->view;
	}
	
	/**
	 * 保存编辑权限 仅限administrator权限
	 * 
	 * @author Zen
	 * @since 2013-01-07
	 */
	public function savarightsAction()
	{
		if($this->request->isPost()) {
			$ret = array();
			$ret['retcode'] = 0;
			$ret['message'] = '';
			
			$params = $this->request->getPost();

			//删除当前组的访问列表
			$accparam = array();
			$accparam['as_group'] = $params['as_group'];
			$i = $this->access->delete($accparam);
			if($i < 0) throw new \Exception('列表清空出错');
			
			foreach($params['master'] as $master) {
				$row = $this->resource->getRowById($master);
				
				$action = array();
				//独立主菜单，默认indexAction
				if($row['um_level'] == 0) {
					array_push($action, 'index');
				}
				//包含下级菜单的主菜单，判断当前选择的主菜单下是否包含子菜单
				elseif($row['um_level'] == 1) {
					//查询当前主菜单的子节点
					$actions = $this->resource->getActionByPid($row['um_id']);
					//如果选择的子节点是当前主菜单下的子节点则添加
					foreach($params['slaver'] as $slaver) {
						foreach($actions as $v) {
							if($slaver == $v['um_id']) {
								//查询隶属于子节点的动作
								$param['parentaction'] = $slaver; 
								$childactions = $this->teamworkaction->getActionByWhere($param);
								foreach($childactions as $childaction) {
									foreach ($params['actions'] as $selectaction) {
										if($selectaction == $childaction['id']) {
											array_push($action, $childaction['action']);
										}
									}
								}
								$url = explode('/', $v['um_url']);
								array_push($action, $url[3]);
							}
						}
					}
				}
				if(!in_array('index', $action)) {
					array_push($action, 'index');
				}
				
				$action = serialize($action);
				//添加一条资源对应的访问列表
				$data = array();
				$data['as_resource'] = $master;
				$data['as_action'] = $action;
				$data['as_groupid'] = $params['as_groupid'];
				$data['as_group'] = $params['as_group'];
				$data['as_act'] = 'allow';
				$save = $this->access->save($data);
				if(!$save) {
					echo json_encode('访问列表添加错误');die;
				}
			}
			echo true;die;
		}
	}
	
	/**
	 * 账号管理
	 * 
	 * @author Zen
	 * @since 2013-01-03
	 */
	public function accountAction()
	{
		$users = $this->teamuser->fetchAll();
		
		foreach($users as$user) {
			$fuck[] = $user;
		}
		
		foreach($fuck as $k=>$user) {
			$role = $this->groups->getGroupByRole($user['role']);
			
			$fuck[$k]['rolename'] = $role['gp_name'];
			$fuck[$k]['isdelete'] = $role['gp_isdelete'];
			
		}

		$this->view->setVariable('users', $fuck);
		
		return $this->view;
	}
	
	/**
	 * 添加账号
	 * 
	 * @author Zen
	 * @since 2013-01-09
	 */
	public function addaccountAction()
	{
		if($this->request->isPost()) {
			$ret = array();
			$ret['retcode'] = 0;
			$ret['message'] = '';
			
			$params = $this->request->getPost();
			
			if($params['EndUserPwd'] == "") {
				$params['EndUserPwd'] = 'guest';
			}
			
			$newaccount = array();
			$newaccount['EndUserName'] = $params['EndUserName'];
			$newaccount['EndUserPwd'] = md5($params['EndUserPwd']);
			$newaccount['role'] = $params['role'];
			
			$i = $this->teamuser->save($newaccount);
			if(!$i) {
				$ret['retcode'] = -1;
				$ret['message'] = '用户保存失败';
				echo json_encode($ret);die;
			}
			
			echo json_encode($ret);die;
		}
		
		$allgroup = $this->groups->getAllGroups();

		$this->view->setVariable('allgroup', $allgroup);
		return $this->view;
	}
	
	/**
	 * 编辑账号
	 * 
	 * @author Zen
	 * @since 2013-01-11
	 */
	public function editaccountAction()
	{
		if($this->request->isPost()) {
			$ret = array();
			$ret['retcode'] = 0;
			$ret['message'] = '';
			
			$params = $this->request->getPost();
			
			$i = $this->teamuser->update(array('EndUserName'=>$params['EndUserName'], 'role'=>$params['role']), 'id='.$params['id']);
			if($i < 0) {
				$ret['retcode'] = -1;
				$ret['message'] = '编辑失败，请刷新重试';
			}
			echo json_encode($ret);die;
		}
		
		$id = $_GET['id'];
		
		$row = $this->teamuser->fetchRow(array('id'=>$id));
		$allgroup = $this->groups->getAllGroups();
		
		$this->view->setVariable('id', $id);
		$this->view->setVariable('allgroup', $allgroup);
		$this->view->setVariable('info', $row);
		return $this->view;
	}
	
	/**
	 * 删除账号
	 * 
	 * @author Zen
	 * @since 2013-01-11
	 */
	public function deleteaccountAction()
	{
		$param = $this->request->getPost();
		
		$ret = array();
		$ret['retcode'] = 0;
		$ret['message'] = '';
		
		$row = $this->teamuser->delete(array('id' => $param['id']));
		if($row < 0) {
			$ret['retcode'] = -1;
			$ret['message'] = '删除失败，刷新页面重试';
			echo json_encode($ret);die;
		}
		
		echo json_encode($ret);die;
	}
}