<?php

class Book_Model extends TinyMVC_Model {
	function add_book($book = array()) {
		return $this->db->insert('Book', $book);
	}
}

?>