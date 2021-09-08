<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Carbon\Carbon;

class Entity extends Management_Controller {

  private $module_path = 'master/entity';

  function __construct(){
    parent::__construct();

    $this->load->model('M_entity');
  }

  public function index(){
    $data['extraJs'] 	= [
      'statics/app_common.js',
      'master/entity_index.js'
    ];
    $data['page_title'] = "Master Entity";
    $data['page_view'] 	= "V_entity";
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
        'lower(code)' => strtolower($search['value']),
        'lower(name)' => strtolower($search['value']),
      ];
    }

    // parse data filter dari js menjadi format PHP
    parse_str($filter, $filter_post);

    // related to datatable

    // hanya dapatkan data yang belum dihapus
    $where = ['deleted_at' => null];

    // bila ada filter
    if($filter_post){
      if(isset($filter_post['status'])){
        $where += ['flag_active' => $filter_post['status']];
      }
    }

    $db_total = $this->M_entity->get_count(null, $where, null, null, null, null, null, $like, 'id');
    $db_data 	= $this->M_entity->get(null, $where, null, null, ['name' => 'asc'], $limit, $offset, $like, 'id, code, name, flag_active');
    foreach($db_data as $i => $v) {

      $action = [
          '<a href="javascript:void(0)" class="btn btn-xs btn-primary btn-item-edit" data-id="' . $v->id . '"><i class="fa fa-edit"></i></a>',
          '<a href="javascript:void(0)" class="btn btn-xs btn-danger btn-item-delete" data-id="' . $v->id . '"><i class="fa fa-trash"></i></a>',
      ];

      $s_status = ($v->flag_active == 1) ? '<span class="label label-success">ACTIVE</span>' : '<span class="label label-danger">INACTIVE</span>';
      
      $table_content[] = [
        'code'    => '<center>'.$v->code.'</center>',
        'name'    => $v->name,
        'status'	=> '<center>'.$s_status.'</center>',
        'action'	=> '<center><div class="btn-group">'.implode('', $action).'</div></center>',
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

    $this->form_validation->set_rules('code', 'Code', 'required|trim');
    $this->form_validation->set_rules('name', 'Name', 'required|trim');
    $this->form_validation->set_rules('status', 'Status', 'required|trim');
    
    if ($this->form_validation->run()) {
        $post = $this->input->post();

        $data = [
					'code'				=> $post['code'],
					'name'				=> $post['name'],
					'flag_active'	=> $post['status'],
				];

				// cek apakah kode ini sudah ada?
				$exist = $this->M_entity->get(null, ['code' => $post['code'], 'deleted_at' => null]);

        if(! $post['id']){
					if(!$exist){
						// log
						$data += [
							'created_by' => current_user_session('id'),
							'created_at' => Carbon::now(),
						];

						$db = $this->M_entity->insert(null, $data);
					}else{
						$msg = 'This code has been used. Please use another code';
					}
        } else {
					// cek apakah kode ini sudah ada?
					$current_data = $this->M_entity->get(null, ['id' => $post['id']]);
					if($current_data[0]->code == $post['code'] || ! $exist){
						// log
						$data += [
							'updated_by' => current_user_session('id'),
							'updated_at' => Carbon::now(),
						];

						$db = $this->M_entity->update(null, ['id' => $post['id']], $data);
					}else{
						$msg = 'This code has been used. Please use another code';
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
			$db	 	= $this->M_entity->get(null, ['id' => $post['id']]);
			if ($db) {
					$status = true;
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
				'flag_active'	=> 0,
			];
			$db = $this->M_entity->update(null, ['id' => $id], $data);
			if ($db) { $status = true; }
		}

		$this->output->set_content_type('application/json')->set_output(json_encode(compact('status', 'msg')));
	}

  function ajax_get_entity(){
    $data   = [];
    $like   = [];
    $where  = ['deleted_at' => null];
    $param  = $this->input->post('param');

    if($param){
      $like = [
        'lower(code)' => strtolower($param),
        'lower(name)' => strtolower($param),
      ];
    }

    $db = $this->M_entity->get(null, $where, null, null, ['name' => 'asc'], 100, null, $like, "id, code, name");
    foreach($db as $i => $v){
      $data[] = ['id' => $v->id, 'text' => '('.$v->code.') '.$v->name];
    }

    $this->output->set_content_type('application/json')->set_output(json_encode($data));
  }
}
