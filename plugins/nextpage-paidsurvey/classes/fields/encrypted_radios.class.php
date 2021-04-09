<?php

if( ! defined( 'ABSPATH' ) ) exit;

class Cuztom_Field_Encrypted_Radios extends Cuztom_Field
{
	var $_supports_bundle		= true;

	var $css_classes 			= array( 'cuztom-input' );
	var $data_attributes 		= array( 'default-value' => null );

	function __construct( $field, $parent )
	{
		parent::__construct( $field, $parent );

		$this->data_attributes['default-value']  = $this->default_value;
		$this->after							.= '[]';
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

		$output = '';

		$output .= '<div class="cuztom-checkboxes-wrap" ' . $this->output_data_attributes() . '>';
			if( is_array( $this->options ) )
			{
				foreach( $this->options as $slug => $name )
				{
					if(!empty($value)) {
						$checked = ($slug == $value) ? 'checked="checked"' : '';
					} else {
						$checked = checked( $this->default_value, $slug, false );
					}

					$output .= '<input type="radio" ' . $this->output_name() . ' ' . $this->output_id( $this->id . $this->after_id . '_' . Cuztom::uglify( $slug ) ) . ' ' . $this->output_css_class() . ' value="' . $slug . '" ' . $checked . ' /> ';
					$output .= '<label ' . $this->output_for_attribute( $this->id . $this->after_id . '_' . Cuztom::uglify( $slug ) ) . '">' . Cuztom::beautify( $name ) . '</label>';
					$output .= '<br />';
				}
			}
		$output .= '</div>';

		$output .= $this->output_explanation();

		return $output;
	}

	function save_value( $value ) {
		if(!empty($value)) {
			$value 				= $value[0];
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