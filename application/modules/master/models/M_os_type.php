<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_os_type extends MY_Model {

	public function __construct(){
		parent::__construct();

		$this->tabel = 'master_os';
	}
}