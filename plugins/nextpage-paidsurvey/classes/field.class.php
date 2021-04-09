<?php

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Cuztom Field Class
 * 
 */
class Cuztom_Field
{
	var $id						= '';
	var $type					= '';
	var $name 					= '';
    var $label 					= '';
    var $description 			= '';
    var $explanation			= '';
	var $default_value 			= '';
	var $options 				= array(); // Only used for radio, checkboxes etc.
	var $args					= array(); // Specific args for the field
	var $underscore 			= true;
	var $required 				= false;
	var $repeatable 			= false;
	var $ajax 					= false;

	var $parent					= '';
	var $meta_type				= '';
	var $in_bundle				= false;

	var $show_admin_column 		= false;
	var $admin_column_sortable	= false;
	var $admin_column_filter	= false;

	var $data_attributes 		= array();
	var $css_classes			= array();

	var $pre					= ''; // Before name
	var $after					= ''; // After name
	var $pre_id					= ''; // Before id
	var $after_id				= ''; // After id

	var $_supports_repeatable 	= false;
	var $_supports_bundle		= false;
	var $_supports_ajax			= false;

	/**
	 * Constructs a Cuztom_Field
	 *
	 */
	function __construct( $field, $parent )
	{
		$this->type				= isset( $field['type'] ) 				? $field['type'] 				: $this->type;
		$this->name 			= isset( $field['name'] ) 				? $field['name'] 				: $this->name;
		$this->label			= isset( $field['label'] ) 				? $field['label'] 				: $this->label;
		$this->description		= isset( $field['description'] ) 		? $field['description'] 		: $this->description;
		$this->explanation		= isset( $field['explanation'] ) 		? $field['explanation'] 		: $this->explanation;
		$this->default_value	= isset( $field['default_value'] ) 		? $field['default_value'] 		: $this->default_value;
		$this->options			= isset( $field['options'] ) 			? $field['options'] 			: $this->options;
		$this->args				= isset( $field['args'] ) 				? $field['args'] 				: $this->args;
		$this->underscore		= isset( $field['underscore'] ) 		? $field['underscore'] 			: $this->underscore;
		$this->required			= isset( $field['required'] ) 			? $field['required'] 			: $this->required;
		$this->repeatable		= isset( $field['repeatable'] ) 		? $field['repeatable'] 			: $this->repeatable ;
		$this->ajax				= isset( $field['ajax'] ) 				? $field['ajax'] 				: $this->ajax ;

		$this->show_admin_column		= isset( $field['show_admin_column'] ) 		? $field['show_admin_column'] 		: $this->show_admin_column;
		$this->admin_column_sortable	= isset( $field['admin_column_sortable'] ) 	? $field['admin_column_sortable'] 	: $this->admin_column_sortable;
		$this->admin_column_filter		= isset( $field['admin_column_filter'] ) 	? $field['admin_column_filter'] 	: $this->admin_column_filter;

		// Mostly the name of the meta box
		$this->parent			= $parent;

		// Id is used as id to select the field, if i'ts not in the $field paramater, the id will be genereted
		$this->id  				= isset( $field['id'] ) 				? $field['id']					: $this->build_id( $this->name, $parent );
	}

	/**
	 * Outputs a field based on its type
	 *
	 */
	function output( $value )
	{
		if( $this->repeatable && $this->_supports_repeatable )
			return $this->_repeatable_output( $value );
		elseif( $this->ajax && $this->_supports_ajax )
			return $this->_ajax_output( $value );
		else
			return $this->_output( $value );
	}

	/**
	 * Output method
	 * Defaults to a normal text field
	 *
	 */
	function _output( $value )
	{
		return '<input type="text" ' . $this->output_name() . ' ' . $this->output_id() . ' ' . $this->output_css_class() . ' value="' . ( strlen( $value ) > 0 ? $value : $this->default_value ) . '" ' . $this->output_data_attributes() . ' />' . $this->output_explanation();
	}

	/**
	 * Outputs the field, ready for repeatable functionality
	 *
	 */

	function _repeatable_output( $value )
	{
		$this->after = '[]';
		$output = '';

		if( is_array( $value ) )
		{
			foreach( $value as $item )
				$output .= '<li class="cuztom-field cuztom-sortable-item js-cuztom-sortable-item"><div class="cuztom-handle-sortable js-cuztom-handle-sortable"></div>' . $this->_output( $item ) . ( count( $value ) > 1 ? '<div class="js-cuztom-remove-sortable cuztom-remove-sortable"></div>' : '' ) . '</li>';
		}
		else
		{
			$output .= '<li class="cuztom-field cuztom-sortable-item js-cuztom-sortable-item"><div class="cuztom-handle-sortable js-cuztom-handle-sortable"></div>' . $this->_output( $value ) . ( $this->repeatable ? '</li>' : '' );
		}

		return $output;
	}

