<?php
class Patron extends TinyMVC_Controller {
	function patron_report() {
		$this->load->model('reader_model', 'reader');
		$readers = $this->reader->get_readers();
		$body = "<div><label for='selectUsers'>Select Patron</label>" .
			"<select id='selectUsers'>";
		foreach($readers as $reader) {
			$body .= "<option value='".$reader['reader']."'>". $reader['reader'] . "</option>";
		}
		$body .= "</select></div>";
		$this->normal_view($body);
	}
	
	private function normal_view($body) {
		
	}
	
	private function json_view($response) {
		$response = json_encode($response);
		
	}
}

?>