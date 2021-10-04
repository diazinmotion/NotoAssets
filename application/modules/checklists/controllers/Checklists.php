<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Carbon\Carbon;

class Checklists extends Management_Controller {

  private $module_path = 'checklists';

  function __construct(){
    parent::__construct();

    $this->load->model('M_checklist');
  }

  public function index(){
    $data['extraJs'] 	= [
      'statics/app_common.js',
      'checklist/checklist_index.js'
    ];
    $data['page_title'] = "Checklist Group";
    $data['page_view'] 	= "V_checklist";
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
        'lower(c.name)' => strtolower($search['value']),
      ];
    }

    // parse data filter dari js menjadi format PHP
    parse_str($filter, $filter_post);
    // related to datatable

    // bila ada filter
    if($filter_post){
      if(isset($filter_post['keyword'])){
        $like += [
          'lower(c.name)' => strtolower($filter_post['keyword']),
          'lower(i.name)' => strtolower($filter_post['keyword']),
        ];
      }
    }

    $select = "
      c.*,
      count(i.id) as task_count
    ";

    // join dengan tabel checklist item
    $join = [
      'checklist_item i' => 'i.checklist_id = c.id'
    ];

    $db_total = $this->M_checklist->get_count_item($like);
    $db_data 	= $this->M_checklist->get('checklist c', $where, $join, 'left', [' c.name' => 'asc'], $limit, $offset, $like, $select, ['c.id']);
    foreach($db_data as $i => $v) {

      $action = [
          '<a href="javascript:void(0)" class="btn btn-xs btn-primary btn-item-edit" data-id="' . $v->id . '"><i class="fa fa-edit"></i></a>',
          '<a href="javascript:void(0)" class="btn btn-xs btn-danger btn-item-delete" data-id="' . $v->id . '"><i class="fa fa-trash"></i></a>',
      ];
      
      $table_content[] = [
        'name'    => $v->name,
        'tasks'   => '<center>'.$v->task_count.'</center>',
        'action'	=> '<center><div class="btn-group">'.implode('', $action).'</div></center>',
      ];
    }

    $result = [
      "draw" 						=> $draw,
      "recordsTotal" 		=> ($db_total) ? count($db_total) : 0,
      "recordsFiltered" => ($db_total) ? count($db_total) : 0,
      "data" 						=> $table_content,
    ];

    $this->output->set_content_type('application/json')->set_output(json_encode($result));

  }

  function ajax_post_form(){
    $db     = false;
    $status = false;
    $msg 		= [];

    $this->form_validation->set_rules('name', 'Name', 'required|trim');
    
    if ($this->form_validation->run()) {
        $post = $this->input->post();
        $id         = (isset($post['id']) && $post['id']) ? $post['id'] : null;
        $data_tasks = [];
        $data       = [ 'name' => $post['name'] ];

        // untuk task yang didaftarkan
        if(isset($post['task_name'])){
          foreach($post['task_name'] as $i => $v){
            $data_tasks[$i] = ['name' => $v];
          }
        }

        // cek apakah kode ini sudah ada?
				$db = $this->M_checklist->process_checklist($id, $data, $data_tasks);
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
      $join = [ 'checklist c' => 'ci.checklist_id = c.id' ];
      $db 	= $this->M_checklist->get('checklist_item ci', ['ci.checklist_id' => $post['id']], $join, 'left', [' ci.id' => 'asc'], null, null, null, 'c.id as checklist_id, c.name as checklist_name, ci.name, ci.id');
			if ($db) {
					$status = true;
					$data 	= $db;
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
			$db = $this->M_checklist->delete(null, ['id' => $id]);
			if ($db) { $status = true; }
		}

		$this->output->set_content_type('application/json')->set_output(json_encode(compact('status', 'msg')));
	}

  function ajax_get_checklist(){
    $data   = [];
    $like   = [];
    $where  = [];
    $param  = $this->input->post('param');

    if($param){
      $like = [
        'lower(name)' => strtolower($param),
      ];
    }

    $db = $this->M_checklist->get(null, [], null, null, ['name' => 'asc'], null, null, null, 'id, name');
    foreach($db as $i => $v){
      $data[] = [
        'id'    => $v->id, 
        'text'  => $v->name
      ];
    }

    $this->output->set_content_type('application/json')->set_output(json_encode($data));
  }
}
