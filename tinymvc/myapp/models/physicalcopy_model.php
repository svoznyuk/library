<?php

class PhysicalCopy_Model extends TinyMVC_Model {
	function get_available_copies() {
		$select = "SELECT `physicalCopy`.`catalogNo`, `physicalCopy`.`title`, `physicalCopy`.`overdueChargePerDay` AS `charge` ".
			"FROM `physicalCopy` " . 
			"WHERE `catalogNo` NOT IN ( " .
				"SELECT `catalogNo` FROM `loan` WHERE 1" .
			")";
			
		$res = $this->db->query_all($select);
		return $res ? $res : false;
	}
	
	function get_number_of_copies() {
		$select = "SELECT `ISBN`, `title`, `author`, `publisher` " .
			"FROM `book` WHERE 1";
		
		$res = $this->db->query_all($select);
		if($res) {
			$select = "SELECT COUNT(`title`) AS `numBooks` " .
				"FROM `physicalCopy` " .
				"WHERE `title` = ? " .
				"GROUP BY `title` ";
			$phys = array();
			
			for($i = 0; $i < count($res); $i++) {
				$phys = $this->db->query_one($select, array($res[$i]['title']));
				if($phys) {
					$res[$i]['count'] = $phys['numBooks'];
				} else {
					return false;
				}
			}
		} else {
			return false;
		}
		
		return $res;
	}
	
	function get_due_date() {
		$select = "SELECT `p`.`catalogNo`, `p`.`title`, `p`.`overdueChargePerDay` AS `charge`, `loan`.`dueDate` " .
			"FROM `physicalCopy` AS `p` " . 
			"JOIN `loan` ON `p`.`catalogNo` = `loan`.`catalogNo` " .
			"WHERE `loan`.`dateIn` IS NOT NULL " .
			"ORDER BY `p`.`catalogNo` ASC";
			
		$res = $this->db->query_all($select);
		return $res ? $res : false;
	}
}

?>