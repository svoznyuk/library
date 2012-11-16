<?php

class Reader_Model extends TinyMVC_Model {
	function get_readers() {
		$select = "SELECT `userName` " .
			"FROM `reader` " . 
			"WHERE 1";
		
		$res = $this->db->query_all($select);
		return $res ? $res : false;
	}
	
	function get_reader_details($reader) {
		$select = "SELECT `userName`, `userCity`, `userEmail`, `telephone` " .
			"FROM `reader` " .
			"WHERE `userName` = ? ";
			
		$res = $this->db->query_one($select, array($reader));
		
		return $res ? $res : false;
	}
	
	function get_reader_fines() {
		$date = '2012-11-01';
		$select = "SELECT `userName`, `userCity`, `userEmail`, `telephone` " .
			"FROM `reader` WHERE 1";
		
		$res = $this->db->query_all($select);
		if($res) {
			$select = "SELECT DATEDIFF('$date', `loan`.`dueDate`) AS `daysOver`, `physicalCopy`.`overdueChargePerDay` AS `charge` " .
				"FROM `Loan` " .
				"JOIN `PhysicalCopy` ON `Loan`.`catalogNo` = `PhysicalCopy`.`catalogNo` " .
				"WHERE `Loan`.`userName` = ? " .
				"AND `Loan`.`dueDate` < ? " ;
				
			$userFine = 0;
			$fine = 0;
			for($i = 0; $i < count($res); $i++) {
				$userFine = 0;
				$fines = $this->db->query_all($select, array($res[$i]['userName'], $date));
				for($j = 0; $j < count($fines); $j++) {
					$fine = $fines[$j]['daysOver'] * $fines[$j]['charge'];
					$userFine += $fine;
				}
				
				$res[$i]['totalFines'] = $userFine;
			}
		} else {
			return false;
		}
		
		return $res;
	}
	
	function add_reader($reader = array()) {
		return $this->db->insert('Reader', $reader);
	}
	
	function update_reader($reader = array()) {
		$this->db->where('`userName`', $reader['userName']);
		return $this->db->update('Reader', $reader);
	}
}

?>