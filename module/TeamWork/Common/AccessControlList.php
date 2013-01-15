<?php
/**
 * 自定义访问控制插件（可单独使用）
 * 
 * @author ohkey
 * @since 2012-12-24
 */
namespace Common;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\Debug\Debug;

class AccessControlList
{
	public $_acl = null ;
	
	public function __construct()
	{
		$this->_acl = $this->getAcl();
	}
	
	/**
	 * 访问控制列表初始化
	 * 
	 * @param Array $param
	 * @author Zen
	 * @since 2012-12-24
	 */
	public function setAcl($param)
	{
		$module = $param['module'];
		$controller = $param['controller'];
		$role = $param['role'];
		$accesslist = $param['accesslist'];
		
		//设置控制资源
		$this->setResources($module,$controller);
		//添加用户组别
		$this->addGroup($role);
		//添加自定义访问规则
		$this->addUserAccess($accesslist);
		//设置默认访问规则
		$this->defaultAccess();
	}
	
	/**
	 * 设置ZendFramework ACL
	 * 
	 * @return resource $this->_acl
	 * @author Zen
	 * @since 2012-12-24
	 */
	private function getAcl()
	{
		if($this->_acl==null) {
			$this->_acl = new Acl();
		}
		return $this->_acl;
	}
	
	/**
	 * 设置控制资源
	 * 
	 * @param resource $resourceTop(模块)
	 * @param resource $resourceChild(控制器)
	 * @author Zen
	 * @since 2012-12-24
	 */
	private function setResources($resourceTop,$resourceChild)
	{
		if(empty($resourceTop->um_url)) {
			throw new \Exception('资源错误');
		}
		//将模块加入资源
		$this->_acl->addResource(new Resource($resourceTop->um_url));
		
		//将控制器加入资源
		if($resourceChild) {
			foreach ($resourceChild as $v) {
				$this->_acl->addResource(new Resource($v['um_url']),$resourceTop['um_url']);
			}
		}		
	}
	
	/**
	 * 添加用户组别
	 * 
	 * @param resource $groupList
	 * @author Zen
	 * @since 2012-12-24
	 */
	private function addGroup($groupList) 
	{
		if($groupList) {
			
			//将用户组别加入角色
			foreach ($groupList as $v) {
				
				//如果没有父级角色，直接添加
				if(empty($v->gp_parentrole)){
					$this->_acl->addRole(new Role($v['gp_role']));
				} else {
					
					//否则判断是否存在父级角色，不存在先添加父级，而后添加子级并有继承父级的权限
					if(!$this->_acl->hasRole($v['gp_parentrole'])) {
						
						$this->_acl->addRole(new Role($v['gp_parentrole']));
					}
				
					$this->_acl->addRole(new Role($v['gp_role']),$v['gp_parentrole']);
				}
			}
		}
	}
	
	/**
	 * 自定义控制规则
	 * 
	 * @param Array $accessList
	 * @since 2012-12-24
	 * @author Zen
	 */
	private function addUserAccess($accessList)
	{
		if($accessList) {
			foreach ($accessList as $v) {
				if(empty($v)) continue;	
				//反序列化为数组
				$action=unserialize($v['as_action']);
				//控制符
				$act=$v['as_act'];
				$action=$action ? $action : null;
				$this->_acl->$act($v['as_group'], $v['um_url'], $action);			
			}
		}
	}
	
	/**
	 * 设置默认访问规则
	 * 
	 * @author Zen
	 * @since 2012-12-24
	 */
	private function defaultAccess()
	{
		//超级管理员
		$this->_acl->allow('administrator');
		//任何来访用户都有访问登录页面的权限
		$this->_acl->allow(null,'TeamWork\Controller\Login',null);
	}
	
	/**
	 * 判断访问权限
	 * 
	 * @param Array $params
	 * @return bool
	 * @author Zen
	 * @since 2012-12-24
	 */
	public function isAllowed($controller, $action, $group)
	{
		$group = $group ? $group : null;
		
		return $this->_acl->isAllowed($group,$controller,$action);
	}
}