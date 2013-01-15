<?php
namespace TeamWork\Controller;

use Zend\Debug\Debug;
use Common\Manager;
use TeamWork\Model\News;
use TeamWork\Model\Newsclassify;
use TeamWork\Model\Health;
use TeamWork\Model\Healthclassify;
use TeamWork\Model\Supplier;

class WebsiteController extends Manager
{
	protected $news;
	protected $newsclassify;
	protected $health;
	protected $healthclassify;
	
	public function __construct()
	{
		parent::init();
		$this->news = new News();
		$this->newsclassify = new Newsclassify();
		$this->health = new Health();
		$this->healthclassify = new Healthclassify();
	}

	/**
	 * 新闻列表
	 *
	 * @author peter
	 * @since 2012-12-24
	 */
	public function looknewsAction()
	{
		$news = $this->news->fetchAll();
		$newsclassify = $this->newsclassify->fetchAll();
		
		$array = array();
		if(isset($newsclassify) && !empty($newsclassify)) {
			foreach ($newsclassify as $k=>$v) {
				$v = (array)$v;
				$array[$v['id']] = $v['NCName'];
				
			}
		}
		$this->view->setVariable('newsclassify', $array);
		$this->view->setVariable('admin', $this->auth->getIdentity()->EndUserName);
		$this->view->setVariable('news', $news);
		return $this->view;
	}
	
	/**
	 * 添加/编辑新闻
	 *
	 * @author peter
	 * @since 2012-12-24
	 */
	public function insetnewsAction()
	{     
		if(isset($_GET['Nid'])) {
			$where = "Nid = ".$_GET['Nid'];
			$this->view->setVariable('admin', $this->auth->getIdentity()->EndUserName);
			$this->view->setVariable('newsclassify', $this->newsclassify->fetchAll());
			$this->view->setVariable('news', $this->news->fetchRow($where));
		}
		
		if ($this->request->isPost()) {
			$params = $this->request->getPost();
			$data = array();
			$data['Ntime'] = date('Y-m-d,H:i:s');
			$data['Ntitle'] = $params->Ntitle;
			$data['Ncontent'] = $params->Ncontent;
			$data['NCID'] = $params->NCID;
			if(isset($params->Nid)&&!empty($params->Nid)) {
				$row = $this->news->update($data,' Nid ='.$params->Nid);
			} else {
				$row = $this->news->save($data);
			}
			$ret = array();
			$ret['code'] = 0;
			$ret['message'] = '';
			if($row ==true) {
				$ret['code'] = 1;
				$ret['message'] = '添加成功！';
			} elseif($row == 0 || $row < 0) {
				$ret['code'] = -1;
				$ret['message'] = '添加失败！';
			}
			echo json_encode($ret);die;
		}
		$this->view->setVariable('admin', $this->auth->getIdentity()->EndUserName);
		$this->view->setVariable('newsclassify', $this->newsclassify->fetchAll());
		return $this->view;
	}
	
	
	
	/**
	 * 新闻删除
	 *
	 * @author peter
	 * @since 2012-12-24
	 */
	public function deletenewsAction()
	{
		if(isset($_GET['Nid'])) {
			$where = "Nid = ".$_GET['Nid'];
			$row =$this->news->delete($where);
			if($row ==true) {
				$ret = '删除成功！';
			} elseif($row == 0 || $row < 0) {
				$ret = '删除失败！';
			}
			echo json_encode($ret);die;
		}
	}
	
	/**
	 * 新闻展示
	 *
	 * @author peter
	 * @since 2012-12-24
	 */
	public function shownewsAction()
	{
		if(isset($_GET['Nid']) && !empty($_GET['Nid'])) {	
			$where = "Nid = ".$_GET['Nid']; 
			$news = $this->news->fetchRow($where);
			$this->view->setVariable('admin', $this->auth->getIdentity()->EndUserName);
			$this->view->setVariable('news', $news);
		}
		return $this->view;
	}
	
