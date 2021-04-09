<?php

if( ! defined( 'ABSPATH' ) ) exit;

class Cuztom_Field_Text_Check extends Cuztom_Field
{
	var $_supports_bundle		= false;
	var $css_classes 			= array( 'cuztom-input' );

	function _output( $value )
	{
		$checked = (!empty($value['check'])) ? 'checked' : '';

		// Set up the argument values
		$check_label = !empty($this->args['check_label']) ? $this->args['check_label'] : '';
		$check_value = !empty($this->args['check_value']) ? $this->args['check_value'] : 1;

		$html  = '';
		$html .= '<table border="0" class="text-check">';
		$html .= '<tr>';

		// Textbox output
		$html .= '<td width="70%">';
		$html .= '<input type="text" name="cuztom[' . $this->id . '][text]"' . $this->output_id() . ' ' . $this->output_css_class() . ' value="' . ( !empty( $value['text'] ) ? esc_attr($value['text']) : esc_attr($this->default_value) ) . '" ' . $this->output_data_attributes() . ' />';
		$html .= '</td>';

		// Checkbox output
		$html .= '<td width="30%">';
		$html .= '<label><input type="checkbox" name="cuztom[' . $this->id . '][check]" id=" ' . $this->id . '_check" value="' . esc_attr($check_value) . '" ' . $checked . '/>' . Cuztom::beautify($check_label) . '</label>';
		$html .= '</td>';

		$html .= '<tr>';

		// Add explanation text
		if (!empty($this->explanation)) {
			$html .= '<tr><td colspan="2">' . $this->output_explanation() . '</td></tr>';
		}

		$html .= '</table>';

		return $html;
	}

}