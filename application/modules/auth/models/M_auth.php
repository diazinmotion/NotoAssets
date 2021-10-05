<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Carbon\Carbon;

class M_auth extends MY_Model {

	public function __construct(){
		parent::__construct();

		$this->tabel    = 'users';
    $this->seq_name = 'id_user_loket_seq';
	}

	function get_users(){
		$this->db->select('COUNT(id) as total');
		$q = $this->db->get($this->tabel);

		return ($q) ? $q->row() : false;
	}

	function get_login($email = null){
		$this->db->select('id, email, password, full_name, flag_super_admin, flag_allowed, last_login');
		$this->db->where([
			'email' 				=> $email,
			'deleted_at'		=> null,
			'flag_allowed'	=> 1
		]);
		$this->db->limit(1);

		$q = $this->db->get($this->tabel);

		return ($q) ? $q->row() : false;
	}

	function update_meta_login($id_user = null){

		$data = [
			'last_login'	=> Carbon::now(),
			'user_agent'	=> $this->agent->agent_string(),
		];

		$q = $this->db->update($this->tabel, $data);

		return ($q) ? true : false;
	}
}