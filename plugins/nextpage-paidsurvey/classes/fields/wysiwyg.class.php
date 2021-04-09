<?php

if( ! defined( 'ABSPATH' ) ) exit;

class Cuztom_Field_Wysiwyg extends Cuztom_Field
{
	var $_supports_ajax			= true;
	var $_supports_bundle		= true;

	function __construct( $field, $parent )
	{
		parent::__construct( $field, $parent );

		$this->args = array_merge(
			array(
				'textarea_name' => 'cuztom[' . $this->id . ']',
				'editor_class'	=> ''
			),
			$this->args
		);

		$this->args['editor_class'] .= ' cuztom-input';

	}

	function _output( $value )
	{
		// Strip slashes when saving a term meta
		if(!empty($value) && $this->meta_type == 'term'){
			$value = stripslashes($value);
		}

		// if on an option page set the html array name
		$array_name = (isset($_GET['page']) && !empty($_GET['page'])) ? $_GET['page'] : 'cuztom';
		$this->args['textarea_name'] = $array_name . $this->pre . '[' . $this->id . ']' . $this->after;
		return wp_editor( ( ! empty( $value ) ? $value : $this->default_value ), $this->pre_id . $this->id . $this->after_id, $this->args ) . $this->output_explanation();
	}

	function save_value( $value ) {
		return wpautop( $value );
	}
}