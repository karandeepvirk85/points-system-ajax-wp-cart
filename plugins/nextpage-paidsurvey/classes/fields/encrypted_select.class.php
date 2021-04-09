<?php

if( ! defined( 'ABSPATH' ) ) exit;

class Cuztom_Field_Encrypted_Select extends Cuztom_Field
{
	var $_supports_repeatable 	= true;
	var $_supports_ajax			= true;
	var $_supports_bundle		= true;

	var $css_classes 			= array( 'cuztom-input cuztom-select' );
	var $data_attributes 		= array( 'default-value' => null );

	function __construct( $field, $parent )
	{
		parent::__construct( $field, $parent );

		$this->data_attributes['default-value'] = $this->default_value;
	}

	function _output( $value )
	{
		// Get the postmeta
		if(!empty($value)) {
			$encKey 		= pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
			$ciphertext_dec = base64_decode($value);
			$iv_size 		= mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
			$iv_dec 		= substr($ciphertext_dec, 0, $iv_size);
			$ciphertext_dec = substr($ciphertext_dec, $iv_size);
			$plaintext_dec  = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $encKey, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);

			$value = rtrim($plaintext_dec);
		}

		$output = '<select ' . $this->output_name() . ' ' . $this->output_id() . ' ' . $this->output_css_class() . ' ' . $this->output_data_attributes() . '>';
			if( isset( $this->args['show_option_none'] ) )
				$output .= '<option value="0" ' . ( empty( $value ) ? 'selected="selected"' : '' ) . '>' . $this->args['show_option_none'] . '</option>';

			if( is_array( $this->options ) )
			{
				foreach( $this->options as $slug => $name )
				{
					$output .= '<option value="' . $slug . '" ' . ( ! empty( $value ) ? selected( $slug, $value, false ) : selected( $this->default_value, $slug, false ) ) . '>' . Cuztom::beautify( $name ) . '</option>';
				}
			}
		$output .= '</select>';

		$output .= $this->output_explanation();

		return $output;
	}

	function save_value( $value ) {
		if(!empty($value)) {
			$encKey 			= pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
			$key_size 			= strlen($encKey);
			$iv_size 			= mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
			$iv 				= mcrypt_create_iv($iv_size, MCRYPT_RAND);
			$ciphertext 		= mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $encKey, $value, MCRYPT_MODE_CBC, $iv);
			$ciphertext 		= $iv . $ciphertext;
			$ciphertext_base64 	= base64_encode($ciphertext);

			$value 				= $ciphertext_base64;
		}
		return $value;
	}
}