<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends Landing_Controller {

	function __construct(){
		parent::__construct();

		$this->load->model('M_auth');
	}

	public function index(){
		$is_valid 	= false;
		$msg 				= null;

		if($this->input->post()){
			// inputan user
			$email 		= $this->input->post('email');
			$password = $this->input->post('password');

			$this->form_validation->set_rules('email', 'Email', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');

			if($this->form_validation->run()){

				// verifikasi username dan login
				$db = $this->M_auth->get_login($email);
				if($db){
					// INFO: KOMBINASI: RAW_PASS +  KEY
					$hashed = hash_hmac("sha256", $password, PK_SECRET);
					if(password_verify($hashed, $db->password)){
						// update data meta login utk user ini
						$this->M_auth->update_meta_login($db->id);

						// set session untuk user ini
						$data = [
							'id'							=> $db->id,
							'email'						=> $db->email,
							'full_name'				=> $db->full_name,
							'last_login'			=> $db->last_login,
							'is_super_admin'	=> ($db->flag_super_admin == 1) ? true : false,
						];
						
						$this->session->set_userdata(APP_SESSION_NAME, $data);
						$is_valid = true;
					}else{
						$msg = 'Invalid email or password.';
					}
				}else{
					$msg = 'No user found associated with this email.';
				}

				if($is_valid){
					redirect('/');
				}else{
					// redirect ke login
					$this->session->set_flashdata('message', $msg);
					redirect('auth');
				}
			}else{
				$this->session->set_flashdata('message', validation_errors());
				redirect('auth');
			}
		}else{
			// tampilkan halaman login
			if(! $this->session->userdata(APP_SESSION_NAME)){
				// belum login
				$data['page_view'] = "V_index";

				$this->load->view('layouts/landing/V_master', $data);
			}else{
				redirect('/');
			}
		}
	}

	public function logout(){
		$this->session->sess_destroy();
    redirect('/');
	}
}
