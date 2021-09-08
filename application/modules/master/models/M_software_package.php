<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_software_package extends MY_Model {

	public function __construct(){
		parent::__construct();

		$this->tabel = 'master_software_package';
	}
}