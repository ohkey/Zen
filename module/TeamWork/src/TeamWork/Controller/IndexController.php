<?php
namespace TeamWork\Controller;

use Zend\Debug\Debug;

use Common\Manager;
use Zend\Authentication\AuthenticationService;
use TeamWork\Model\Resource;

class IndexController extends Manager
{	
	public function __construct()
	{
		parent::init();		
		$this->auth = new AuthenticationService();
	}
	
	/**
	 * 网站配置
	 *
	 * @author Zen
	 * @since 2012-12-24
	 */
	public function indexAction()
	{
		$rs = new Resource();
		$currentMenu = $rs->getMenu($this->AclControl, $this->controller, $this->action, $this->identity->role);

		$userInfo = array();
		$userInfo['username'] = $this->auth->getIdentity()->EndUserName;
		
		$webinfo = array();
		$webinfo['title'] = '管理系统';
		
		//website信息
		$this->layout()->Webinfo = $webinfo;
		//当前用户菜单
		$this->layout()->menu = $currentMenu;
		//当前用户信息
		$this->layout()->userInfo = $userInfo;
		
		$this->view->setVariable('userInfo', $this->auth->getIdentity()->EndUserName);
		return $this->view;
	}

}