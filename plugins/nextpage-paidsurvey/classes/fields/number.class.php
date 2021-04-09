<?php

if( ! defined( 'ABSPATH' ) ) exit;

class Cuztom_Field_Number extends Cuztom_Field
{
	var $_supports_repeatable 	= true;
	var $_supports_bundle		= true;
	var $_supports_ajax			= true;

	var $css_classes			= array( 'cuztom-input' );

	function output($value){
		$period = 'true';
		$comma = 'true';
		$min = '';
		$max = '';
		$step = 'any';

		if(isset($this->options) && isset($this->options['allowPeriod']) && $this->options['allowPeriod'] == false){
			$period = 'false';
		}

		if(isset($this->options) && isset($this->options['allowComma']) && $this->options['allowComma'] == false){
			$comma = 'false';
		}

		if (isset($this->options) && isset($this->options['min'])) {
			$min = 'min="' . $this->options['min'] . '"';
		}

		if (isset($this->options) && isset($this->options['max'])) {
			$max = 'max="' . $this->options['max'] . '"';
		}

		if (isset($this->options) && isset($this->options['step'])) {
			$step = 'min="' . $this->options['step'] . '"';
		}

		return '<input type="number" ' . $this->output_name() . ' ' . $this->output_id() . ' ' . $this->output_css_class() . ' data-restrict-number="true" data-allow-period="' . $period . '" data-allow-comma="' . $comma . '" ' . $min . ' ' . $max . ' step="' . $step . '" value="' . ( strlen( $value ) > 0 ? $value : $this->default_value ) . '" />';
	}

	function save_value( $value )
	{
		if( is_array( $value ) )
			array_walk_recursive( $value, array( &$this, 'do_htmlspecialchars' ) );
		else
			$value = htmlspecialchars( $value );

		return $value;
	}

	function do_htmlspecialchars( &$value )
	{
		$value = htmlspecialchars( $value );
	}
}