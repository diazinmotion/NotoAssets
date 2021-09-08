<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_laptop extends MY_Model {

	public function __construct(){
		parent::__construct();

		$this->tabel = 'laptop';
	}
}