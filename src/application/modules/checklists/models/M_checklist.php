<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_checklist extends MY_Model {

	public function __construct(){
		parent::__construct();

		$this->tabel = 'checklist';
	}

	function get_count_item($like = []){

		$this->db->select('COUNT( c.id ) AS total');
		$this->db->join('checklist_item i', 'i.checklist_id = c.id', 'left');
		$this->db->group_by('c.id');

		// bila ada like clause
		if(is_array($like) && $like){
			$is_first = true;

			// bila ada where, maka pake grouping
			$this->db->group_start();

			foreach($like as $i => $v){
				if($is_first){
						$this->db->like($i, $v);
						$is_first = false;
						
						continue;
				}

				$this->db->or_like($i, $v);
			}

			// bila ada where, maka pake grouping
			$this->db->group_end();
	}
	// end like clause

		$q = $this->db->get('checklist c');

		return ($q->result()) ?: null; 
	}

	function process_checklist($id = null, $header = [], $tasks = []){
		// variable untuk data tasks
		$task_id 			= [];
		$task_insert 	= [];
		$task_update 	= [];

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

		// START TASKS
		foreach($tasks as $i => $v){
			$temp_task = $v;
			$temp_task += ['checklist_id' => $id];

			if(is_numeric($i)){
				// masukkan id yang exist saat ini, selain ini akan dihapus
				$task_id[] 	= $i;
				
				// masukkan dalam data update
				$temp_task 		+= ['id' => $i];
				$task_update[] = $temp_task;
			}else{
				// masukkan dalam data insert
				$task_insert[] = $temp_task;
			}
		}

		if($task_update && $task_id){
			// update entry tasks ke database
			$this->db->update_batch('checklist_item', $task_update, 'id');
			
			// hapus entry tasks yang tidak ada dalam list
			$this->db->where_not_in('id', $task_id);
			$this->db->where('checklist_id', $id);
		}

		if($task_insert){
			// insert entry tasks ke database
			$this->db->insert_batch('checklist_item', $task_insert);
		}
		// END TASKS

		$this->db->trans_complete();

		return $this->db->trans_status();
	}
}