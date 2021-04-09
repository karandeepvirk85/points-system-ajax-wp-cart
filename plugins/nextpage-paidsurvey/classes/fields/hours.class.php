<?php

if( ! defined( 'ABSPATH' ) ) exit;

class Cuztom_Field_Hours extends Cuztom_Field
{
	function _output( $value )
	{
		if(!empty($value)){
			$save_value = htmlspecialchars(json_encode($value));
		}else{
			$save_value = '{}';
			$value = array();
		}

		$days = array(
			'monday' => 'Monday',
			'tuesday' => 'Tuesday',
			'wednesday' => 'Wednesday',
			'thursday' => 'Thursday',
			'friday' => 'Friday',
			'saturday' => 'Saturday',
			'sunday' => 'Sunday',
			'holidays' => 'Holidays'
		);

		$hours = array();
		for($i = 1; $i <= 12; $i++){
			array_push($hours, $i);
		}

		$minutes = array();
		for($i = 0; $i <= 59; $i++){
			array_push($minutes, $i);
		}

		$meridiems = array(
			'am',
			'pm'
		);

		$html = '<div class="hours">'
			. '<input type="hidden" ' .  $this->output_name() . ' value="' . $save_value . '" />'
			. '<table class="form-table sh-hours-table wp-list-table widefat fixed">'
			. '<thead>'
		    . '<tr valign="top">'
		    	. '<th>Day</th><th></th><th>Open</th><th>Close</th>'
		    . '</tr>'
		    . '</thead><tbody class="the-list">';

		$i = 0;
		foreach($days as $day => $label){
			$strOddClass = '';
			if(($i % 2) != 0){
				$strOddClass = 'alternate';
			}

			$strClosed = '';
			$strDisabled = '';
			if(!empty($value[$day]['closed']) && $value[$day]['closed'] == 1){
				$strClosed = 'checked="checked"';
				$strDisabled = 'disabled="disabled"';
			}

			$html .= '<tr valign="top" class="' . $strOddClass . '" data-day="' . $day . '">'
		    		. '<th>' . $label . '</th>'
		    		. '<td><label><input type="checkbox" class="closed" value="1" ' . $strClosed . ' /> Closed</label></td>'
			    	. '<td class="time-selection start">'
			    		. '<select class="start-hour hour" ' . $strDisabled . '>';

			    		foreach($hours as $hour){
			    			$selectedClass = '';
			    			if(!empty($value[$day]) && $hour == $value[$day]['start']['hour']){
			    				$selectedClass = 'selected="selected"';
			    			}

			    			$html .= '<option value="' . $hour . '" ' . $selectedClass . '>' . $hour . '</option>';
			    		}

			     $html .= '</select>:'
			     		. '<select class="start-minute minute" ' . $strDisabled . '>';

			    		foreach($minutes as $minute){
			    			$selectedClass = '';
			    			if(!empty($value[$day]) && $minute == $value[$day]['start']['minute']){
			    				$selectedClass = 'selected="selected"';
			    			}

			    			$html .= '<option value="' . $minute . '" ' . $selectedClass . '>' . str_pad($minute, 2, "0", STR_PAD_LEFT) . '</option>';
			    		}

			     $html .= '</select>'
			     		. '<select class="start-meridiem meridiem" ' . $strDisabled . '>';

			    		foreach($meridiems as $meridiem){
			    			$selectedClass = '';
			    			if(!empty($value[$day]) && $meridiem == $value[$day]['start']['meridiem']){
			    				$selectedClass = 'selected="selected"';
			    			}

			    			$html .= '<option value="' . $meridiem . '" ' . $selectedClass . '>' . $meridiem . '</option>';
			    		}

			     $html .= '</select>'
			    	. '</td>'
			    	. '<td class="time-selection end">'
			    		. '<select class="end-hour hour" ' . $strDisabled . '>';

			    		foreach($hours as $hour){
			    			$selectedClass = '';
			    			if(!empty($value[$day]) && $hour == $value[$day]['end']['hour']){
			    				$selectedClass = 'selected="selected"';
			    			}

			    			$html .= '<option value="' . $hour . '" ' . $selectedClass . '>' . $hour . '</option>';
			    		}

			     $html .= '</select>:'
			     		. '<select class="end-minute minute" ' . $strDisabled . '>';

			    		foreach($minutes as $minute){
			    			$selectedClass = '';
			    			if(!empty($value[$day]) && $minute == $value[$day]['end']['minute']){
			    				$selectedClass = 'selected="selected"';
			    			}

			    			$html .= '<option value="' . $minute . '" ' . $selectedClass . '>' . str_pad($minute, 2, "0", STR_PAD_LEFT) . '</option>';
			    		}

			     $html .= '</select>'
			     		. '<select class="end-meridiem meridiem" ' . $strDisabled . '>';

			    		foreach($meridiems as $meridiem){
			    			$selectedClass = '';
			    			if(!empty($value[$day]) && $meridiem == $value[$day]['end']['meridiem']){
			    				$selectedClass = 'selected="selected"';
			    			}

			    			$html .= '<option value="' . $meridiem . '" ' . $selectedClass . '>' . $meridiem . '</option>';
			    		}

			     $html .= '</select>'
			    	. '</td>'
		    	. '</tr>';

		    $i++; // increment counter for odd/even
		 }

		$html .= '</tbody>'
		. '</table>';

		$html .= '</div>';

		return $html;
	}

	function save_value( $value )
	{
		return json_decode(stripslashes($value), true);
	}
}