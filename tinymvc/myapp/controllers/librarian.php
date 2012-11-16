<?php

class Librarian_Controller extends TinyMVC_Controller 
{
	function reports() {
		$body = "<div><h4>Library Reports</h4><form method='post' action=''><label for='report_type'>Choose Report</label>" . 
			"<select name='report_type'>".
				"<option value='numCopies'>Copies on hand</option>" .
				"<option value='book'>Book Report</option>" .
				"<option value='dueDate'>Due Dates</option>" .
				"<option value='reader'>Reader Fines</option>" .
			"</select><input type='submit' name='report' value='Generate Report' /></div><div>";
		if(isset($_POST['report'])) {
			$type = $_POST['report_type'];
			switch($type) {
				case 'numCopies':
					$body .= $this->get_num_copies();
					break;
				case 'book':
					$body .= $this->get_book_info();
					break;
				case 'dueDate':
					$body .= $this->get_due_dates();
					break;
				case 'reader':
					$body .= $this->get_reader_fines();
					break;
				default:
					$body .= '<p>Unknown report type.</p>';
					break;
			}
		}
		$body .= "</div>";
		$this->normal_view($body);
	}
	
	function update() {
		if(isset($_POST['newUserSubmit'])) {
			$user_info = array();
			$user_info['userName'] = $_POST['userName'];
			$user_info['userCity'] = $_POST['userCity'];
			$user_info['userEmail'] = $_POST['userEmail'];
			$user_info['telephone'] = $_POST['telephone'];
			
			$this->load->model('reader_model', 'reader');
			$res = $this->reader->add_reader($user_info);
			$body = "<h4>Add User</h4>";
			if($res !== false) {
				$body .= "<p>User added.</p>";
			} else {
				$body .= "<p>Could not add user.</p>";
			}
		} else if(isset($_POST['newBookSubmit'])) {
			$book_info = array();
			$book_info['ISBN'] = $_POST['isbn'];
			$book_info['title'] = $_POST['title'];
			$book_info['author'] = $_POST['author'];
			$book_info['publisher'] = $_POST['publisher'];
			
			$this->load->model('book_model', 'book');
			$res = $this->book->add_book($book_info);
			$body = "<h4>Add Book</h4>";
			if($res !== false) {
				$body .= "<p>Book added.</p>";
			} else {
				$body .= "<p>Could not add book.</p>";
			}
		} else if(isset($_POST['addCopy'])) {
			$title = $_POST['selectBook'];
			$body = "<div><h4>Add Book Copy</h4><form method='post' action=''>" .
				"<input type='hidden' name='title' value='$title' />" .
				"<div>Catalog # <input type='text' name='catalogNo' /></div>" .
				"<div>Overdue Charge <input type='text' name='charge' /></div>" .
				"<input type='submit' value='Add' name='addCopyFinal' />";
		} else if(isset($_POST['addCopyFinal'])) {
			$copy_info = array();
			$copy_info['title'] = $_POST['title'];
			$copy_info['catalogNo'] = $_POST['catalogNo'];
			$copy_info['overdueChargePerDay'] = $_POST['charge'];
			
			$body = "<div><h4>Add Book Copy</h4>";
			$this->load->model('physicalcopy_model', 'physical');
			$res = $this->physical->add_copy($copy_info);
			
			if($res !== false) {
				$body .= "<p>Added copy.</p>";
			} else {
				$body .= "<p>Could not add copy.</p>";
			}
		} else if(isset($_POST['modCharge'])) {
			$catalog_num = $_POST['selectBook'];
			$this->load->model('physicalcopy_model', 'physical');
			$charge = $this->physical->get_charge($catalog_num);
			$body = "<div><h4>Modify Charge</h4><form method='post' action=''>".
				"<div><label for='charge'>Overdue Charge: </label><input type='text' name='charge' value='".$charge['charge']."' /></div>" .
				"<input type='hidden' name='catalogNo' value='$catalog_num' />" .
				"<input type='submit' name='modChargeFinal' value='Save' /></form></div>";
		} else if(isset($_POST['modChargeFinal'])) {
			$new_charge = $_POST['charge'];
			$catalog_num = $_POST['catalogNo'];
			$this->load->model('physicalcopy_model', 'physical');
			$res = $this->physical->modify_charge($catalog_num, $new_charge);
			
			$body = "<h4>Modify Charge</h4>";
			if($res !== false) {
				$body .= "<p>Set overdue charge to $new_charge for Catalog # $catalog_num.</p>";
			} else {
				$body .= "<p>Could not change overdue charge.</p>";
			}
		} else {
			$body = "<div><h4>Library Reports</h4><form method='post' action=''><label for='report_type'>Choose Report</label>" . 
				"<select name='update_type'>".
					"<option value='newUser'>Add User</option>" .
					"<option value='newBook'>Add Book</option>" .
					"<option value='newCopy'>Add Copy</option>" .
					"<option value='modCharge'>Modify Charge</option>" .
				"</select><input type='submit' name='update' value='Go' /></div><div>";
			if(isset($_POST['update'])) {
				$type = $_POST['update_type'];
				switch($type) {
					case 'newUser' :
						$body .= $this->add_user();
						break;
					case 'newBook' :
						$body .= $this->add_book();
						break;
					case 'newCopy' :
						$body .= $this->add_copy();
						break;
					case 'modCharge' :
						$body .= $this->mod_charge();
						break;
					default:
						$body .= "<p>Select one of the options.</p>";
				}
			}
			$body .= "</div>";
		}
		$this->normal_view($body);
	}
	
