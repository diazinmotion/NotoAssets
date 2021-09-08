<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(! function_exists('current_user_session')){
  function current_user_session($item = null){  
    $CI           =& get_instance();
    $session_item = $CI->session->userdata(APP_SESSION_NAME);

    return (isset($session_item[$item]) ? $session_item[$item] : false);
  }
}

if(! function_exists('format_date_to_db')){
  function format_date_to_db($str = null){
    $output = [];

    // pecah tanggal menjadi format bulan tahun tanggal
    $ex = explode('-', $str);
    if($ex && count($ex) == 3){
      $output = [$ex[2], $ex[1], $ex[0]];
    }

    return implode('-', $output);
  }
}

if(! function_exists('generate_table')){
  function generate_table($identifier = null){

    $CI =& get_instance();

    $template = array(
      'table_open'            => '<div class="table-responsive"><table class="table table-condensed table-bordered table-striped">',
      'thead_open'            => '<thead class="bg-danger">',
      'thead_close'           => '</thead>',
      'heading_row_start'     => '<tr>',
      'heading_row_end'       => '</tr>',
      'heading_cell_start'    => '<th style="vertical-align:middle;">',
      'heading_cell_end'      => '</th>',
      'tbody_open'            => '<tbody id="'.$identifier.'">',
      'tbody_close'           => '</tbody>',
      'row_start'             => '<tr class="'.$identifier.'-child">',
      'row_end'               => '</tr>',
      'cell_start'            => '<td style="vertical-align:middle;">',
      'cell_end'              => '</td>',
      'row_alt_start'         => '<tr class="'.$identifier.'-child">',
      'row_alt_end'           => '</tr>',
      'cell_alt_start'        => '<td style="vertical-align:middle;">',
      'cell_alt_end'          => '</td>',    
      'table_close'           => '</table></div>'
    );
  
    $CI->table->set_template($template);
  
    return $CI->table->generate();
  }
}