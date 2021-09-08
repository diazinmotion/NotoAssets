<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Carbon\Carbon;

class Software extends Management_Controller {

  private $module_path = 'master/software';

  function __construct(){
    parent::__construct();

    $this->load->model('M_software');
  }

  public function index(){
    $data['extraJs'] 	= [
      'statics/app_common.js',
      'master/software_index.js'
    ];
    $data['page_title'] = "Master Software Item";
    $data['page_view'] 	= "V_software";
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
      if(isset($filter_post['keyword'])){
        $like += [
          'lower(name)' => strtolower($filter_post['keyword']),
        ];
      }
    }

    $db_total = $this->M_software->get_count(null, $where, null, null, null, null, null, $like, 'id');
    $db_data 	= $this->M_software->get(null, $where, null, null, ['name' => 'asc'], $limit, $offset, $like, 'id, name');
    foreach($db_data as $i => $v) {

      $action = [
          '<a href="javascript:void(0)" class="btn btn-xs btn-primary btn-item-edit" data-id="' . $v->id . '"><i class="fa fa-edit"></i></a>',
          '<a href="javascript:void(0)" class="btn btn-xs btn-danger btn-item-delete" data-id="' . $v->id . '"><i class="fa fa-trash"></i></a>',
      ];
      
      $table_content[] = [
        'name'    => $v->name,
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

    $this->form_validation->set_rules('name', 'Name', 'required|trim');
    
    if ($this->form_validation->run()) {
        $post = $this->input->post();

        $data = [
					'name' => $post['name'],
				];

			  if(! $post['id']){
					// log
          $data += [
            'created_by' => current_user_session('id'),
            'created_at' => Carbon::now(),
          ];

          $db = $this->M_software->insert(null, $data);
        } else {
					// log
          $data += [
            'updated_by' => current_user_session('id'),
            'updated_at' => Carbon::now(),
          ];

          $db = $this->M_software->update(null, ['id' => $post['id']], $data);
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
			$db	 	= $this->M_software->get(null, ['id' => $post['id']]);
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
			$db = $this->M_software->update(null, ['id' => $id], $data);
			if ($db) { $status = true; }
		}

		$this->output->set_content_type('application/json')->set_output(json_encode(compact('status', 'msg')));
	}

  function ajax_get_software(){
    $data   = [];
    $like   = [];
    $where  = ['deleted_at' => null];
    $param  = $this->input->post('param');

    if($param){
      $like = [
        'lower(name)' => strtolower($param),
      ];
    }

    if($exclude = $this->input->post('exclude')){
      $where += ['id NOT IN ('.implode(',', $exclude).')' => null];
    }

    $db = $this->M_software->get(null, $where, null, null, ['name' => 'asc'], 100, null, $like, "id, name");
    foreach($db as $i => $v){
      $data[] = ['id' => $v->id, 'text' => $v->name];
    }

    $this->output->set_content_type('application/json')->set_output(json_encode($data));
  }
}
