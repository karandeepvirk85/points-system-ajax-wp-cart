<?php

if( ! defined( 'ABSPATH' ) ) exit;

class Cuztom_Field_Readonly extends Cuztom_Field
{
	var $_supports_repeatable 	= true;
	var $_supports_bundle		= true;
	var $_supports_ajax			= true;

	var $css_classes			= array( 'cuztom-input' );

	function _output( $value )
	{
		if(!isset($this->options)){
			// no value to show
			return '';
		}

		global $post;

		$value = '';

		if (!empty($this->options['meta_field'])) {
			$metaValue = get_post_meta($post->ID, $this->options['meta_field'], true);
			$value = $metaValue . ' <input type="hidden" name="cuztom[' . $this->options['meta_field'] . ']" id="' . $this->id . '" value="' . $metaValue . '" />';
		}

		if(isset($this->options) || isset($this->options['type']) && !empty($this->options['type'])){
			// Deal with special types
			switch(strtolower($this->options['type'])){
				case 'email':
					if (!empty($this->options['meta_field'])) {
						$value = '<a href="mailto:' . $metaValue . '">' . $metaValue . '</a>' . ' <input type="hidden" name="cuztom[' . $this->options['meta_field'] . ']" value="' . $metaValue . '" />';
					}
					break;
				case 'raw_output':
					if (!empty($this->options['output'])) {
						$value = $this->options['output'];
					}
					break;
			}
		}

		return $value;
	}

	function do_htmlspecialchars( &$value )
	{
		$value = htmlspecialchars( $value );
	}
}