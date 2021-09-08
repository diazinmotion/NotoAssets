<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Carbon\Carbon;

class Laptop extends Management_Controller {

  private $module_path 		= 'asstes/laptop';
	private $laptop_status	= [
		0 => 'DECOMISSIONED',
		1 => 'NORMAL',
		2 => 'IN SERVICE',
		3 => 'BROKEN',
		4 => 'NEW',
	];

  function __construct(){
    parent::__construct();

    $this->load->model('M_laptop');
  }

  public function index(){
    $data['extraJs'] 	= [
      'statics/app_common.js',
      'asstes/laptop_index.js'
    ];
    $data['page_title'] = "Laptop Assets";
    $data['page_view'] 	= "V_laptop_index";
    $data['module_url']	= base_url($this->module_path);
    
    $this->load->view('layouts/cms/V_master', $data);
  }

  public function create($id = null){
    $has_save = false;
    $msg      = [];
    $post     = $this->input->post();

    if($post){
      $has_save = true;

      $this->form_validation->set_rules('entity_id', 'Entity', 'numeric|trim');
      $this->form_validation->set_rules('location_id', 'Location', 'numeric|trim');
      $this->form_validation->set_rules('code', 'Code / Barcode', 'trim');
      $this->form_validation->set_rules('model_id', 'Model', 'numeric|required|trim');
      $this->form_validation->set_rules('name', 'Assets Name', 'trim');
      $this->form_validation->set_rules('serial_number', 'Serial Number', 'trim');
      $this->form_validation->set_rules('purchased_at', 'Purchased Date', 'trim');
      $this->form_validation->set_rules('warranty_expired', 'Warranty Expiration', 'trim');
      $this->form_validation->set_rules('os_type_id', 'OS Type', 'numeric|trim');
      $this->form_validation->set_rules('os_product_key', 'OS Product Key', 'trim');
      $this->form_validation->set_rules('storage_type_id', 'Storage Type', 'numeric|trim');
      $this->form_validation->set_rules('storage_type_brand', 'Storage Brand', 'trim');
      $this->form_validation->set_rules('storage_size', 'Storage Size', 'numeric|trim');
      $this->form_validation->set_rules('memory_type_id', 'Memory Type', 'numeric|trim');
      $this->form_validation->set_rules('memory_brand', 'Memory Brand', 'trim');
      $this->form_validation->set_rules('memory_size', 'Memory Size', 'numeric|trim');
      $this->form_validation->set_rules('account_type_id', 'Account Type', 'numeric|trim');
      $this->form_validation->set_rules('account_email', 'Account Email', 'valid_email|trim');
      $this->form_validation->set_rules('pki_email', 'PKI Email', 'valid_email|trim');
      $this->form_validation->set_rules('pki_password', 'PKI Password', 'trim');
      $this->form_validation->set_rules('encryption_password', 'Encryption Password', 'trim');
      $this->form_validation->set_rules('flag_status', 'Name', 'numeric|trim');
      
      if ($this->form_validation->run()) {
        $data = [
          'code'                => $post['code'],
          'name'                => $post['name'],
          'entity_id'           => $post['entity_id'],
          'location_id'         => $post['location_id'],
          'serial_number'       => $post['serial_number'],
          'os_type_id'          => $post['os_type_id'],
          'os_product_key'      => $post['os_product_key'],
          'pki_email'           => $post['pki_email'],
          'pki_password'        => $post['pki_password'],
          'encryption_password' => $post['encryption_password'],    
          'storage_type_id'     => $post['storage_type_id'],
          'storage_type_brand'  => $post['storage_type_brand'],
          'storage_size'        => $post['storage_size'],
          'memory_type_id'      => $post['memory_type_id'],
          'memory_brand'        => $post['memory_brand'],
          'memory_size'         => $post['memory_size'],
          'account_type_id'     => $post['account_type_id'],
          'account_email'       => $post['account_email'],
          'flag_status'         => $post['flag_status'],
          'purchased_at'        => ($post['purchased_at']) ? format_date_to_db($post['purchased_at']) : null,
          'warranty_expired'    => ($post['warranty_expired']) ? format_date_to_db($post['warranty_expired']) : null,
          'model_id'            => $post['model_id'],
        ];

        if(! $id){
          // log
          $data += [
            'created_by' => current_user_session('id'),
            'created_at' => Carbon::now(),
          ];

          $db = $this->M_laptop->insert(null, $data);
        } else {
          // log
          $data += [
            'updated_by' => current_user_session('id'),
            'updated_at' => Carbon::now(),
          ];

          $db = $this->M_laptop->update(null, ['id' => $id], $data);
        }
      } else {
        $msg = str_replace(['<p>', '</p>'], [null, '<br/>'], validation_errors());
      }
    }

    $data['t_software'] = $this->_show_table_software();
    $data['has_save']   = $has_save;
    $data['message']    = $msg;
    $data['page_title'] = "New Laptop";
    $data['page_view'] 	= "V_laptop_create";
    $data['module_url']	= base_url($this->module_path);
    $data['extraJs'] 	= [
      'statics/app_common.js',
      'assets/laptop_create.js'
    ];
    
    $this->load->view('layouts/cms/V_master', $data);
  }

  function _show_table_software(){
    // set data content
    $data_content = [];
    
    // set heading
    $this->table->set_heading(
      ['data' => 'Software Name', 'class' => 'bg-primary', 'style' => 'width:50%'],
      ['data' => 'License', 'class' => 'bg-primary', 'style' => 'width:40%'],
      ['data' => 'Actions', 'class' => 'bg-primary text-center', 'style' => 'width:10%']
    );

    // TODO: DAPATKAN DATA SOFTWARE PADA ASET INI

    $data_content['uid'] = [
      'software_id'     => null,
      'software_name'   => null,
      'license_id'      => null,
      'license_name'    => null,
      'license_expired' => null
    ];

    foreach($data_content as $i => $v){
      $uid = (! is_numeric($i)) ? 'uid' : $i;

      $f_software = form_dropdown('software_id['.$uid.']', [], null, 'class=software_id');
      $f_delete   = '<a href="javascript:void(0)" class="btn btn-xs btn-danger btn-software-delete"><i class="fa fa-trash fa-fw"></i></a>';

      $this->table->add_row(
        ['data' => $f_software],
        ['data' => '<small class="clearfix">Expired On: 22 Feb 2021</small>'],
        ['data' => $f_delete, 'class' => 'text-center']
      );
    }

    return generate_table('table-software');
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

    $db_total = $this->M_laptop->get_count(null, $where, null, null, null, null, null, $like, 'id');
    $db_data 	= $this->M_laptop->get(null, $where, null, null, ['name' => 'asc'], $limit, $offset, $like, 'id, name');
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

	function ajax_delete_item(){
		$status = false;
		$msg 		= [];

		if($id = $this->input->post('id')){
			$data = [
				'deleted_at'  => date('Y-m-d H:i:s'),
				'deleted_by'  => current_user_session('id'),
			];
			$db = $this->M_laptop->update(null, ['id' => $id], $data);
			if ($db) { $status = true; }
		}

		$this->output->set_content_type('application/json')->set_output(json_encode(compact('status', 'msg')));
	}
}
