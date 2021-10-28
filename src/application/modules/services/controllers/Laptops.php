<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Carbon\Carbon;

class Laptops extends Management_Controller {

  private $module_path    = 'services/laptops';
  private $laptop_status	= [
		0 => 'DECOMISSIONED',
		1 => 'NORMAL',
		2 => 'IN SERVICE',
		3 => 'BROKEN',
		4 => 'NEW',
	];

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

      if($v->service_end){
        // buang action hapus
        unset($action[1]);
      }

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

    $this->form_validation->set_rules('laptop_id', 'Laptop', 'required|numeric|trim');
    $this->form_validation->set_rules('purposes', 'Purposes', 'required|trim');
    $this->form_validation->set_rules('location', 'Serivce Location', 'trim');
    $this->form_validation->set_rules('pic_name', 'PIC Name', 'trim');
    $this->form_validation->set_rules('pic_contact', 'PIC Contact', 'trim');
    $this->form_validation->set_rules('ticket_it', 'JIRA Ticket (IT)', 'trim');
    $this->form_validation->set_rules('ticket_ga', 'JIRA Ticket (GA)', 'trim');
    $this->form_validation->set_rules('service_start', 'Start Date', 'required|trim');
    $this->form_validation->set_rules('service_end', 'End Date', 'trim');
    $this->form_validation->set_rules('description', 'Description', 'trim');
    
    if ($this->form_validation->run()) {
        $post = $this->input->post();

        $data = [
					'purposes'          => $post['purposes'],
					'laptop_id'         => $post['laptop_id'],
					'pic_name'          => $post['pic_name'],
					'pic_contact'       => $post['pic_contact'],
					'ticket_it'         => $post['ticket_it'],
					'ticket_ga'         => $post['ticket_ga'],
					'service_start'     => ($post['service_start']) ? Carbon::parse($post['service_start']) : null,
					'service_end'       => ($post['service_end']) ? Carbon::parse($post['service_end']) : null,
					'service_location'  => $post['location'],
					'description'       => $post['description'],
				];

        if(! $post['id']){
					// // log
          $data += [
            'created_by' => current_user_session('id'),
            'created_at' => Carbon::now(),
          ];

          $db = $this->M_service_laptop->insert(null, $data);
          if($db){
            // bila buat baru maka perlu dimasukkan dalam log laptop ini
            $s_link = base_url('services/laptop/detail/'.base64_encode($db));
            $a_link = anchor($s_link, 'Go To Detail');
            $data_log = [
              'events' 			=> 'SERVICE: New Service',
              'detail' 			=> 'This laptop is being serviced (status changed to '.$this->laptop_status[2].'), for '.$post['purposes'].' purposes. For detail click '.$a_link,
              'created_by' 	=> current_user_session('id'),
              'created_at' 	=> Carbon::now(),
              'laptop_id' 	=> $post['laptop_id'],
              'category' 		=> '1',
            ];
      
            $this->M_service_laptop->insert('laptop_history', $data_log);

            // update status laptop
            $data_laptop = ['flag_status' => 2];
            $this->M_service_laptop->update('laptop', ['id' => $post['laptop_id']], $data_laptop);
          }
        } else {
					// // log
          $data += [
            'updated_by' => current_user_session('id'),
            'updated_at' => Carbon::now(),
          ];

          $db = $this->M_service_laptop->update(null, ['id' => $post['id']], $data);
          if($db){
            // bila tanggal berakhir service sudah ada, maka masukkan dalam log
            if($post['service_end']){
              $s_link = base_url('services/laptop/detail/'.base64_encode($post['id']));
              $a_link = anchor($s_link, 'Go To Detail');
              $data_log = [
                'events' 			=> 'SERVICE: Done Service',
                'detail' 			=> 'This laptop has been completed service (status changed to '.$this->laptop_status[1].'), for '.$post['purposes'].' purposes. For detail click '.$a_link,
                'created_by' 	=> current_user_session('id'),
                'created_at' 	=> Carbon::now(),
                'laptop_id' 	=> $post['laptop_id'],
                'category' 		=> '1',
              ];
        
              $this->M_service_laptop->insert('laptop_history', $data_log);

              // update status laptop
              $data_laptop = ['flag_status' => 1];
              $this->M_service_laptop->update('laptop', ['id' => $post['laptop_id']], $data_laptop);
            }
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
      
      // join dengan tabel entity
      $join = [
        'laptop l'        => 'l.id = s.laptop_id',
        'master_model mo' => 'mo.id = l.model_id',
        'master_brand b'  => 'b.id = mo.brand_id',
      ];
      
      $db   = $this->M_service_laptop->get('service_laptop s', ['s.id' => $post['id']], $join, null, ['s.service_start' => 'desc'], null, null, null, 'l.id as laptop_id, l.name as laptop_name, l.code as laptop_code, mo.name as model_name, b.name as brand_name, s.*');
			if ($db) {
        $db[0]->service_start = Carbon::parse($db[0]->service_start)->format('d-m-Y');
        $db[0]->service_end   = ($db[0]->service_end) ? Carbon::parse($db[0]->service_end)->format('d-m-Y') : null;
      
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
      // dapatkan data laptop id sebelum dihapus
      $dbl = $this->M_service_laptop->get(null, ['id' => $id]);
      if($dbl){
        $db = $this->M_service_laptop->delete(null, ['id' => $id]);
        if ($db) { 
          $status = true; 

          // update ke log laptop
          $data_log = [
            'events' 			=> 'SERVICE: Cancelled Service',
            'detail' 			=> 'This laptop has been cancelled or deleted from system.',
            'created_by' 	=> current_user_session('id'),
            'created_at' 	=> Carbon::now(),
            'laptop_id' 	=> $dbl[0]->laptop_id,
            'category' 		=> '1',
          ];
    
          $this->M_service_laptop->insert('laptop_history', $data_log);
        }
      }
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
