<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Carbon\Carbon;

class Laptops extends Management_Controller {

  private $module_path = 'services/laptops';

  function __construct(){
    parent::__construct();

    $this->load->model('M_service_laptop');
  }

  public function index(){
    $data['extraJs'] 	= [
      'statics/app_common.js',
      'services/laptop_index.js'
    ];
    $data['page_title'] = "Services Laptop";
    $data['page_view'] 	= "V_service_laptop_index";
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

    // bila ada filter
    if($filter_post){
      if(isset($filter_post['keyword'])){
        $like += [
          'lower(l.name)' => strtolower($filter_post['keyword']),
        ];
      }
    }

    // join dengan tabel entity
    $join = [
      'laptop l'        => 'l.id = s.laptop_id',
      'master_entity e' => 'e.id = l.entity_id',
    ];

    $db_total = $this->M_service_laptop->get_count('service_laptop s', $where, $join, null, null, null, null, $like, 's.id');
    $db_data 	= $this->M_service_laptop->get('service_laptop s', $where, $join, null, ['s.service_start' => 'desc'], $limit, $offset, $like, 'l.id as laptop_id, l.name as laptop_name, l.code as laptop_code, e.name as entity_name, s.*');
    foreach($db_data as $i => $v) {

      $action = [
          '<a href="javascript:void(0)" class="btn btn-xs btn-primary btn-item-edit" data-id="' . $v->id . '"><i class="fa fa-eye"></i></a>',
          '<a href="javascript:void(0)" class="btn btn-xs btn-danger btn-item-delete" data-id="' . $v->id . '"><i class="fa fa-trash"></i></a>',
      ];

      // tanggal service
      $s_start  = Carbon::parse($v->service_start)->format('d/m/Y');
      $s_end    = ($v->service_end) ? '<span class="clearfix">Finished: '.Carbon::parse($v->service_end)->format('d/m/Y').'</span>' : null;
      $s_status = ($v->service_end) ? '<span class="label label-success">FINISHED</span>' : '<span class="label label-primary">ON PROGRESS</span>';

      // link untuk laptop
      $s_laptop = base_url('assets/laptop/edit/'.base64_encode($v->laptop_id));

      $table_content[] = [
        'laptop'    => '<b><a href="'.$s_laptop.'">'.$v->laptop_name.'</a></b>
                        <span class="clearfix">Barcode: '.$v->laptop_code.'</span>
                        <span class="clearfix">Entity: '.$v->entity_name.'</span>',
        'purposes'  => $v->purposes,
        'duration'  => '<span>Start: '.$s_start.'</span>
                        <span class="clearfix">'.$s_status.'</span>',
        'ticket'    => '<span>IT: '.$v->ticket_it.'</span>
                        <span class="clearfix">GA: '.$v->ticket_ga.'</span>',
        'action'	  => '<center><div class="btn-group">'.implode('', $action).'</div></center>',
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

    $this->form_validation->set_rules('entity_id', 'Entity', 'required|trim');
    $this->form_validation->set_rules('name', 'Name', 'required|trim');
    
    if ($this->form_validation->run()) {
        $post = $this->input->post();

        $data = [
					'name'      => $post['name'],
					'entity_id' => $post['entity_id'],
				];

			  if(! $post['id']){
					// log
          $data += [
            'created_by' => current_user_session('id'),
            'created_at' => Carbon::now(),
          ];

          $db = $this->M_service_laptop->insert(null, $data);
        } else {
					// log
          $data += [
            'updated_by' => current_user_session('id'),
            'updated_at' => Carbon::now(),
          ];

          $db = $this->M_service_laptop->update(null, ['id' => $post['id']], $data);
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
      $join = ['master_entity e' => 'e.id = l.entity_id AND e.deleted_at IS NULL'];
      
      $db 	= $this->M_service_laptop->get('master_service_laptop l', ['l.id' => $post['id']], $join, null, ['name' => 'asc'], null, null, null, 'l.id, l.name, e.id as entity_id, e.code as entity_code, e.name as entity_name');
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
			$db = $this->M_service_laptop->update(null, ['id' => $id], $data);
			if ($db) { $status = true; }
		}

		$this->output->set_content_type('application/json')->set_output(json_encode(compact('status', 'msg')));
	}

  function ajax_get_service_laptop(){
    $data   = [];
    $like   = [];
    $where  = ['l.deleted_at' => null, 'e.flag_active' => 1];
    $param  = $this->input->post('param');

    if($param){
      $like = [
        'lower(l.name)' => strtolower($param),
      ];
    }

    if($entity_id = $this->input->post('entity_id')){
      $where += ['e.id' => $entity_id];
    }

    // join dengan tabel entity
    $join = ['master_entity e' => 'e.id = l.entity_id AND e.deleted_at IS NULL'];
    $db   = $this->M_service_laptop->get('master_service_laptop l', $where, $join, null, ['l.name' => 'asc'], 100, null, $like, "l.id, l.name, e.code as entity_code");
    foreach($db as $i => $v){
      $data[] = ['id' => $v->id, 'text' => '('.$v->entity_code.') '.$v->name];
    }

    $this->output->set_content_type('application/json')->set_output(json_encode($data));
  }
}
