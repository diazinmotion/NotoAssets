<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Carbon\Carbon;

class Software_package extends Management_Controller {

  private $module_path = 'master/software_package';

  function __construct(){
    parent::__construct();

    $this->load->model('M_software_package');
  }

  public function index(){
    $data['extraJs'] 	= [
      'statics/app_common.js',
      'master/software_package_index.js'
    ];
    $data['page_title'] = "Master Software Package";
    $data['page_view'] 	= "V_software_package";
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
        'lower(p.name)' => strtolower($search['value']),
      ];
    }

    // parse data filter dari js menjadi format PHP
    parse_str($filter, $filter_post);
    // related to datatable

    // bila ada filter
    if($filter_post){
      if(isset($filter_post['keyword'])){
        $like += [
          'lower(p.code)' => strtolower($filter_post['keyword']),
          'lower(p.name)' => strtolower($filter_post['keyword']),
          'lower(s.name)' => strtolower($filter_post['keyword']),
        ];
      }
    }

    // join dengan tabel software untuk mendapatkan list software
    $join = [
      'master_software_package_item i'  => 'i.software_package_id = p.id',
      'master_software s'               => 's.id = i.software_id AND s.deleted_at IS NULL',
    ];

    $db_total = $this->M_software_package->get_count('master_software_package p', $where, $join, 'left', null, null, null, $like, 'p.id', ['p.id']);
    $db_data 	= $this->M_software_package->get('master_software_package p', $where, $join, 'left', [' p.name' => 'asc'], $limit, $offset, $like, 'p.id, p.code, p.name, GROUP_CONCAT(s.name) as software_list', ['p.id']);
    foreach($db_data as $i => $v) {

      // bila software list tidak kosong, maka tampilkan
      $list_item = [];
      if($list = explode(',', $v->software_list)){
        $list_item = array_map(function($v){
          return $v;
        }, $list);
      }

      $action = [
          '<a href="javascript:void(0)" class="btn btn-xs btn-primary btn-item-edit" data-id="' . $v->id . '"><i class="fa fa-edit"></i></a>',
          '<a href="javascript:void(0)" class="btn btn-xs btn-danger btn-item-delete" data-id="' . $v->id . '"><i class="fa fa-trash"></i></a>',
      ];
      
      $table_content[] = [
        'code'    => '<center>'.$v->code.'</center>',
        'name'    => $v->name,
        'list'    => ($list_item) ? implode('<br/>', $list_item) : '<em>No Item</em>',
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
    
    if ($this->form_validation->run()) {
        $post = $this->input->post();

        $data = [
					'code' => $post['code'],
					'name' => $post['name'],
				];

        // cek apakah kode ini sudah ada?
				$exist = $this->M_software_package->get(null, ['code' => $post['code']]);

			  if(! $post['id']){
          if(!$exist){
            // log
            $data += [
              'created_by' => current_user_session('id'),
              'created_at' => Carbon::now(),
            ];

            $db = $this->M_software_package->insert(null, $data);
            if($db){
              // insert ke dalam tabel package item
              $data = [];

              foreach ($post['list'] as $v) {
                $data[] = [
                  'software_id'         => $v,
                  'software_package_id' => $db,
                ]; 
              }

              $db = $this->M_software_package->insert('master_software_package_item', $data);
            }
          }else{
						$msg = 'This code has been used. Please use another code';
					}
        } else {
          // cek apakah kode ini sudah ada?
					$current_data = $this->M_software_package->get(null, ['id' => $post['id']]);
					if($current_data[0]->code == $post['code'] || ! $exist){
            // log
            $data += [
              'updated_by' => current_user_session('id'),
              'updated_at' => Carbon::now(),
            ];

            $db = $this->M_software_package->update(null, ['id' => $post['id']], $data);
            if($db){
              // delete data software yang tidak ada dalam id
              $this->M_software_package->delete('master_software_package_item', ['software_package_id' => $post['id']]);

              // insert ke dalam tabel package item
              $data = [];

              foreach ($post['list'] as $v) {
                $data[] = [
                  'software_id'         => $v,
                  'software_package_id' => $post['id'],
                ]; 
              }

              $db = $this->M_software_package->insert('master_software_package_item', $data);
            }
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
      
      // join dengan tabel software untuk mendapatkan list software
      $join = [
        'master_software_package_item i'  => 'i.software_package_id = p.id',
        'master_software s'               => 's.id = i.software_id AND s.deleted_at IS NULL',
      ];
      
      $db 	= $this->M_software_package->get('master_software_package p', ['p.id' => $post['id']], $join, 'left', [' p.name' => 'asc'], null, null, null, 'p.id, p.code, p.name, GROUP_CONCAT(s.name) as software_list, GROUP_CONCAT(s.id) as software_list_id', ['p.id']);
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
			$db = $this->M_software_package->delete(null, ['id' => $id]);
			if ($db) { $status = true; }
		}

		$this->output->set_content_type('application/json')->set_output(json_encode(compact('status', 'msg')));
	}

  function ajax_get_package(){
    $data   = [];
    $like   = [];
    $where  = [];
    $param  = $this->input->post('param');

    if($param){
      $like = [
        'lower(name)' => strtolower($param),
      ];
    }

    // join dengan tabel software untuk mendapatkan list software
    $join = [
      'master_software_package_item i'  => 'i.software_package_id = p.id',
      'master_software s'               => 's.id = i.software_id AND s.deleted_at IS NULL',
    ];
    
    $db 	= $this->M_software_package->get('master_software_package p', $where, $join, 'left', [' p.name' => 'asc'], null, null, null, 'p.id, p.code, p.name, GROUP_CONCAT(s.name) as software_list, GROUP_CONCAT(s.id) as software_list_id', ['p.id']);
    foreach($db as $i => $v){
      // bila software list tidak kosong, maka tampilkan
      $list_item  = [];
      $list       = explode(',', $v->software_list);
      $list_id    = explode(',', $v->software_list_id);

      if($list && $list_id){
        foreach ($list as $list_i => $list_v) {
          $list_item[] = [
            'id'    => $list_id[$list_i],
            'name'  => $list_v,
          ];
        }
      }

      $data[] = [
        'id'    => $v->id, 
        'text'  => $v->name,
        'list'  => $list_item
      ];
    }

    $this->output->set_content_type('application/json')->set_output(json_encode($data));
  }
}
