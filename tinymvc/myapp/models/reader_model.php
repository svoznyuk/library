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
			
		$res = $this->db->query_all($select, array($reader));
		
		return $res ? $res : false;
	}
	
	function get_reader_fines() {
		$date = '2012-11-01';
		$select = "SELECT `userName`, `userCity`, `userEmail`, `telephone` " .
			"FROM `reader` WHERE 1";
		
		$res = $this->db->query_all($select);
		if($res) {
			$select = "SELECT DATEDIFF('$date', `loan`.`dueDate`) AS `daysOver`, `physicalCopy`.`overdueChargePerDay` AS `charge` " .
				"FROM `loan` " .
				"JOIN `physicalCopy` ON `loan`.`catalogNo` = `physicalCopy`.`catalogNo` " .
				"WHERE `loan`.`userName` = ? " .
				"AND `loan`.`dueDate` < ? " ;
				
			$userFine = 0;
			$fine = 0;
			for($i = 0; $i < count($res); $i++) {
				
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
}

?>