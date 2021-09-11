<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Carbon\Carbon;

class Licenses extends Management_Controller {

  private $module_path = 'licenses';

  function __construct(){
    parent::__construct();

    $this->load->model('M_license');
  }

  public function index(){
    $data['extraJs'] 	= [
      'statics/app_common.js',
      'licenses/licenses_index.js'
    ];
    $data['page_title'] = "Manage Licenses";
    $data['page_view'] 	= "V_licenses_index";
    $data['module_url']	= base_url($this->module_path);
    
    $this->load->view('layouts/cms/V_master', $data);
  }

  public function create($id = null){
    // inputan POST
    $post = $this->input->post();

    if($id){
      $id = base64_decode($id);
      if(! is_numeric((int) base64_decode($id))){
        show_404();
      }
    }

    if($post){
      $this->form_validation->set_rules('software_id', 'Software', 'required|numeric|trim');
      $this->form_validation->set_rules('name', 'Assets Name', 'trim');
      $this->form_validation->set_rules('is_bulk_license', 'License Type', 'required|numeric|trim');
      $this->form_validation->set_rules('flag_permanent', 'License Category', 'required|numeric|trim');
      $this->form_validation->set_rules('purchased_at', 'Purchased Date', 'trim');
      $this->form_validation->set_rules('purchased_place', 'Purchased Location', 'trim');
      $this->form_validation->set_rules('universal_expired_at', 'Bulk Expiration', 'trim');
      $this->form_validation->set_rules('universal_product_key', 'Bulk Product Key', 'trim');
      $this->form_validation->set_rules('quota', 'Quota', 'numeric|trim');
      
      if ($this->form_validation->run()) {
        $data = [
          'name'                  => $post['name'],
          'software_id'           => $post['software_id'],
          'is_bulk_license'       => $post['is_bulk_license'],
          'flag_permanent'        => $post['flag_permanent'],
          'purchased_at'          => (isset($post['purchased_at'])) ? format_date_to_db($post['purchased_at']) : null,
          'purchased_place'       => $post['purchased_place'],
          'universal_expired_at'  => (isset($post['universal_expired_at'])) ? format_date_to_db($post['universal_expired_at']) : null,
          'universal_product_key' => (isset($post['universal_product_key'])) ? $post['universal_product_key'] : null,
          'quota'                 => (isset($post['quota'])) ? $post['quota'] : 0,
        ];

        if(! $id){
          // log
          $data += [
            'created_by' => current_user_session('id'),
            'created_at' => Carbon::now(),
          ];

          $db = $this->M_license->insert(null, $data);
        } else {
          // log
          $data += [
            'updated_by' => current_user_session('id'),
            'updated_at' => Carbon::now(),
          ];

          $db = $this->M_license->update(null, ['id' => $id], $data);
        }
      } else {
        $msg[] = str_replace(['<p>', '</p>'], [null, '<br/>'], validation_errors());
      }

      $this->session->set_flashdata('has_save', true);
      $this->session->set_flashdata('message', $msg);

      redirect('licenses/'.(($id) ? 'edit/'.base64_encode($id) : 'create'));
    }else{
      $db = false;

      if($id){
        $join = ['master_software s' => 's.id = l.software_id'];
        $db 	= $this->M_license->get('license l', ['l.id' => $id], $join, 'left', null, null, null, null, 'l.*, s.name as software_name');
        if(! $db){ 
          show_404();
        }
      }

      $data['id']         = base64_encode($id);
      $data['has_save']   = ($this->session->flashdata('has_save')) ?: false;
      $data['message']    = ($this->session->flashdata('message')) ?: [];
      $data['page_title'] = ($id) ? "Edit License" : "New License";
      $data['page_view'] 	= "V_licenses_create";
      $data['module_url']	= base_url($this->module_path);
      $data['db']         = $db;
      $data['extraJs'] 	  = [
        'statics/app_common.js',
        'licenses/licenses_create.js'
      ];

      $this->load->view('layouts/cms/V_master', $data);
    }
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
        'lower(s.name)' => strtolower($filter_post['keyword']),
      ];
    }

    // parse data filter dari js menjadi format PHP
    parse_str($filter, $filter_post);

    // bila ada filter
    if($filter_post){
      if(isset($filter_post['keyword'])){
        $like += [
          'lower(l.name)' => strtolower($filter_post['keyword']),
          'lower(s.name)' => strtolower($filter_post['keyword']),
        ];
      }

      if(isset($filter_post['software_id'])){
        $where += ['l.software_id' => $filter_post['software_id']];
      }
    }

    $join = ['master_software s' => 's.id = l.software_id'];

    $db_total = $this->M_license->get_count('license l', $where, $join, 'left', null, null, null, $like, 'l.id');
    $db_data 	= $this->M_license->get('license l', $where, $join, 'left', ['l.name' => 'asc'], $limit, $offset, $like, 'l.*, s.name as software_name');
    foreach($db_data as $i => $v) {

      $action = [
          '<a href="'.base_url($this->module_path."/edit/".base64_encode($v->id)).'" class="btn btn-xs btn-primary btn-item-edit"><i class="fa fa-edit"></i></a>',
          '<a href="javascript:void(0)" class="btn btn-xs btn-danger btn-item-delete" data-id="' . $v->id . '"><i class="fa fa-trash"></i></a>',
      ];

      // type lisensi
      $s_type   = ($v->is_bulk_license == 0) ? '<span class="label label-primary">UNIQUE KEY</span>' : '<span class="label label-success">BULK KEY</span>';
      $s_seat   = ($v->quota == 0) ? null : "<small class='clearfix'><b>Used:</b> 0/{$v->quota}</small>";
      
      $s_puchase   = Carbon::parse($v->purchased_at)->format('d M Y');
      $s_expired   = ($v->flag_permanent == 0) ? (($v->universal_expired_at) ? Carbon::parse($v->universal_expired_at)->format('d M Y') : '<span class="label label-primary">UNIQUE</span>') : '<span class="label label-success">PERMANENT</span>';
      
      $table_content[] = [
        'name'        => "<b class='text-primary'>{$v->name}</b>
                          <small class='clearfix text-bold'><b>Software:</b> {$v->software_name}</small>
                          {$s_seat}",
        'purchase'    => '<center>'.$s_puchase.'</center>',
        'expiration'  => '<center>'.$s_expired.'</center>',
        'type'        => '<center>'.$s_type.'</center>',
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

	function ajax_delete_item(){
		$status = false;
		$msg 		= [];

		if($id = $this->input->post('id')){
			$db = $this->M_license->delete(null, ['id' => $id]);
			if ($db) { $status = true; }
		}

		$this->output->set_content_type('application/json')->set_output(json_encode(compact('status', 'msg')));
	}

  function ajax_get_licenses(){
    $data   = [];
    $like   = [];
    $where  = ['deleted_at' => null];
    $param  = $this->input->post('param');

    if($param){
      $like = [
        'lower(name)' => strtolower($param),
      ];
    }

    $db = $this->M_license->get(null, $where, null, null, ['name' => 'asc'], 100, null, $like, "id, name");
    foreach($db as $i => $v){
      $data[] = ['id' => $v->id, 'text' => $v->name];
    }

    $this->output->set_content_type('application/json')->set_output(json_encode($data));
  }
}
