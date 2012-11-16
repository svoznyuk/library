<?php

class PhysicalCopy_Model extends TinyMVC_Model {
	function get_all() {
		$select = "SELECT `PhysicalCopy`.`catalogNo`, `PhysicalCopy`.`title`, `PhysicalCopy`.`overdueChargePerDay` AS `charge` ".
			"FROM `PhysicalCopy` WHERE 1";
		
		$res = $this->db->query_all($select);
		return $res ? $res : false;
	}
	
	function get_available_copies() {
		$select = "SELECT `PhysicalCopy`.`catalogNo`, `PhysicalCopy`.`title`, `PhysicalCopy`.`overdueChargePerDay` AS `charge` ".
			"FROM `PhysicalCopy` " . 
			"WHERE `catalogNo` NOT IN ( " .
				"SELECT `catalogNo` FROM `loan` WHERE 1" .
			")";
			
		$res = $this->db->query_all($select);
		return $res ? $res : false;
	}
	
	function get_number_of_copies() {
		$select = "SELECT `ISBN`, `title`, `author`, `publisher` " .
			"FROM `Book` WHERE 1";
		
		$res = $this->db->query_all($select);
		if($res) {
			$select = "SELECT COUNT(`title`) AS `numBooks` " .
				"FROM `PhysicalCopy` " .
				"WHERE `title` = ? " .
				"GROUP BY `title` ";
			$phys = array();
			
			for($i = 0; $i < count($res); $i++) {
				$phys = $this->db->query_one($select, array($res[$i]['title']));
				if($phys) {
					$res[$i]['count'] = $phys['numBooks'];
				} else {
					$res[$i]['count'] = 0;
				}
			}
		} else {
			return false;
		}
		
		return $res;
	}
	
	function get_due_date() {
		$select = "SELECT `p`.`catalogNo`, `p`.`title`, `p`.`overdueChargePerDay` AS `charge`, `loan`.`dueDate` " .
			"FROM `PhysicalCopy` AS `p` " . 
			"JOIN `Loan` ON `p`.`catalogNo` = `Loan`.`catalogNo` " .
			"WHERE `Loan`.`dateIn` IS NOT NULL " .
			"ORDER BY `p`.`catalogNo` ASC";
			
		$res = $this->db->query_all($select);
		return $res ? $res : false;
	}
	
	function add_copy($book_copy = array()) {
		return $this->db->insert('PhysicalCopy', $book_copy);
	}
	
	function get_charge($cat_num) {
		$select = "SELECT `overdueChargePerDay` as `charge` FROM `PhysicalCopy` WHERE `catalogNo` = ?";
		$res = $this->db->query_one($select, array($cat_num));
		return $res ? $res : false;
	}
	
	function modify_charge($cat_num, $new_charge) {
		$this->db->where('catalogNo', $cat_num);
		return $this->db->update('PhysicalCopy', array('overdueChargePerDay' => $new_charge));
	}
}

?>