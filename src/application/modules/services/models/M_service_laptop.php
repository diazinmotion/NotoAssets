<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_service_laptop extends MY_Model {

	public function __construct(){
		parent::__construct();

		$this->tabel = 'service_laptop';
	}
}