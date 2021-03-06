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
      $data             = [];
      $data_software    = [];
      $data_checklist   = [];
      $data_handover    = [];
      $laptop_checklist = [];

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
          'code'                  => $post['code'],
          'name'                  => $post['name'],
          'entity_id'             => (isset($post['entity_id']) && $post['entity_id'] != '') ? $post['entity_id'] : null,
          'location_id'           => (isset($post['location_id']) && $post['location_id'] != '') ? $post['location_id'] : null,
          'serial_number'         => $post['serial_number'],
          'os_type_id'            => (isset($post['os_type_id']) && $post['os_type_id'] != '') ? $post['os_type_id'] : null,
          'os_product_key'        => $post['os_product_key'],
          'pki_email'             => $post['pki_email'],
          'pki_password'          => $post['pki_password'],
          'encryption_password'   => $post['encryption_password'],    
          'storage_type_id'       => (isset($post['storage_type_id']) && $post['storage_type_id'] != '') ? $post['storage_type_id'] : null,
          'storage_type_brand'    => $post['storage_type_brand'],
          'storage_size'          => ($post['storage_size'] != '') ? $post['storage_size'] : 0,
          'memory_type_id'        => (isset($post['memory_type_id']) && $post['memory_type_id'] != '') ? $post['memory_type_id'] : null,
          'memory_brand'          => $post['memory_brand'],
          'memory_size'           => ($post['memory_size'] != '') ? $post['memory_size'] : 0,
          'account_type_id'       => (isset($post['account_type_id']) && $post['account_type_id'] != '') ? $post['account_type_id'] : null,
          'account_email'         => $post['account_email'],
          'flag_status'           => $post['flag_status'],
          'purchased_at'          => ($post['purchased_at'] && $post['purchased_at'] != '') ? format_date_to_db($post['purchased_at']) : null,
          'warranty_expired'      => ($post['warranty_expired'] && $post['warranty_expired'] != '') ? format_date_to_db($post['warranty_expired']) : null,
          'model_id'              => (isset($post['model_id'])) ? $post['model_id'] : null,
          'flag_status_original'  => $post['flag_status_original'],
          'code_original'         => $post['code_original']
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

        // upload file recovery
        if($_FILES){
          $original_file = isset($post['original_file_name']) ? $post['original_file_name'] : null;

          $config['upload_path']      = getcwd().'/../uploads/';
          $config['allowed_types']    = 'zip|rar|txt';
          $config['file_ext_tolower'] = true;
          $config['encrypt_name']     = true;
          $config['max_size']         = 0;

          $this->load->library('upload', $config);

          if(! $this->upload->do_upload('encryption_recovery_file')){
            $msg = $this->upload->display_errors();
          }else{
            // berhasil diupload, maka hapus file yang lama bila ada
            if($original_file){
              @unlink(getcwd().'/../uploads/'.$original_file);
            }

            $d = $this->upload->data();
            $data += ['encryption_recovery_file' => $d['file_name']];
          }
        }
        
        // dapatkan data software untuk aset ini
        if(isset($post['software_id'])){
          foreach($post['software_id'] as $i => $v){
            // cek apabila license id kosong, maka kembalikan ke halaman sebelumnya
            if(! isset($post['license_id'][$i]) || $post['license_id'] == ''){
              $this->session->set_flashdata('has_save', true);
              $this->session->set_flashdata('message', 'Software must have at least one license choosen.');

              redirect('assets/laptop/'.(($id) ? 'edit/'.base64_encode($id) : 'create'));
              break;
            }else{
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
          }
        }

        // START CHECKLIST LAPTOP
        // bila ada data ini dari form maka sudah pasti merupakan checklist baru
        if(isset($post['checklist_id']) && $post['checklist_id']){
          foreach($post['checklist_id'] as $v){
            $laptop_checklist[] = [ 'checklist_id' => $v ];
          }
        }
        // END CHECKLIST LAPTOP
        
        // START CHECKLIST ITEM STATUS
        if(isset($post['checklist_item_id'])){
          foreach($post['checklist_item_id'] as $i => $v){
            $temp = [
              'id'                  => $i,
              'checklist_item_id'   => $v,
              'checklist_laptop_id' => $post['checklist_laptop_id'][$i],
              'has_done'            => (isset($post['checklist_has_done'][$i])) ? 'Y' : 'N',
            ];
  
            if(is_numeric($i)){
              // update
              $temp += [
                'updated_by' => current_user_session('id'),
                'updated_at' => Carbon::now(),
              ];
            }else{
              // insert
              $temp += [
                'created_by' => current_user_session('id'),
                'created_at' => Carbon::now(),
              ];
            }
  
            $data_checklist[$i] = $temp;
          }
        }
        // END CHECKLIST ITEM STATUS

        // START HANDOVER (IF ANY)
        if(isset($post['ho_location_id'])){
          $data_handover = [
            'person_name'     => ($post['ho_person_name']) ? $post['ho_person_name'] : null,
            'location_id'     => ($post['ho_location_id']) ? $post['ho_location_id'] : null,
            'cubical_number'  => ($post['ho_cubical_number']) ? $post['ho_cubical_number'] : null,
            'handovered_at'   => ($post['ho_handovered_at_date'] && $post['ho_handovered_at_time']) ? Carbon::parse($post['ho_handovered_at_date'].' '.$post['ho_handovered_at_time']) : null,
            'created_by'      => current_user_session('id'),
            'created_at'      => Carbon::now()
          ];
        }
        // END HANDOVER (IF ANY)

        $db = $this->M_laptop->proccess_data($id, $data, $data_software, $laptop_checklist, $data_checklist, $data_handover);
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
      $db   = false;
      $db_c = false;

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

        $db_c = $this->M_laptop->get_laptop_checklist($id);
      }

      $data['id']         = base64_encode($id);
      $data['db']         = $db;
      $data['db_c']       = $db_c;
      $data['l_status']   = $this->laptop_status;
      $data['t_software'] = $this->_table_software($id);
      $data['t_logs']     = $this->_table_logs($id);
      $data['t_handover'] = $this->_table_handover($id);
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

  public function download_encryption_file($id = null){
    if($id){
      $id = base64_decode($id);
      if(! is_numeric((int) base64_decode($id))){
        show_404();
      }else{
        if($id){
          $db = $this->M_laptop->get('laptop', ['id' => $id]);    
          if(! $db){ 
            show_404();
          }else{
            // download file
            $url = getcwd().'/../uploads/'.$db[0]->encryption_recovery_file;
            force_download($url, null);
          }
        }
      }
    }else{
      show_404();
    }
  }

  function ajax_delete_checklist(){
    $status = false;
    $msg    = [];
    $post   = $this->input->post();

    $this->form_validation->set_rules('id', 'ID', 'numeric|required');
    if($this->form_validation->run()) {
      // lakukan delete checklist group item
      $db = $this->M_laptop->delete('checklist_laptop', ['id' => $post['id']]);
      if($db){
        $status = true;
      }else{
        $msg = 'Cannot delete this checklist group.';
      }
    }else{
      $msg[] = str_replace(['<p>', '</p>'], [null, '<br/>'], validation_errors());
    }

    $this->output->set_content_type('application/json')->set_output(json_encode(compact('status', 'msg')));
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
          '<a href="'.base_url($this->module_path.'/edit/'.base64_encode($v->id)).'" class="btn btn-xs btn-primary" title="Edit Asset"><i class="fa fa-edit"></i></a>',
          '<a href="javascript:void(0)" class="btn btn-xs btn-success btn-laptop-clone" data-id="'.$v->id.'" title="Clone Asset"><i class="fa fa-copy"></i></a>',
          '<a href="javascript:void(0)" class="btn btn-xs btn-danger btn-laptop-delete" data-id="'.$v->id.'" title="Delete Asset"><i class="fa fa-trash"></i></a>',
      ];

      // link ke edit item
      $link_model     = ($v->model_name) ? anchor('/master/model', $v->brand_name.' '.$v->model_name) : null;
      $link_memory    = ($v->memory_code) ? anchor('/master/memory_type', $v->memory_code) : null;
      $link_storage   = ($v->storage_code) ? anchor('/master/storage_type', $v->storage_code) : null;
      $link_os        = ($v->os_name) ? anchor('/master/os_type', $v->os_name) : null;
      $link_entity    = ($v->entity_name) ? anchor('/master/entity', $v->entity_name) : null;
      $link_location  = ($v->location_name) ? anchor('/master/location', $v->location_name) : null;

      $s_status       = '<span class="label '.(!in_array($v->flag_status, [0,3]) ? 'label-primary' : 'label-danger').'">'.$this->laptop_status[$v->flag_status].'</span>';
      
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
        'status'              => $s_status,
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
			$db = $this->M_laptop->delete(null, ['id' => $id]);
			if ($db) { $status = true; }
		}

		$this->output->set_content_type('application/json')->set_output(json_encode(compact('status', 'msg')));
	}

  function ajax_clone_item(){
    $status = false;
		$msg 		= [];
    $db     = false;
    
		if($id = $this->input->post('id')){
      $db = $this->M_laptop->clone_item($id);
			if ($db) { $status = true; }
    }

    if(! $db){
      $msg = 'Cannot clone this asset at this moment.';
    }

		$this->output->set_content_type('application/json')->set_output(json_encode(compact('status', 'msg')));
  }

  function ajax_get_laptop(){
    $data   = [];
    $like   = [];
    $where  = [];
    $param  = $this->input->post('param');

    if($param){
      $like = [
        'lower(l.name)'           => strtolower($param),
        'lower(l.code)'           => strtolower($param),
        'lower(l.serial_number)'  => strtolower($param),
        'lower(mo.name)'          => strtolower($param),
        'lower(b.name)'           => strtolower($param),
      ];
    }

    $join = [
      'master_model mo' => 'mo.id = l.model_id',
      'master_brand b'  => 'b.id = mo.brand_id',
    ];

    $db = $this->M_laptop->get('laptop l', $where, $join, null, ['l.name' => 'asc'], 100, null, $like, "l.*, mo.name as model_name, b.name as brand_name");
    foreach($db as $i => $v){
      $data[] = [
        'id'    => $v->id, 
        'text'  => sprintf("%s / %s (%s %s)", $v->code, $v->name, $v->brand_name, $v->model_name)
      ];
    }

    $this->output->set_content_type('application/json')->set_output(json_encode($data));
  }

  function _table_software($id = null){
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

    if($id){
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

  function _table_logs($id = null){
    // set data content
    $db = false;
    
    // set heading
    $this->table->set_heading(
      ['data' => 'Events', 'class' => 'bg-primary', 'style' => 'width:30%'],
      ['data' => 'Detail', 'class' => 'bg-primary'],
      ['data' => 'Initiator', 'class' => 'bg-primary text-center', 'style' => 'width:15%']
    );

    if($id){
      $join = ['users u' => 'u.id = h.created_by'];
      $db   = $this->M_laptop->get('laptop_history h', ['h.laptop_id' => $id], $join, 'left', ['h.created_at' => 'desc'], null, null, null, 'u.full_name, h.*');
      foreach($db as $v){
        $this->table->add_row(
          ['data' => '<b>'.$v->events.'</b><small class="clearfix">Date: '.Carbon::parse($v->created_at)->format('d/m/Y H:i').'</small>'],
          ['data' => $v->detail],
          ['data' => '<center>'.$v->full_name.'</center>']
        );
      }
    }

    if(! $db){
      $this->table->add_row(
        ['data' => '<center class="text-bold">No Logs Available For This Laptop</center>', 'colspan' => 4]
      );
    }

    return generate_table('table-logs');
  }

  function _table_handover($id = null){
    // set data content
    $db = false;
    
    // set heading
    $this->table->set_heading(
      ['data' => 'User & Cubical', 'class' => 'bg-primary', 'style' => 'width:30%'],
      ['data' => 'Location', 'class' => 'bg-primary'],
      ['data' => 'Date', 'class' => 'bg-primary text-center', 'style' => 'width:20%'],
      ['data' => 'Status', 'class' => 'bg-primary text-center', 'style' => 'width:15%']
    );

    if($id){
      $select = "
        hl.*,
        e.name as entity_name, 
        l.name as laptop_name, 
        lo.name as location_name,
        u.full_name as full_name
      ";

      $join   = [
        'laptop l'            => 'l.id = hl.laptop_id',
        'master_location lo'  => 'lo.id = hl.location_id',
        'master_entity e'     => 'e.id = lo.entity_id',
        'users u'             => 'u.id = hl.created_by',
      ];

      $db   = $this->M_laptop->get('handover_laptop hl', ['hl.laptop_id' => $id], $join, 'left', ['hl.handovered_at' => 'desc'], null, null, null, $select);
      foreach($db as $i => $v){
        // status indicator
        $s_status = ($i == 0) ? '<span class="label label-primary">CURRENT</span>' : null;
        $this->table->add_row(
          ['data' => '<b>'.$v->person_name.'</b>
                      <small class="clearfix">Cubical: '.$v->cubical_number.'</small>'],
          ['data' => '<b>'.$v->entity_name.'</b>
                      <small class="clearfix">Location: '.$v->location_name.'</small>'],
          ['data' => '<center>'.Carbon::parse($v->handovered_at)->format('d M Y - H:i').'
                      <small class="clearfix">By: '.$v->full_name.'</small></center>'],
          ['data' => '<center>'.$s_status.'</center>']
        );
      }
    }

    if(! $db){
      $this->table->add_row(
        ['data' => '<center class="text-bold">No Handover History Available For This Laptop</center>', 'colspan' => 4]
      );
    }

    return generate_table('table-handover');
  }
}
