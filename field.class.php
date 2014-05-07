<?php

/* something here for security ?*/

include 'controlla_cf.php' ; 

class profile_field_codicefiscale extends profile_field_base {

    /**
     * Overwrite the base class to display the data for this field
     */
	function edit_save_data_preprocess($data, $datarecord) {
		return strtoupper($data);
	}

	function edit_field_add($mform) {
	/// Create the form field
		$mform->addElement('text', $this->inputname, format_string($this->field->name), 'maxlength="16" size="16" ');
		$mform->setType($this->inputname, PARAM_TEXT);
/*
		if ($this->is_required() and !has_capability('moodle/user:update',  context_system::instance())) {
			$mform->addRule($this->inputname, get_string('required'), 'nonzero', null, 'client');
		}
*/
	}

    /**
     * Overwrite the base class to validate this field
     */
	function edit_validate_field($usernew) {
		    
		$errors = array();
		$errors = parent::edit_validate_field($usernew);

		if (count($errors)==0) {
		// Get input value.
			if (isset($usernew->{$this->inputname})) {
				if (is_array($usernew->{$this->inputname}) && isset($usernew->{$this->inputname}['text'])) {
					$value = $usernew->{$this->inputname}['text'];
				} else {
					$value = $usernew->{$this->inputname};
				}
			} else {
				$value = '';
			}
				$esito = controllaCF($value);
				/*  * 0 -> CF OK
					* 1 -> stringa vuota
					* 2 -> errore lunghezza CF
					* 3 -> caratteri non previsti
					* 4 -> controcodice sbagliato
				*/
			switch($esito) {
				case 0: 
					break;
				case 1:
					$errors[$this->inputname] = get_string('cf_novalue','profilefield_codicefiscale');
					break;
				case 2:
					$errors[$this->inputname] = get_string('cf_valuestrlen','profilefield_codicefiscale');
					break;
				case 3:
					$errors[$this->inputname] = get_string('cf_valuestrchar','profilefield_codicefiscale');
					break;
				case 4:
					$errors[$this->inputname] = get_string('cf_valuestrctrl','profilefield_codicefiscale');
					break;   				 				
			}  
		}
		return $errors;
	}
}
