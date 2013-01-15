<?php
namespace TeamWork\Controller;

use Zend\Debug\Debug;
use Common\Manager;

class CenterController extends Manager
{
	public function __construct()
	{
		parent::init();
	}
	
	public function indexAction()
	{
		die('Center');
	}
}
