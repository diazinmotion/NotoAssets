<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Carbon\Carbon;

class Model extends Management_Controller {

  private $module_path = 'master/model';

  function __construct(){
    parent::__construct();

    $this->load->model('M_model');
  }

  public function index(){
    $data['extraJs'] 	= [
      'statics/app_common.js',
      'master/model_index.js'
    ];
    $data['page_title'] = "Master Models";
    $data['page_view'] 	= "V_model";
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
        'lower(l.name)' => strtolower($search['value']),
      ];
    }

    // parse data filter dari js menjadi format PHP
    parse_str($filter, $filter_post);

    // related to datatable

    // hanya dapatkan data yang belum dihapus
    $where = ['l.deleted_at' => null];

    // bila ada filter
    if($filter_post){
      if(isset($filter_post['keyword'])){
        $like += [
          'lower(l.name)' => strtolower($filter_post['keyword']),
        ];
      }

      if(isset($filter_post['brand_id'])){
        $where += ['l.brand_id' => $filter_post['brand_id']];
      }
    }

    // join dengan tabel entity
    $join = ['master_brand e' => 'e.id = l.brand_id AND e.deleted_at IS NULL'];

    $db_total = $this->M_model->get_count('master_model l', $where, $join, 'left', null, null, null, $like, 'l.id');
    $db_data 	= $this->M_model->get('master_model l', $where, $join, 'left', ['name' => 'asc'], $limit, $offset, $like, 'l.id, l.name, e.name as brand_name');
    foreach($db_data as $i => $v) {

      $action = [
          '<a href="javascript:void(0)" class="btn btn-xs btn-primary btn-item-edit" data-id="' . $v->id . '"><i class="fa fa-edit"></i></a>',
          '<a href="javascript:void(0)" class="btn btn-xs btn-danger btn-item-delete" data-id="' . $v->id . '"><i class="fa fa-trash"></i></a>',
      ];
      
      $table_content[] = [
        'name'    => $v->name,
        'brand'   => $v->brand_name,
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
    $status = false;
    $msg 		= [];

    $this->form_validation->set_rules('brand_id', 'Brand', 'required|trim');
    $this->form_validation->set_rules('name', 'Name', 'required|trim');
    
    if ($this->form_validation->run()) {
        $post = $this->input->post();

        $data = [
					'name'      => $post['name'],
					'brand_id'  => $post['brand_id'],
				];

			  if(! $post['id']){
					// log
          $data += [
            'created_by' => current_user_session('id'),
            'created_at' => Carbon::now(),
          ];

          $db = $this->M_model->insert(null, $data);
        } else {
					// log
          $data += [
            'updated_by' => current_user_session('id'),
            'updated_at' => Carbon::now(),
          ];

          $db = $this->M_model->update(null, ['id' => $post['id']], $data);
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
      $join = ['master_brand e' => 'e.id = l.brand_id AND e.deleted_at IS NULL'];      
      $db 	= $this->M_model->get('master_model l', ['l.id' => $post['id']], $join, 'left', ['name' => 'asc'], null, null, null, "l.id, l.name, e.name as brand_name");
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
			];
			$db = $this->M_model->update(null, ['id' => $id], $data);
			if ($db) { $status = true; }
		}

		$this->output->set_content_type('application/json')->set_output(json_encode(compact('status', 'msg')));
	}

  function ajax_get_model(){
    $data   = [];
    $like   = [];
    $where  = ['l.deleted_at' => null];
    $param  = $this->input->post('param');

    if($param){
      $like = [
        'lower(l.name)' => strtolower($param),
        'lower(e.name)' => strtolower($param),
      ];
    }

    // join dengan tabel entity
    $join = ['master_brand e' => 'e.id = l.brand_id AND e.deleted_at IS NULL'];
    $db   = $this->M_model->get('master_model l', $where, $join, 'left', ['l.name' => 'asc'], 100, null, $like, "l.id, l.name, e.name as brand_name");
    foreach($db as $i => $v){
      $data[] = ['id' => $v->id, 'text' => $v->brand_name.' '.$v->name];
    }

    $this->output->set_content_type('application/json')->set_output(json_encode($data));
  }
}
