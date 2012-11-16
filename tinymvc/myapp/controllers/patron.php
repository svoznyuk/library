<?php
class Patron_Controller extends TinyMVC_Controller {
	function patron_report() {
		$body = $this->load_readers('Patron Report');
		if(isset($_POST['plookup'])) {
			$name = preg_replace('/[^a-z]/i', '', $_POST['patron']);
			$body .= $this->get_patron_details($name);
		}
		$this->normal_view($body);
	}
	
	function patron_info() {
		$body = $this->load_readers('Patron Info');
		if(isset($_POST['update'])) {
			$user_data = array();
			$user_data['userName'] = $_POST['userName'];
			$user_data['userCity'] = $_POST['userCity'];
			$user_data['userEmail'] = $_POST['userEmail'];
			$user_data['telephone'] = $_POST['telephone'];
			$res = $this->reader->update_reader($user_data);
			if($res !== false) {
				$body .= '<p>Patron information updated.</p>';
			} else {
				$body .= '<p>Update failed, please try again.</p>';
			}
		} 
		if(isset($_POST['plookup'])) {
			$name = preg_replace('/[^a-z]/i', '', $_POST['patron']);
			$partron_details = $this->reader->get_reader_details($name);
			$body .= "<form action='' method='post'>" .
				"<input type='hidden' name='plookup' value='again' />".
				"<input type='hidden' name='patron' value='$name' />".
				"<table><tr><th>User Name</th><th>City</th><th>E-mail</th><th>Phone #</th></tr>".
				"<tr><td><input type='text' name='userName' value='".$partron_details['userName']."' /></td>". 
				"<td><input type='text' name='userCity' value='".$partron_details['userCity']."' /></td>" . 
				"<td><input type='text' name='userEmail' value='".$partron_details['userEmail'] . "' /></td>" . 
				"<td><input type='text' name='telephone' value='" .$partron_details['telephone'] . "' /></td></tr>".
				"</table><input type='submit' name='update' value='Update' /></form>";
		}
		$this->normal_view($body);
	}
	
	private function load_readers($heading) {
		$this->load->model('reader_model', 'reader');
		$readers = $this->reader->get_readers();
		$body = "<h4>$heading</h4>";
		$body .= "<div><form action='' method='post'><label for='selectUsers'>Select Patron</label>" .
			"<select name='patron' id='selectUsers'>";
		foreach($readers as $reader) {
			$body .= "<option value='".$reader['userName']."'>". $reader['userName'] . "</option>";
		}
		$body .= "</select><input type='submit' name='plookup' value='Look Up' /></form></div>";
		return $body;
	}
	
	private function normal_view($body) {
		$this->view->assign('body', $body);
		$this->view->display('main_view');
	}
	

	private function get_patron_details($name) {
		$this->load->model('loan_model', 'loan');
		$res = $this->loan->get_reader_loans($name);
		if($res) {
			$table = "<p>Patron name: $name</p>";
			$date = '2012-11-01';
			$table .= "<table><tr><th>Catalog #</th><th>Title</th><th>Date In</th><th>Due Date</th><th>Current Fine</th></tr>";
			foreach($res as $checkout) {
				$due_date = strtotime($checkout['dueDate']);
				$date_in = $checkout['dateIn'] ? strtotime($checkout['dateIn']) : strtotime($date);
				$fine = 0;
				if($date_in > $due_date) {
					$diff = $date_in - $due_date;
					$diff = floor($diff/(60*60*24));
					$fine = $checkout['charge'] * $diff;
				}
				$table .= "<tr><td>".$checkout['catalogNo']."</td><td>".$checkout['title']."</td><td>".
					$checkout['dateIn']."</td><td>".$checkout['dueDate']."</td><td>".$fine."</td></tr>";
			}
			$table .= "</table>";
			return $table;
		} else {
			return "No checkouts found for $name";
		}
	}
}

?>