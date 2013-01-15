<?php
namespace TeamWork\Controller;

use TeamWork\Model\Supplier;
use Common\Manager;

class FirmController extends Manager
{
	protected $supplier;
	public function __construct()
	{
		parent::init();
		$this->supplier = new Supplier();
	}
	
	public function indexAction()
	{
		
	}

	/**
	 * 厂商列表
	 *
	 * @author peter
	 * @since 2013-1-11
	 */
	public function supplierAction()
	{
		$supplier=$this->supplier->fetchAll();
		$this->view->setVariable('admin', $this->auth->getIdentity()->EndUserName);
		$this->view->setVariable('supplier', $supplier);
		return $this->view;
	
	}
	
	/**
	 * 添加/编辑厂商
	 *
	 * @author peter
	 * @since 2013-1-11
	 */
	public function insetsupplierAction()
	{
		if(isset($_GET['id']) && !empty($_GET['id'])) {
			$where=" id = ".$_GET['id'];
			$supplier=$this->supplier->fetchRow($where);
			$this->view->setVariable('admin', $this->auth->getIdentity()->EndUserName);
			$this->view->setVariable('supplier', $supplier);
		}
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$params = $this->request->getPost();
			$data = array();
			$data['name'] = $params->name;
			$data['address'] = $params->address;
			$data['postcode'] = $params->postcode;
			$data['phone'] = $params->phone;
			$data['fax'] = $params->fax;
			$data['main_principal'] = $params->main_principal;
			$data['url'] = $params->url;
			$data['business_linkman'] = $params->business_linkman;
			$data['email'] = $params->email;
			$data['retister_capital'] = $params->retister_capital;
			$data['floor_space'] = $params->floor_space;
			$data['established_time'] = $params->established_time;
			$data['turnover'] = $params->turnover;
			$data['credit_rating'] = $params->credit_rating;
			$data['equipment'] = $params->equipment;
			$data['manpower_resource'] = $params->manpower_resource;
			$data['main_product'] = $params->main_product;
			$data['main_production_equipment'] = $params->main_production_equipment;
			$data['main_production_craft'] = $params->main_production_craft;
			$data['main_test_facility'] = $params->main_test_facility;
			$data['main_detection_facility'] = $params->main_detection_facility;
			$data['month_supply'] = $params->month_supply;
			$data['delivery_cycle'] = $params->delivery_cycle;
			$data['client_group'] = $params->client_group;
			$data['proportion'] = $params->proportion;
			$data['clearing_form'] = $params->clearing_form;
			$data['any_business'] = $params->any_business;
			if(isset($params->id)&&!empty($params->id)){
				$row = $this->supplier->update($data,' id ='.$params->id);
			}else{
				$row = $this->supplier->save($data);
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
	
	public function deviceAction()
	{
		return $this->view;
	}
}