	/**
	 * Outputs the field, ready for ajax save
	 *
	 * @param  	string|array 	$value
	 * @return  mixed 			$output
	 */
	function _ajax_output( $value )
	{
		$output = $this->_output( $value );
		$output .= '<a class="cuztom-ajax-save js-cuztom-ajax-save button-secondary" href="#">' . __( 'Save', 'cuztom' ) . '</a>';

		return $output;
	}

	/**
	 * Save meta
	 * @param  	int 			$object_id
	 * @param  	string 			$value
	 *
	 */
	function save( $object_id, $value )
	{
		$value = $this->save_value( $value );

		if( $this->meta_type == 'user' )
			update_user_meta( $object_id, $this->id, $value );
		elseif( $this->meta_type == 'post' )
			update_post_meta( $object_id, $this->id, $value );
		elseif( $this->meta_type == 'term' )
			return $value;

		return false;
	}

	/**
	 * Output save value
	 *
	 */
	function save_value( $value )
	{
		return $value;
	}

	/**
	 *
	 */
	function ajax_save()
	{
		if( $_POST['cuztom'] )
		{
			$object_id	= $_POST['cuztom']['object_id'];
			$id_field	= $_POST['cuztom']['field_id'];
			$value 		= $_POST['cuztom']['value'];
			$meta_type 	= $_POST['cuztom']['meta_type'];

			if( empty( $object_id ) )
				die();

			if( $meta_type == 'user' )
				update_user_meta( $object_id, $field_id, $value );
			elseif( $meta_type == 'post' )
				update_post_meta( $object_id, $field_id, $value );
			elseif( $meta_type == 'option')
				update_option('cuztom');
		}

		// For Wordpress
		die();
	}

	/**
	 * Outputs the fields name attribute
	 * 
	 * 
	 */
	function output_name( $overwrite = null )
	{
		// if the option page $_GET var is set use that as the html array name
		if (isset($_GET['page']) && !empty($_GET['page'])) {
			$overwrite = $_GET['page'].$this->pre.'['.$this->id.']'.$this->after;
		}
		return $overwrite ? 'name="' . $overwrite . '"' : 'name="cuztom' . $this->pre . '[' . $this->id . ']' . $this->after . '"';
	}

	/**
	 * Outputs the fields id attribute
	 * 
	 * 
	 */
	function output_id( $overwrite = null )
	{
		return $overwrite ? 'id="' . $overwrite . '"' : 'id="' . $this->pre_id . $this->id . $this->after_id . '"';
	}

	/**
	 * Outputs the fields css classes
	 *
	 *
	 */
	function output_css_class( $extra = array() )
	{
		$classes = array_merge( $this->css_classes, $extra );

		return 'class="' . implode( ' ', $classes ) . '"';
	}

	/**
	 * Outputs the fields data attributes
	 *
	 */
	function output_data_attributes( $extra = array() )
	{
		$output = '';

		foreach( array_merge( $this->data_attributes, $extra ) as $attribute => $value )
		{
			if( ! is_null( $value ) )
				$output .= 'data-' . $attribute . '="' . $value . '"';
			elseif( ! $value && isset( $this->args[Cuztom::uglify( $attribute )] ) )
				$output .= 'data-' . $attribute . '="' . $this->args[Cuztom::uglify( $attribute )] . '"';
		}

		return $output;
	}

	/**
	 * Outputs the for attribute
	 *
	 *
	 */
	function output_for_attribute( $for = null )
	{
		return $for ? 'for="' . $for . '"' : '';
	}

	/**
	 * Outputs the fields explanation
	 *
	 *
	 */
	function output_explanation()
	{
		return ( ! $this->repeatable && $this->explanation ? '<em class="cuztom-explanation">' . $this->explanation . '</em>' : '' );
	}

	/**
	 * Builds an string used as field id and name
	 *
	 * @param 	string 			$name
	 * @param  	string 			$parent
	 * @return 	string
	 *
	 */
	function build_id( $name, $parent )
	{
		return apply_filters( 'cuztom_build_id',  ( $this->underscore ? '_' : '' ) . ( ! empty( $parent ) ? Cuztom::uglify( $parent ) . '_' : '' ) . Cuztom::uglify( $name ) );
	}
}