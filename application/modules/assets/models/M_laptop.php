<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_laptop extends MY_Model {

	public function __construct(){
		parent::__construct();

		$this->tabel = 'laptop';
	}

	function get_laptop_checklist($id_laptop = null){
		$this->db->select("
			l.*,
			c.name as checklist_name,
			ci.id as checklist_item_id,
			GROUP_CONCAT(concat(COALESCE(ci.id, 0),'|',COALESCE(cl.id, 0),'|', ci.name,'|', COALESCE(cl.has_done, 'N')) SEPARATOR '$') as task_name
		");
		$this->db->join('checklist c', 'c.id = l.checklist_id', 'left');
		$this->db->join('checklist_item ci', 'ci.checklist_id = c.id', 'left');
		$this->db->join('checklist_laptop_status cl', 'cl.checklist_item_id = ci.id and cl.checklist_laptop_id = l.id','left');
		$this->db->where('laptop_id', $id_laptop);
		$this->db->group_by('l.id');

		$q = $this->db->get('checklist_laptop l');

		return $q->result() ?: [];
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