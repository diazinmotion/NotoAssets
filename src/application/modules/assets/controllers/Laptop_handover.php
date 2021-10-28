<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Carbon\Carbon;

class Laptop_handover extends Management_Controller {

  private $module_path = 'assets/laptop_handover';

  function __construct(){
    parent::__construct();

    $this->load->model('M_laptop');
  }

  public function index(){
    $data['extraJs'] 	= [
      'statics/app_common.js',
      'assets/laptop_handover.js'
    ];
    $data['page_title'] = "Laptop Handover";
    $data['page_view'] 	= "V_laptop_handover";
    $data['module_url']	= base_url($this->module_path);
    
    $this->load->view('layouts/cms/V_master', $data);
  }

  function ajax_module_index(){
    $like  					= [];
    $where  				= [];
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

    $select = "
      hl.*,
      e.name as entity_name, 
      l.id as laptop_id, 
      l.name as laptop_name, 
      l.code as laptop_code, 
      lo.name as location_name,
      mo.name as model_name,
      b.name as brand_name,
      u.full_name as full_name,
      (select id from handover_laptop slh where slh.laptop_id = hl.laptop_id order by handovered_at desc limit 1) as latest_id
    ";

    $join   = [
      'laptop l'            => 'l.id = hl.laptop_id',
      'master_model mo'     => 'mo.id = l.model_id',
      'master_brand b'      => 'b.id = mo.brand_id',
      'master_location lo'  => 'lo.id = hl.location_id',
      'master_entity e'     => 'e.id = lo.entity_id',
      'users u'             => 'u.id = hl.created_by',
    ];

    $db_data  = $this->M_laptop->get('handover_laptop hl', $where, $join, 'left', ['hl.handovered_at' => 'desc'], null, null, null, $select);
    foreach($db_data as $i => $v) {

      $link_entity    = ($v->entity_name) ? anchor('/master/entity', $v->entity_name) : null;
      $link_location  = ($v->location_name) ? anchor('/master/location', $v->location_name) : null;
      $link_model     = ($v->model_name) ? anchor('/master/model', $v->brand_name.' '.$v->model_name) : null;
      $link_laptop    = ($v->laptop_code) ? anchor('/assets/laptop/edit/'.base64_encode($v->laptop_id), $v->laptop_code.' '.$v->laptop_name) : null;

      $s_status = ($v->latest_id == $v->id) ? '<span class="label label-primary">CURRENT</span>' : null;
      
      $table_content[] = [
        'user'        => $v->person_name,
        'cubical'     => $v->cubical_number,
        'model'       => $link_model,
        'entity'      => $link_entity,
        'location'    => $link_location,
        'laptop'      => $link_laptop,
        'date'        => (($v->handovered_at) ? Carbon::parse($v->handovered_at)->format('d M Y H:i') : null),
        'initiator'   => $v->full_name,
        'status'      => $s_status,
      ];
    }

    $result = [
      "total" 						=> ($db_data) ? count($db_data) : 0,
      "totalNotFiltered" 	=> ($db_data) ? count($db_data) : 0,
      "rows"              => $table_content,
    ];

    $this->output->set_content_type('application/json')->set_output(json_encode($result));
  }
}
