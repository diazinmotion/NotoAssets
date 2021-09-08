<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Carbon\Carbon;

class Home extends Management_Controller {

	function __construct(){
		parent::__construct();
	}

	public function index(){
		$data['page_title'] = "Dashboard";
		$data['page_view'] 	= "V_index";
		
		$this->load->view('layouts/cms/V_master', $data);
	}
}
