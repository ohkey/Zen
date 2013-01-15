<?php
/**
 * 控制器总控
 * 
 * @author ohkey
 * @since 2012-12-19
 */
namespace Common;

use Zend\Debug\Debug;

use Zend\View\Helper\Json;

use TeamWork\Model\Access;
use TeamWork\Model\Groups;
use TeamWork\Model\Resource;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;

class Manager extends AbstractActionController
{
	protected $RequestUrl;
	protected $controller;
	protected $module;
	protected $__CONTROLLER__;
	protected $action;
	protected $auth;
	protected $identity;
	protected $request;
	protected $view;
	protected $time;
	protected $AclControl;
	
	/**
	 * 系统初始化，预加载
	 *
	 * @author Zen
	 * @since 2012-12-19
	 */
	public function init()
	{
		//全局请求
		$this->request = $this->getRequest();
		//全局视图
		$this->view = $this->getEvent()->getViewModel();
		
		$this->time = $_SERVER['REQUEST_TIME'];
		//用户认证
		$this->auth = new AuthenticationService();

		$this->AclControl = new \Common\AccessControlList();
	}

	/**
	 * 设置事件监听
	 *
	 * @author Zen
	 * @since 2012-12-20
	 */
	protected function attachDefaultListeners()
	{
		$events = $this->getEventManager();
		$events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'));
		$events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'preDispatch'), 5);
		$events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'checkLog'), 4);
		$events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'AccessControlList'), 3);
	}

	/**
	 * 设置访问路径
	 *
	 * @author Zen
	 * @since 2012-12-20
	 */
	public function preDispatch()
	{
		$event = $this->getEvent();
		$routeMatch = $event->getRouteMatch();
		
		$this->controller = $routeMatch->getParam('controller');
		$module = explode('\\', $this->controller);
		$this->module = $module[0];
		$this->__CONTROLLER__ = $routeMatch->getParam('__CONTROLLER__');
		$this->action = $routeMatch->getParam('action');
		$this->RequestUrl = '/'.$this->module.'/'.$this->__CONTROLLER__.'/'.$this->action;
		
		$this->view->setVariable('RequestUrl', $this->RequestUrl);
	}

	/**
	 * 检查读取用户信息
	 *
	 * @author Zen
	 * @since 2012-12-20
	 */
	public function checkLog()
	{
		//启用后台模板
		$this->layout("layout/backstage");
		//检查是否存在认证信息
		if($this->auth->hasIdentity()) {
			//如果未存入过认证信息
			if(empty($this->identity)) {
				$this->identity = $this->auth->getIdentity();
			}
		} else {
			//否则跳转登陆
			return $this->redirect()->toRoute('Team/default',array('controller' => 'Login','action' => 'index'));
		}
	}
	
	/**
	 * 访问控制列表
	 * 
	 * @author Zen
	 * @since 2012-12-25
	 */
	public function AccessControlList()
	{
		//如果不存在资源或者角色，则初始化访问控制列表
		if(!$this->AclControl->_acl->getResources() || !$this->AclControl->_acl->getRoles()) {
			$Resource = new Resource();
			$module = $Resource->fetchRow(array('um_pid' => '0'));
			$controller = $Resource->fetchAll(array('um_pid' => $module->um_id));
			
			$groups = new Groups();
			$role = $groups->fetchAll();
			
			$access = new Access();
			$accesslist = $access->getAccess();
			
			$params = compact('module', 'controller', 'role', 'accesslist');
			
			//初始化访问控制列表
			$this->AclControl->setAcl($params);
			
			//手动释放资源
			unset($Resource, $groups, $access);
		}
		
		//获取当前用户组和操作位置
		$controller = $this->controller;
		$action = $this->action;
		$group = $this->identity->role;
		$AclResults = $this->AclControl->isAllowed($controller, $action, $group);
		
		//不可访问
		if(!$AclResults) {
			//如果不是ajax提交的跳转请求，则控制显示无权查看
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER["HTTP_X_REQUESTED_WITH"] == 'XMLHttpRequest' ) {
				echo '当前用户无操作权限';die;
			} else {
				//否则（url强制进入），跳转至登陆页面
				return $this->redirect()->toRoute('Team/default', array('controller' => 'Login','action' => 'index'));
			}
		} else {
			//可以访问：判断是否为ajax跳转，如果不是则视为URL强制进入随即跳转首页
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
				$this->view->setTerminal(TRUE);
			} else {
				if($_SERVER['REQUEST_URI'] != '/TeamWork/Index/index') {
					return $this->redirect()->toRoute('Team/default', array('controller' => 'Index', 'action' => 'index'));
				}
			}
		}
	}
	
	/**
	 * layout改变（暂留）
	 * 
	 * @param array
	 * @author Zen
	 * @since 2012-12-28
	 */
	public function ChangeLayoutContent($param)
	{
		$echo = "<script>";
		$echo .= "$('#messageCount').html(".$param['messaegCount'].")";
		$echo .= "</script>";
		echo $echo;
	}
}