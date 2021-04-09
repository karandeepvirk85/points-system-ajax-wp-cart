<?php

if( ! defined( 'ABSPATH' ) ) exit;

class Cuztom_Field_Encrypted_Textarea extends Cuztom_Field
{
	var $_supports_repeatable 	= true;
	var $_supports_bundle		= true;
	var $_supports_ajax			= true;

	var $css_classes 			= array( 'cuztom-input' );

	function _output( $value ) {
		global $post;

		// Get the postmeta
		if(!empty($value)) {
			$metaValue 		= get_post_meta($post->ID, $this->id, true);
			$encKey 		= pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
			$ciphertext_dec = base64_decode($metaValue);
			$iv_size 		= mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
			$iv_dec 		= substr($ciphertext_dec, 0, $iv_size);
			$ciphertext_dec = substr($ciphertext_dec, $iv_size);
			$plaintext_dec  = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $encKey, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);

			$value = rtrim($plaintext_dec);
		}

		$html = '<textarea ' . $this->output_name() . ' id="' . $this->id . '">'.$value.'</textarea>';
		return $html;
	}

	function save_value( $value ) {
		$encKey 			= pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
		$key_size 			= strlen($encKey);
		$iv_size 			= mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		$iv 				= mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$ciphertext 		= mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $encKey, $value, MCRYPT_MODE_CBC, $iv);
		$ciphertext 		= $iv . $ciphertext;
		$ciphertext_base64 	= base64_encode($ciphertext);

		$value 				= $ciphertext_base64;
		return $value;
	}
}