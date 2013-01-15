<?php
namespace TeamWork\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Debug\Debug;
use Zend\Json\Json;
use Common\Grandmaster;

class LoginController extends AbstractActionController 
{
	protected $view;
	protected $auth;
	
	public function __construct()
	{
		$this->view = $this->getEvent()->getViewModel();
		$this->auth = new AuthenticationService();
	}
	
	public function indexAction()
	{
 		$this->layout('layout/login');
 		$this->view->terminate(true);

 		$request = $this->getRequest();
		if ($request->isPost()) {
			$adapter = new Grandmaster();
			$adapter->getAdapter();
			$data = $request->getPost();
			$authAdapter = new AuthAdapter($adapter->adapter,'teamuser','EndUserName','EndUserPwd','md5(?)');
			$authAdapter->setIdentity($data->EndUserName)
								 ->setCredential($data->EndUserPwd);

			$result = $this->auth->authenticate($authAdapter);
			$response = $result->getCode();
			
			$ret = array();
			$ret['retcode'] = $result->getCode();
			$ret['message'] = '';
			if($response == 1) {
				$columnsToOmit = array('EndUserPwd');
				$this->auth->getStorage()->write($authAdapter->getResultRowObject(null,$columnsToOmit));
			} else if($response == -1) {
				$ret['message'] = '用户不存在';
			} else if($response == -3) {
				$ret['message'] = '用户名或密码不正确';
			}
			$json = new Json();
			$ret = $json->encode($ret);
			echo $ret;die;
		}
	}
	
	public function logoutAction()
	{
		$this->view->setTerminal(TRUE);
		
		if($this->auth->hasIdentity()) {
			$this->auth->clearIdentity();
			echo 1;
		} else {
			echo 0;
		}
		die;
	}
}