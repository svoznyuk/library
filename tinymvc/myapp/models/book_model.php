<?php

class Book_Model extends TinyMVC_Model {
	function get_all() {
		$select = "SELECT `ISBN`, `title` FROM `Book` WHERE 1";
		$res = $this->db->query_all($select);
		return $res ? $res : false;
	}
	
	function add_book($book = array()) {
		return $this->db->insert('Book', $book);
	}
}

?>