	/**
	 * 添加/编辑 新闻分类
	 *
	 * @author peter
	 * @since 2012-12-24
	 */
	public function insetnewsclassifyAction()
	{
		if(isset($_GET['id']) && !empty($_GET['id'])) {
			$where = "id = ".$_GET['id'];
			$newsclassify=$this->newsclassify->fetchRow($where);
			$this->view->setVariable('admin', $this->auth->getIdentity()->EndUserName);
			$this->view->setVariable('news', $newsclassify);	
		}
   		$request = $this->getRequest ();
   		if ($request->isPost ()) {
   			$params = $this->request->getPost();
   			$data = array();
   			$data['NCName'] = $params->NCName;
   			if(isset($params->id)&&!empty($params->id)) {
   				$row = $this->newsclassify->update($data,' id ='.$params->id);
   			} else {
   				$row = $this->newsclassify->save($data);
   			}
   			$ret = array();
   			$ret['code'] = 0;
   			$ret['message'] = '';
   			if($row ==true) {
   				$ret['code'] = 1;
   				$ret['message'] = '添加成功！';
   			} elseif($row == 0 || $row < 0) {
   				$ret['code'] = -1;
   				$ret['message'] = '添加失败！';
   			}
   			echo json_encode($ret);die;
   		}
   		$this->view->setVariable('admin', $this->auth->getIdentity()->EndUserName);
   		return $this->view;
	}
	
	/**
	 * 新闻分类列表
	 *
	 * @author peter
	 * @since 2012-12-24
	 */
	public function looknewsclassifyAction()
	{
		$newsclassify=$this->newsclassify->fetchAll();
		$this->view->setVariable('admin', $this->auth->getIdentity()->EndUserName);
		$this->view->setVariable('newsclassify', $newsclassify);
		return $this->view;
	}
	
	/**
	 * 新闻分类删除
	 *
	 * @author peter
	 * @since 2012-12-24
	 */
	public function deletenewsclassifyAction()
	{
		if(isset($_GET['id'])) {
			$where = "id = ".$_GET['id'];
			$row =$this->newsclassify->delete($where);
			if($row ==true) {
				$ret = '删除成功！';
			} elseif($row == 0 || $row < 0) {
				$ret = '删除失败！';
			}
			echo json_encode($ret);die;
		}
	}
	
	/**
	 * 常识列表
	 *
	 * @author peter
	 * @since 2012-1-7
	 */
	public function lookhealthAction()
	{
		$health = $this->health->fetchAll();
		$healthclassify = $this->healthclassify->fetchAll();
		$array = array();
		if(isset($healthclassify) && !empty($healthclassify)) {
			foreach ($healthclassify as $k=>$v) {
				$v = (array)$v;
				$array[$v['id']] = $v['Hcname'];
			}
		}
		$this->view->setVariable('healthclassify', $array);
		$this->view->setVariable('admin', $this->auth->getIdentity()->EndUserName);
		$this->view->setVariable('health', $health);
		return $this->view;
	}
	
	/**
	 * 添加/编辑常识
	 *
	 * @author peter
	 * @since 2012-1-7
	 */
	public function insethealthAction()
	{
		if(isset($_GET['Hid'])) {
			$where=" Hid = ".$_GET['Hid'];
			$this->view->setVariable('admin', $this->auth->getIdentity()->EndUserName);
			$this->view->setVariable('healthclassify', $this->healthclassify->fetchAll());
			$this->view->setVariable('health', $this->health->fetchRow($where));
		}
	
		if ($this->request->isPost()) {
			$params = $this->request->getPost();
			$data = array();
		
			$data['Htime'] = date('Y-m-d,H:i:s');
			$data['Htitle'] = $params->Htitle;
			$data['Hcontent'] = $params->Hcontent;
			$data['HCID'] = $params->HCID;
			if(isset($params->Hid)&&!empty($params->Hid)) {
				$row = $this->health->update($data,' Hid ='.$params->Hid);
			} else {
				$row = $this->health->save($data);
			}
			
			$ret = array();
			$ret['code'] = 0;
			$ret['message'] = '';
			if($row ==true) {
				$ret['code'] = 1;
				$ret['message'] = '添加成功！';
			} elseif($row == 0 || $row < 0) {
				$ret['code'] = -1;
				$ret['message'] = '添加失败！';
			}
			echo json_encode($ret);die;
				
		}
		$this->view->setVariable('admin', $this->auth->getIdentity()->EndUserName);
		$this->view->setVariable('healthclassify', $this->healthclassify->fetchAll());
		return $this->view;
	}
	
	
	
