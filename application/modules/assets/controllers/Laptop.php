<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Carbon\Carbon;

class Laptop extends Management_Controller {

  private $module_path 		= 'assets/laptop';
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
    $this->load->model('licenses/M_license');
  }

  public function index(){
    $data['extraJs'] 	= [
      'statics/app_common.js',
      'assets/laptop_index.js'
    ];
    $data['page_title'] = "Laptop Assets";
    $data['page_view'] 	= "V_laptop_index";
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
      $data           = [];
      $data_software  = [];

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
          'entity_id'           => (isset($post['entity_id'])) ? $post['entity_id'] : null,
          'location_id'         => (isset($post['location_id'])) ? $post['location_id'] : null,
          'serial_number'       => $post['serial_number'],
          'os_type_id'          => (isset($post['os_type_id'])) ? $post['os_type_id'] : null,
          'os_product_key'      => $post['os_product_key'],
          'pki_email'           => $post['pki_email'],
          'pki_password'        => $post['pki_password'],
          'encryption_password' => $post['encryption_password'],    
          'storage_type_id'     => (isset($post['storage_type_id'])) ? $post['storage_type_id'] : null,
          'storage_type_brand'  => $post['storage_type_brand'],
          'storage_size'        => $post['storage_size'],
          'memory_type_id'      => (isset($post['memory_type_id'])) ? $post['memory_type_id'] : null,
          'memory_brand'        => $post['memory_brand'],
          'memory_size'         => $post['memory_size'],
          'account_type_id'     => (isset($post['account_type_id'])) ? $post['account_type_id'] : null,
          'account_email'       => $post['account_email'],
          'flag_status'         => $post['flag_status'],
          'purchased_at'        => ($post['purchased_at']) ? format_date_to_db($post['purchased_at']) : null,
          'warranty_expired'    => ($post['warranty_expired']) ? format_date_to_db($post['warranty_expired']) : null,
          'model_id'            => (isset($post['model_id'])) ? $post['model_id'] : null,
        ];

        if(! $id){
          // insert
          $data += [
            'created_by' => current_user_session('id'),
            'created_at' => Carbon::now(),
          ];
        } else {
          // update
          $data += [
            'updated_by' => current_user_session('id'),
            'updated_at' => Carbon::now(),
          ];
        }
        
        // dapatkan data software untuk aset ini
        foreach($post['software_id'] as $i => $v){
          $temp_software = [
            'license_id'    => (isset($post['license_id'][$i])) ? $post['license_id'][$i] : null,
            'expiration_at' => (isset($post['software_expired_at'][$i]) && $post['software_expired_at'][$i]) ? format_date_to_db($post['software_expired_at'][$i]) : null,
            'installed_at'  => (isset($post['software_install_at'][$i]) && $post['software_install_at'][$i]) ? format_date_to_db($post['software_install_at'][$i]) : null,
            'product_key'   => $post['software_product_key'][$i]
          ];

          if(is_numeric($i)){
            // update
            $temp_software += [
              'updated_by' => current_user_session('id'),
              'updated_at' => Carbon::now(),
            ];
          }else{
            // insert
            $temp_software += [
              'created_by' => current_user_session('id'),
              'created_at' => Carbon::now(),
            ];
          }

          $data_software[$i] = $temp_software;
        }

        $db = $this->M_laptop->proccess_data($id, $data, $data_software);
        if(! $db){
          $msg[] = 'Cant save this asset for this moment. Please try again later.';
        }
      } else {
        $msg[] = str_replace(['<p>', '</p>'], [null, '<br/>'], validation_errors());
      }

      $this->session->set_flashdata('has_save', true);
      $this->session->set_flashdata('message', $msg);

      redirect('assets/laptop/'.(($id) ? 'edit/'.base64_encode($id) : 'create'));
    }else{
      $db = false;

      if($id){
        $select = '
          l.*,
          e.name as entity_name,
          lo.name as location_name,
          o.id as os_id,
          o.name as os_name,
          s.id as storage_id,
          s.name as storage_name,
          s.code as storage_code,
          m.id as memory_id,
          m.name as memory_name,
          m.code as memory_code,
          a.id as account_id,
          a.name as account_name,
          mo.id as model_id,
          mo.name as model_name,
          b.name as brand_name,
          uc.full_name as created_name, 
          uu.full_name as updated_name
        ';

        $join = [
          'master_entity e'     => 'e.id = l.entity_id',
          'master_location lo'  => 'lo.id = l.location_id',
          'master_os o'         => 'o.id = l.os_type_id',
          'master_storage s'    => 's.id = l.storage_type_id',
          'master_memory m'     => 'm.id = l.memory_type_id',
          'master_account a'    => 'a.id = l.account_type_id',
          'master_model mo'     => 'mo.id = l.model_id',
          'master_brand b'      => 'b.id = mo.brand_id',
          'users uc'            => 'uc.id = l.created_by',
          'users uu'            => 'uu.id = l.updated_by'
        ];

        $db = $this->M_laptop->get('laptop l', ['l.id' => $id], $join, 'left', ['e.name, mo.name, l.name' => 'asc'], null, null, null, $select);    
        if(! $db){ 
          show_404();
        }
      }

      $data['id']         = base64_encode($id);
      $data['db']         = $db;
      $data['t_software'] = $this->_show_table_software($id);
      $data['has_save']   = ($this->session->flashdata('has_save')) ?: false;
      $data['message']    = ($this->session->flashdata('message')) ?: [];
      $data['page_title'] = ($id) ? "Edit Laptop" : "New Laptop";
      $data['page_view'] 	= "V_laptop_create";
      $data['module_url']	= base_url($this->module_path);
      $data['extraJs'] 	= [
        'statics/app_common.js',
        'assets/laptop_create.js'
      ];
      
      $this->load->view('layouts/cms/V_master', $data);
    }
  }

  function _show_table_software($id = null){
    // set data content
    $data_content = [];
    
    // set heading
    $this->table->set_heading(
      ['data' => 'Software Name', 'class' => 'bg-primary', 'style' => 'width:30%'],
      ['data' => 'Installed', 'class' => 'bg-primary', 'style' => 'width:15%'],
      ['data' => 'Expired', 'class' => 'bg-primary', 'style' => 'width:15%'],
      ['data' => 'Product Key', 'class' => 'bg-primary', 'style' => 'width:30%'],
      ['data' => 'Actions', 'class' => 'bg-primary text-center', 'style' => 'width:10%']
    );

    $select = "
      ls.*,
      l.id as license_id,
      l.name as license_name,
      l.universal_product_key as license_universal_product_key,
      l.universal_expired_at as license_universal_expired_at,
      l.is_bulk_license as license_is_bulk_license,
      s.id as software_id,
      s.name as software_name
    ";
    $join = [
      'license l'         => 'l.id = ls.license_id',
      'master_software s' => 's.id = l.software_id'
    ];
    $db = $this->M_license->get('license_seat ls', ['laptop_id' => $id], $join, 'left', null, null, null, null, $select);
    foreach($db as $v){
      $data_content[$v->id] = [
        'software_id'           => $v->software_id,
        'software_name'         => $v->software_name,
        'license_id'            => $v->license_id,
        'license_name'          => $v->license_name,
        'license_expired'       => $v->expiration_at,
        'license_installed_at'  => $v->installed_at,
        'license_product_key'   => $v->product_key,
      ];
    }

    $data_content['uid'] = [
      'software_id'           => null,
      'software_name'         => null,
      'license_id'            => null,
      'license_name'          => null,
      'license_expired'       => null,
      'license_installed_at'  => null,
      'license_product_key'   => null,
    ];

    foreach($data_content as $i => $v){
      $uid = (! is_numeric($i)) ? 'uid' : $i;
      
      $opt_software = [$v['software_id'] => $v['software_name']];
      $opt_license  = [$v['license_id'] => $v['license_name']];

      $f_software = form_dropdown('software_id['.$uid.']', $opt_software, $v['software_id'], 'class=software_id');
      $f_license  = form_dropdown('license_id['.$uid.']', $opt_license, $v['license_id'], 'class=license_id');
      $f_install  = form_input('software_install_at['.$uid.']', (($v['license_installed_at']) ? Carbon::parse($v['license_installed_at'])->format('d-m-Y') : null), 'class="dtp-max-today form-control software_install_at" placeholder="Input data"');
      $f_expired  = form_input('software_expired_at['.$uid.']', (($v['license_expired']) ? Carbon::parse($v['license_expired'])->format('d-m-Y') : null), 'class="dtp form-control software_expired_at" placeholder="Input data"');
      $f_p_key    = form_input('software_product_key['.$uid.']', $v['license_product_key'], 'class="form-control software_product_key" placeholder="Input data"');
      $f_delete   = '<a href="javascript:void(0)" class="btn btn-xs btn-danger btn-software-delete"><i class="fa fa-trash fa-fw"></i></a>';

      $action = implode(' ', [$f_delete]);

      $this->table->add_row(
        ['data' => $f_software.'<div class="clearfix">'.$f_license.'</div>'],
        ['data' => $f_install],
        ['data' => $f_expired],
        ['data' => $f_p_key],
        ['data' => $action, 'class' => 'text-center']
      );
    }

    return generate_table('table-software');
  }

  function ajax_module_index(){
    $like  					= [];
    $where  				= ['e.flag_active' => '1'];
    $action 				= [];
    $table_content 	= [];

    // related to datatable
    $filter = $this->input->post('filter');

    // parse data filter dari js menjadi format PHP
    parse_str($filter, $filter_post);

    // bila ada filter
    if($filter_post){
      if(isset($filter_post['keyword'])){
        $like += [
          'lower(l.name)' => strtolower($filter_post['keyword']),
        ];
      }
    }

    $select = '
      l.*,
      e.name as entity_name,
      lo.name as location_name,
      o.name as os_name,
      s.name as storage_name,
      s.code as storage_code,
      m.name as memory_name,
      m.code as memory_code,
      a.name as account_name,
      mo.name as model_name,
      b.name as brand_name,
      (select count(id) from license_seat sls where l.id = sls.laptop_id) as software_installed
    ';

    $join = [
      'master_entity e'     => 'e.id = l.entity_id',
      'master_location lo'  => 'lo.id = l.location_id',
      'master_os o'         => 'o.id = l.os_type_id',
      'master_storage s'    => 's.id = l.storage_type_id',
      'master_memory m'     => 'm.id = l.memory_type_id',
      'master_account a'    => 'a.id = l.account_type_id',
      'master_model mo'     => 'mo.id = l.model_id',
      'master_brand b'      => 'b.id = mo.brand_id',
    ];

    $db_total = $this->M_laptop->get_count('laptop l', $where, $join, 'left', null, null, null, $like, 'l.id');
    $db_data 	= $this->M_laptop->get('laptop l', $where, $join, 'left', ['e.name, mo.name, l.name' => 'asc'], null, null, null, $select);
    foreach($db_data as $i => $v) {

      $action = [
          '<a href="'.base_url($this->module_path.'/edit/'.base64_encode($v->id)).'" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>',
          '<a href="javascript:void(0)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>',
      ];

      // link ke edit item
      $link_model     = ($v->model_name) ? anchor('/master/model', $v->brand_name.' '.$v->model_name) : null;
      $link_memory    = ($v->memory_code) ? anchor('/master/memory_type', $v->memory_code) : null;
      $link_storage   = ($v->storage_code) ? anchor('/master/storage_type', $v->storage_code) : null;
      $link_os        = ($v->os_name) ? anchor('/master/os_type', $v->os_name) : null;
      $link_entity    = ($v->entity_name) ? anchor('/master/entity', $v->entity_name) : null;
      $link_location  = ($v->location_name) ? anchor('/master/location', $v->location_name) : null;
      
      $table_content[] = [
        'id'                  => $v->id,
        'entity'              => $link_entity,
        'location'            => $link_location,
        'code'                => $v->code,
        'model'               => $link_model,
        'name'                => $v->name,
        'sn'                  => $v->serial_number,
        'os'                  => $link_os,
        'os_key'              => $v->os_product_key,
        'storage_type'        => $link_storage,
        'storage_size'        => $v->storage_size,
        'memory_type'         => $link_memory,
        'memory_size'         => $v->memory_size,
        'status'              => $v->flag_status,
        'software_installed'  => $v->software_installed,
        'action'	            => '<center><div class="btn-group">'.implode('', $action).'</div></center>',
      ];
    }

    $result = [
      "total" 						=> ($db_total) ? $db_total->total : 0,
      "totalNotFiltered" 	=> ($db_total) ? $db_total->total : 0,
      "rows"              => $table_content,
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
