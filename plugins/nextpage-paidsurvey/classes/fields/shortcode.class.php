<?php

if( ! defined( 'ABSPATH' ) ) exit;

class Cuztom_Field_Shortcode extends Cuztom_Field
{
	var $_supports_repeatable 	= false;
	var $_supports_bundle		= false;
	var $_supports_ajax			= false;

	var $css_classes			= array( 'cuztom-input' );

	function _output( $value )
	{
		global $post;

		if(!empty($post->post_name)){
			$value = $post->post_name;
		}


		$html = '<div class="nav-shortcode-holder">';

		if(!empty($this->options['use_value']) && $this->options['use_value'] == true && !empty($value)){
			// Use the $value as the text to display
			$html .= '<input type="hidden" ' . $this->output_name() . ' ' . $this->output_id() . ' ' . $this->output_css_class() . ' data-shorcode-name="' . $this->options['shortcode_name'] . '" value="' . $value . '" />';

			$html .= '<span class="output">[' . $this->options['shortcode_name'] . ' slug="' . $value . '"]</span>';
		}elseif(!empty($this->options['text'])){
			// Use text option from field array to display
			$html .= '<input type="hidden" ' . $this->output_name() . ' ' . $this->output_id() . ' ' . $this->output_css_class() . ' data-shorcode-name="' . $this->options['shortcode_name'] . '" value="" />';

			$html .= '<span class="output">' . $this->options['text'] . '</span>';
		}else{
			// Don't display anything
			$html .= '<input type="hidden" ' . $this->output_name() . ' ' . $this->output_id() . ' ' . $this->output_css_class() . ' data-shorcode-name="' . $this->options['shortcode_name'] . '" value="" />'
				   . '<span class="output"></span>';
		}

		$html .= '</div>';

		return $html;
	}
}