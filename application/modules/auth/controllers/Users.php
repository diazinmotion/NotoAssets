<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Carbon\Carbon;

class Users extends Management_Controller {

  private $module_path = 'auth/users';

  function __construct(){
    parent::__construct();

    $this->load->model('M_users');
  }

  public function index(){
    $data['extraJs'] 	= [
      'statics/app_common.js',
      'auth/users_index.js'
    ];
    $data['page_title'] = "Users Management";
    $data['page_view'] 	= "V_users";
    $data['module_url']	= base_url($this->module_path);
    
    $this->load->view('layouts/cms/V_master', $data);
  }

  function ajax_module_index(){
    $like  					= [];
    $where  				= [];
    $action 				= [];
    $table_content 	= [];

    // related to datatable
    $offset = $this->input->post('start');
    $limit  = $this->input->post('length');
    $draw   = $this->input->post('draw');
    $order  = $this->input->post('order');
    $filter = $this->input->post('filter');

    $search = $this->input->post('search');
    if($search && $search['value']){
      $like = [
        'lower(email)'      => strtolower($search['value']),
        'lower(full_name)'  => strtolower($search['value']),
      ];
    }

    // parse data filter dari js menjadi format PHP
    parse_str($filter, $filter_post);

    // related to datatable

    // hanya dapatkan data yang belum dihapus
    $where = ['deleted_at' => null];

    // bila ada filter
    if($filter_post){
      if(isset($filter_post['keyword'])){
        $like += [
          'lower(email)'      => strtolower($search['value']),
          'lower(full_name)'  => strtolower($filter_post['keyword']),
        ];
      }
    }

    $db_total = $this->M_users->get_count(null, $where, null, null, null, null, null, $like, 'id');
    $db_data 	= $this->M_users->get(null, $where, null, null, ['full_name' => 'asc'], $limit, $offset, $like);
    foreach($db_data as $i => $v) {

      $action = [
          '<a href="javascript:void(0)" class="btn btn-xs btn-primary btn-item-edit" data-id="' . $v->id . '"><i class="fa fa-edit"></i></a>',
          '<a href="javascript:void(0)" class="btn btn-xs btn-danger btn-item-delete" data-id="' . $v->id . '"><i class="fa fa-trash"></i></a>',
      ];

      // bila user id sama dengan session, maka hapus action hapus
      if(current_user_session('id') == $v->id){
        unset($action[1]);
      }

      $s_role   = ($v->flag_super_admin == 1) ? '<span class="label label-primary">ADMIN</span>' : '<span class="label label-info">USER</span>';
      $s_status = ($v->flag_allowed == 1) ? '<span class="label label-success"><i class="fa fa-check fa-fw"></i></span>' : '<span class="label label-danger"><i class="fa fa-times fa-fw"></i></span>';
      
      $table_content[] = [
        'name'        => '<b>'.$v->full_name.'</b><small class="clearfix">Email: '.$v->email.'</span>',
        'status'      => '<center>'.$s_role.'</center>',
        'allowance'   => '<center>'.$s_status.'</center>',
        'last_login'  => '<center>'.Carbon::parse($v->last_login)->diffForHumans().'</center>',
        'action'	    => '<center><div class="btn-group">'.implode('', $action).'</div></center>',
      ];
    }

    $result = [
      "draw" 						=> $draw,
      "recordsTotal" 		=> ($db_total) ? $db_total->total : 0,
      "recordsFiltered" => ($db_total) ? $db_total->total : 0,
      "data" 						=> $table_content,
    ];

    $this->output->set_content_type('application/json')->set_output(json_encode($result));

  }

  function ajax_post_form(){
    $db     = false;
    $status = false;
    $msg 		= [];

    $this->form_validation->set_rules('name', 'Full Name', 'required|trim');
    $this->form_validation->set_rules('email', 'Email', 'required|trim');
    $this->form_validation->set_rules('role', 'Role', 'required|trim');
    $this->form_validation->set_rules('status', 'Login Allowance', 'required|trim');
    
    if ($this->form_validation->run()) {
        $post = $this->input->post();

        $data = [
					'full_name'         => $post['name'],
					'email'             => $post['email'],
					'flag_super_admin'  => $post['role'],
					'flag_allowed'      => $post['status'],
				];

        // cek apakah ada password dan password confirmation?
        if($post['password'] && $post['password_confirm'] && ($post['password'] == $post['password_confirm'])){
          $hashed = hash_hmac("sha256", $password, PK_SECRET);
          $data   += [
            'password' => password_hash($hashed, PASSWORD_BCRYPT)
          ];
        }

        // cek apakah kode ini sudah ada?
				$exist = $this->M_users->get(null, ['email' => $post['email'], 'deleted_at' => null]);

			  if(! $post['id']){
          if(!$exist){
            // cek apakah ada password?
            if(! $post['password'] || ($post['password'] != $post['password_confirm'])){
              $msg = 'Please fill the password for this user';
            }else{
              // log
              $data += [
                'created_at' => Carbon::now(),
              ];

              $db = $this->M_users->insert(null, $data);
            }
          }else{
						$msg = 'This email has been used. Please use another email';
          }
        } else {
          // cek apakah kode ini sudah ada?
					$current_data = $this->M_users->get(null, ['id' => $post['id']]);
					if($current_data[0]->email == $post['email'] || ! $exist){
            // log
            $data += [
              'updated_at' => Carbon::now(),
            ];

            $db = $this->M_users->update(null, ['id' => $post['id']], $data);
          }else{
						$msg = 'This email has been used. Please use another email';
          }
        }

        if ($db) { $status = true; }
    } else {
        $msg = str_replace(['<p>', '</p>'], [null, '<br/>'], validation_errors());
    }

    $this->output->set_content_type('application/json')->set_output(json_encode(compact('status', 'msg')));
  }

	function ajax_get_item(){
		$status = false;
		$data 	= [];

		$this->form_validation->set_rules('id', 'ID', 'required|trim');

		if ($this->form_validation->run()) {
			$post = $this->input->post();
			$db	 	= $this->M_users->get(null, ['id' => $post['id']]);
			if ($db) {
					$status = true;

          // unset password dan semua field yang tidak dibutuhkan
          unset(
            $db[0]->password,
            $db[0]->user_agent
          );

					$data 	= $db[0];
			}
		} else {
				$data = str_replace(['<p>', '</p>'], [null, '<br/>'], validation_errors());
		}

		$this->output->set_content_type('application/json')->set_output(json_encode(compact('status', 'data')));
	}

	function ajax_delete_item(){
		$status = false;
		$msg 		= [];

		if($id = $this->input->post('id')){
			$data = [
				'deleted_at'  => date('Y-m-d H:i:s'),
				'deleted_by'  => current_user_session('id'),
			];
			$db = $this->M_users->update(null, ['id' => $id], $data);
			if ($db) { $status = true; }
		}

		$this->output->set_content_type('application/json')->set_output(json_encode(compact('status', 'msg')));
	}
}
