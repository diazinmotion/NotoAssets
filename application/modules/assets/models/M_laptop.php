<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Carbon\Carbon;

class M_laptop extends MY_Model {

	private $laptop_status	= [
		0 => 'DECOMISSIONED',
		1 => 'NORMAL',
		2 => 'IN SERVICE',
		3 => 'BROKEN',
		4 => 'NEW',
	];

	public function __construct(){
		parent::__construct();

		$this->tabel = 'laptop';
	}

	function get_laptop_checklist($id_laptop = null){
		$this->db->select("
			l.*,
			c.name as checklist_name,
			ci.id as checklist_item_id,
			GROUP_CONCAT(concat(COALESCE(ci.id, 0),'|',COALESCE(cl.id, 0),'|', cl.checklist_laptop_id,'|', ci.name,'|', COALESCE(cl.has_done, 'N')) SEPARATOR '$') as task_name
		");
		$this->db->join('checklist c', 'c.id = l.checklist_id', 'left');
		$this->db->join('checklist_item ci', 'ci.checklist_id = c.id', 'left');
		$this->db->join('checklist_laptop_status cl', 'cl.checklist_item_id = ci.id and cl.checklist_laptop_id = l.id','left');
		$this->db->where('laptop_id', $id_laptop);
		$this->db->group_by('l.id');

		$q = $this->db->get('checklist_laptop l');

		return $q->result() ?: [];
	}

	function clone_item($id = null){
		$new_id_laptop = null;
		
		$this->db->trans_start();

		// dapatkan data laptop dan lakukan insert
		$h = $this->db->query("
			INSERT INTO laptop(
				code,
				name,
				entity_id,
				location_id,
				serial_number,
				os_type_id,
				os_product_key,
				pki_email,
				pki_password,
				encryption_password,
				encryption_recovery_file,
				storage_type_id,
				storage_type_brand,
				storage_size,
				memory_type_id,
				memory_brand,
				memory_size,
				account_type_id,
				account_email,
				flag_status,
				created_at,
				created_by,
				updated_at,
				updated_by,
				purchased_at,
				warranty_expired,
				model_id,
				reference_id
			) SELECT
				concat('C-', code) as code,
				concat('Copy Of ', name) as name,
				entity_id,
				location_id,
				concat('C-', serial_number) as serial_number,
				os_type_id,
				os_product_key,
				pki_email,
				pki_password,
				encryption_password,
				null as encryption_recovery_file,
				storage_type_id,
				storage_type_brand,
				storage_size,
				memory_type_id,
				memory_brand,
				memory_size,
				account_type_id,
				account_email,
				flag_status,
				now() as created_at,
				".current_user_session('id')." as created_by,
				null as updated_at,
				null as updated_by,
				purchased_at,
				warranty_expired,
				model_id,
				{$id}
			FROM
				laptop 
			WHERE
				id = {$id};
		");
		
		if($h){
			$this->db->where('reference_id', $id);
			$this->db->order_by('id', 'desc');
			$g = $this->db->get('laptop');
			if($g){
				$new_id_laptop = $g->result()[0]->id;

				// masukkan data software dan lisensi dari aset ini
				$this->db->query("
					INSERT INTO license_seat(
						license_id,
						laptop_id,
						expiration_at,
						installed_at,
						product_key,
						created_at,
						updated_at,
						created_by,
						updated_by
					) SELECT 
						license_id,
						{$new_id_laptop} as laptop_id,
						expiration_at,
						now() as installed_at,
						product_key,
						now() as created_at,
						null as updated_at,
						".current_user_session('id')." as created_by,
						null as updated_by
					FROM license_seat WHERE laptop_id = {$id};
				");

				// masukkan data checklist
				$cl = $this->db->query("
					INSERT INTO checklist_laptop(
						checklist_id,
						laptop_id
					)
					SELECT 
						checklist_id,
						{$new_id_laptop} as laptop_id
					FROM checklist_laptop 
					WHERE laptop_id = {$id};
				");

				if($cl){
					// loop data id dari laptop yang barusan dibuat
					$this->db->where('laptop_id', $new_id_laptop);
					$c = $this->db->get('checklist_laptop');
					if($c){
						foreach ($c->result() as $v) {
							// dapatkan masing-masing item dari checklist ini
							$this->db->where('checklist_id', $v->checklist_id);
							$item = $this->db->get('checklist_item');
							if($item && $item->result()){
								// looping item dari masing masing checklist
								$temp = [];
								foreach ($item->result() as $v_item) {
									$temp[] = [
										'created_at'					=> Carbon::now(),
										'created_by'					=> current_user_session('id'),
										'has_done'						=> 'N',
										'checklist_item_id'		=> $v_item->id,
										'checklist_laptop_id'	=> $v->id,
									];
								}

								$this->db->insert_batch('checklist_laptop_status', $temp);
							}
						}
					}
				}
			}
		}

		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	function proccess_data($id = null, $header = [], $software = [], $laptop_checklist = [], $checklist = [], $handover = []){
		// variable untuk data software
		$software_id 							= [];
		$software_insert 					= [];
		$software_update 					= [];
		$original_flag_status			= null;

		$this->db->trans_start();

		// ORIGINAL DATA
		$original_flag_status = $header['flag_status_original'];
		$original_code 				= $header['code_original'];

		unset(
			$header['flag_status_original'],
			$header['code_original'],
		);
		// END ORIGINAL DATA
		
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
			$this->db->delete('license_seat');
		}

		if($software_insert){
			// insert entry software ke database
			$this->db->insert_batch('license_seat', $software_insert);
		}
		// END SOFTWARE

		// START LAPTOP CHECKLIST INSERT BARU
		foreach($laptop_checklist as $i => $v){
			$temp = $v;
			$temp += ['laptop_id' => $id];

			// insert entry laptop checklist ke database
			$this->db->insert('checklist_laptop', $temp);
			$id_checlist_laptop = $this->db->insert_id();
			if($id_checlist_laptop){
				// dapatkan task dari masing-masing checklist ini
				$this->db->where('checklist_id', $v['checklist_id']);
				$db = $this->db->get('checklist_item');
				if($db){
					$temp_checklist_status = [];
					foreach ($db->result() as $v_item) {
						$temp_checklist_status[] = [
							'checklist_laptop_id' => $id_checlist_laptop,
							'checklist_item_id' 	=> $v_item->id,
							'has_done'						=> 'N'
						];
					}

					// insert checklist laptop status
					$this->db->insert_batch('checklist_laptop_status', $temp_checklist_status);
				}
			}
		}
		// END LAPTOP CHECKLIST INSERT BARU

		// START CHECKLIST STATUS UPDATE
		if($checklist){
			$this->db->update_batch('checklist_laptop_status', $checklist, 'id');
		}
		// END CHECKLIST STATUS

		// START INSERT LOG
		// hanya yang statusnya berubah saja
		if($original_flag_status != $header['flag_status']){
			$data_log = [
				'events' 			=> 'CHANGE: Asset Status',
				'detail' 			=> 'The laptop STATUS has been changed from '.$this->laptop_status[$original_flag_status].' -> '.$this->laptop_status[$header['flag_status']],
				'created_by' 	=> current_user_session('id'),
				'created_at' 	=> Carbon::now(),
				'laptop_id' 	=> $id,
				'category' 		=> '1',
			];

			$this->db->insert('laptop_history', $data_log);
		}

		if($original_code != $header['code']){
			$data_log = [
				'events' 			=> 'CHANGE: Asset Barcode',
				'detail' 			=> 'The BARCODE/CODE has been changed from '.$original_code.' -> '.$header['code'],
				'created_by' 	=> current_user_session('id'),
				'created_at' 	=> Carbon::now(),
				'laptop_id' 	=> $id,
				'category' 		=> '1',
			];

			$this->db->insert('laptop_history', $data_log);
		}
		// END INSERT LOG

		// START HANDOVER
		if($handover){
			$temp = $handover;
			$temp += ['laptop_id' => $id];

			// insert ke tabel handover_laptop
			$this->db->insert('handover_laptop', $temp);
		}
		// END HANDOVER

		$this->db->trans_complete();

		return $this->db->trans_status();
	}
}