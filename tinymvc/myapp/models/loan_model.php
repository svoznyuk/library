<?php

class Loan_Model extends TinyMVC_Model {

	function get_reader_loans ($reader) {
		$select = "SELECT `loan`.`catalogNo`, `physicalCopy`.`title`, `loan`.`dateIn`, `loan`.`dueDate`, `physicalCopy`.`overdueChargePerDay` as `charge` " .
			"FROM `physicalCopy` " .
			"JOIN `loan` ON `physicalCopy`.`catalogNo` = `loan`.`catalogNo` " . 
			"WHERE `loan`.`userName` = ? " .
			"ORDER BY `loan`.`dueDate` DESC ";
			
		$res = $this->db->query_all($select, array($reader));
		return $res ? $res : false;
	}

}

?>