	/**
	 * 常识删除
	 *
	 * @author peter
	 * @since 2012-12-24
	 */
	public function deletehealthAction()
	{
		if(isset($_GET['Hid'])) {
			$where = "Hid = ".$_GET['Hid'];
			$row =$this->health->delete($where);
			if($row ==true) {
				$ret = '删除成功！';
			} elseif($row == 0 || $row < 0) {
				$ret = '删除失败！';
			}
			echo json_encode($ret);die;
		}
	}
	
	/**
	 * 常识展示
	 *
	 * @author peter
	 * @since 2012-12-24
	 */
	public function showhealthAction()
	{
		if(isset($_GET['Hid']) && !empty($_GET['Hid'])) {
			$where = "Hid = ".$_GET['Hid'];
			$health = $this->health->fetchRow($where);
			$this->view->setVariable('admin', $this->auth->getIdentity()->EndUserName);
			$this->view->setVariable('health', $health);
		}
		return $this->view;
	}
	
	/**
	 * 添加/编辑 常识分类
	 *
	 * @author peter
	 * @since 2012-12-24
	 */
	public function insethealthclassifyAction()
	{
		if(isset($_GET['id']) && !empty($_GET['id'])) {
			$where=" id = ".$_GET['id'];
			$healthclassify=$this->healthclassify->fetchRow($where);
			$this->view->setVariable('admin', $this->auth->getIdentity()->EndUserName);
			$this->view->setVariable('health', $healthclassify);
		}
		
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$params = $this->request->getPost();
			$data = array();
			$data['Hcname'] = $params->Hcname;
			if(isset($params->id)&&!empty($params->id)){
				$row = $this->healthclassify->update($data,' id ='.$params->id);
			}else{
				$row = $this->healthclassify->save($data);
			}
			
			$ret = array();
			$ret['code'] = 0;
			$ret['message'] = '';
			if($row ==true){
				$ret['code'] = 1;
				$ret['message'] = '添加成功！';
			}elseif($row == 0 || $row < 0) {
				$ret['code'] = -1;
				$ret['message'] = '添加失败！';
			}
			echo json_encode($ret);die;
		}
		$this->view->setVariable('admin', $this->auth->getIdentity()->EndUserName);
		return $this->view;
	}
	
	/**
	 * 新闻分类列表
	 *
	 * @author peter
	 * @since 2012-12-24
	 */
	public function lookhealthclassifyAction()
	{
		$healthclassify=$this->healthclassify->fetchAll();
		$this->view->setVariable('admin', $this->auth->getIdentity()->EndUserName);
		$this->view->setVariable('healthclassify', $healthclassify);
		return $this->view;
	}
	
	/**
	 * 新闻分类删除
	 *
	 * @author peter
	 * @since 2012-12-24
	 */
	public function deletehealthclassifyAction()
	{
		if(isset($_GET['id'])) {
			$where = "id = ".$_GET['id'];
			$row =$this->healthclassify->delete($where);
			if($row ==true) {
				$ret = '删除成功！';
			} elseif($row == 0 || $row < 0) {
				$ret = '删除失败！';
			}
			echo json_encode($ret);die;
		}
	}
}