	private function add_user() {
		$body = "<div><h4>Add User</h4><form method='post' action=''>" .
			"<div><label for='userName'>User Name</label><input type='text' name='userName' /></div>" .
			"<div><label for='userCity'>User City</label><input type='text' name='userCity' /></div>" .
			"<div><label for='userEmail'>User E-Mail</label><input type='text' name='userEmail' /></div>" .
			"<div><label for='telephone'>Telephone #</label><input type='text' name='telephone' /></div>" .
			"<input type='submit' name='newUserSubmit' value='Add User' /></form></div>";
		return $body;
	}
	
	private function add_book() {
		$body = "<div><h4>Add Book</h4><form method='post' action=''>" .
			"<div><label for='isbn'>ISBN</label><input type='text' name='isbn' /></div>" .
			"<div><label for='title'>Title</label><input type='text' name='title' /></div>" .
			"<div><label for='author'>Author</label><input type='text' name='author' /></div>" .
			"<div><label for='publisher'>Publisher</label><input type='text' name='publisher' /></div>" .
			"<input type='submit' name='newBookSubmit' value='Add Book' /></form></div>";
		return $body;
	}
	
	private function add_copy() {
		$this->load->model('book_model', 'book');
		$books = $this->book->get_all();
		if($books !== false) {
			$body = "<div><h4>Add Copy</h4><form method='post' action=''>".
				"<label for='selectBook'>Select Book</label><select name='selectBook'>";
			foreach($books as $book) {
				$body .= "<option value='".$book['title']."'>".$book['title']."</option>";
			}
			$body .= "</select><input type='submit' name='addCopy' value='Add' /></form></div>";
			return $body;
		} else {
			return "<p>Could not get books.</p>";
		}
	}
	
	private function mod_charge() {
		$this->load->model('physicalcopy_model', 'physical');
		$copies = $this->physical->get_all();
		$body = "<div><h4>Modify charge per day</h4><form method='post' action=''>" .
			"<label for='selectBook'>Select Book</label><select name='selectBook'>";
		foreach($copies as $copy) {
			$body .= "<option value='".$copy['catalogNo']."'>".$copy['catalogNo'].' '.$copy['title']."</option>";
		}
		$body .= "<input type='submit' name='modCharge' value='Go' /></form></div>";
		return $body;
	}
	
	private function get_num_copies() {
		$this->load->model('physicalcopy_model', 'physical');
		$copies = $this->physical->get_available_copies();
		$body = "<h4>Available Copies</h4><table><tr><th>Catalog #</th><th>Title</th><th>Overdue Charge</th></tr>";
		foreach($copies as $copy) {
			$body .= "<tr><td>".$copy['catalogNo']."</td><td>".$copy['title']."</td><td>".$copy['charge']."</td></tr>";
		}
		$body .= "</table>";
		return $body;
	}
	
	private function get_book_info() {
		$this->load->model('physicalcopy_model', 'physical');
		$books = $this->physical->get_number_of_copies();
		if($books !== false) {
			$body = "<h4>Book Info</h4><table><tr><th>ISBN</th><th>Title</th><th>Author</th><th>Publisher</th><th># of Copies</th></tr>";
			foreach($books as $book) {
				$body .= "<tr><td>".$book['ISBN']."</td><td>".$book['title']."</td><td>".$book['author']."</td>" . 
					"<td>" . $book['publisher'] . "</td><td>" . $book['count'] . "</td></tr>";
			}
			$body .= "</table>";
		} else {
			$body = "<p>Could not find books.";
		}
		return $body;
	}
	
	private function get_due_dates() {
		$this->load->model('physicalcopy_model', 'physical');
		$books = $this->physical->get_due_date();
		$body = "<h4>Due Dates</h4><table><tr><th>Catalog #</th><th>Title</th><th>Overdue Charge</th><th>Due Date</th></tr>";
		foreach($books as $book) {
			$body .= "<tr><td>".$book['catalogNo']."</td><td>".$book['title']."</td><td>".$book['charge']."</td><td>".$book['dueDate']."</td></tr>";
		}
		$body .= "</table>";
		return $body;
	}
	
	private function get_reader_fines() {
		$this->load->model('reader_model', 'reader');
		$readers = $this->reader->get_reader_fines();
		$body = "<h4>Reader fines</h4><table><tr><th>User Name</th><th>City</th><th>E-Mail</th><th>telephone</th><th>Total Fines</th></tr>";
		foreach($readers as $reader) {
			$body .= "<tr><td>".$reader['userName']."</td><td>".$reader['userCity']."</td><td>".$reader['userEmail']."</td><td>".$reader['telephone']."</td>".
				"<td>" . $reader['totalFines'] . "</td></tr>";
		}
		$body .= "</table>";
		return $body;
	}
	
	private function normal_view($body) {
		$this->view->assign('body', $body);
		$this->view->display('main_view');
	}
}

?>