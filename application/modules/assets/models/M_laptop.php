<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_laptop extends MY_Model {

	public function __construct(){
		parent::__construct();

		$this->tabel = 'laptop';
	}

	function proccess_data($id = null, $header = [], $software = []){
		// variable untuk data software
		$software_id 			= [];
		$software_insert 	= [];
		$software_update 	= [];

		$this->db->trans_start();
		
		// START HEADER
		if($id){
			// update
			$this->update(null, ['id' => $id], $header);
		}else{
			// insert
			$id = $this->insert(null, $header);
		}
		// END HEADER

		// START SOFTWARE
		foreach($software as $i => $v){
			$temp_software = $v;
			$temp_software += ['laptop_id' => $id];

			if(is_numeric($i)){
				// masukkan id yang exist saat ini, selain ini akan dihapus
				$software_id[] 	= $i;
				
				// masukkan dalam data update
				$temp_software 		+= ['id' => $i];
				$software_update[] = $temp_software;
			}else{
				// masukkan dalam data insert
				$software_insert[] = $temp_software;
			}
		}

		if($software_update && $software_id){
			// update entry software ke database
			$this->db->update_batch('license_seat', $software_update, 'id');
			
			// hapus entry software yang tidak ada dalam list
			$this->db->where_not_in('id', $software_id);
			$this->db->where('laptop_id', $id);
		}

		if($software_insert){
			// insert entry software ke database
			$this->db->insert_batch('license_seat', $software_insert);
		}
		// END SOFTWARE

		$this->db->trans_complete();

		return $this->db->trans_status();
	}
}