<?php

if( ! defined( 'ABSPATH' ) ) exit;

class Cuztom_Field_Gmap extends Cuztom_Field
{
	var $_supports_bundle		= false;
	var $css_classes			= array( 'cuztom-input gmap' );

	function _output( $value )
	{
		$classes = str_replace('"', '', str_replace('class="', '', $this->output_css_class()));

		$default_value = array(
			'lat' => 49.8996925,
			'lng' => -119.4546625,
			'zoom' => 10,
		);

		if (!empty($value)) {
			$default_value = $value;
		} else if (!empty($this->default_value)) {
			$default_value = array_merge($default_value, $this->default_value);
		}
		
		$dataClear = false;
		if(isset($this->options['can_clear'])){
			$dataClear = (boolean)$this->options['can_clear'];
		}

		$dataShowMarker = true;
		if(empty($value) && isset($this->options['default_show_marker'])){
			$dataShowMarker = (boolean)$this->options['default_show_marker'];
		}

		$html = '<div class="cuztom-gmap-wrap ' . $classes . '">';

		if(isset($this->options['sync_field_name'])){
			$syncLabel = 'Get location';
			if(isset($this->options['sync_field_label'])){
				$syncLabel = 'Get location from ' . $this->options['sync_field_label'];
			}

			$html .= '<input type="button" class="syncField button-secondary" value="' . $syncLabel . '" data-sync-metabox="' . $this->options['sync_field_metabox'] . '" data-sync-field="' . $this->options['sync_field_name'] . '" />';	
		}

		if($dataClear){
			$html .= ' <input type="button" class="clearMarker button-secondary" value="Clear Map" />';		
		}

		$html .= '<input type="hidden" ' . $this->output_name() . ' value="' . htmlentities(json_encode($default_value)) . '" />'
			  . '<div class="gmap-holder" ' . $this->output_id() . ' data-lat="' . $default_value['lat'] . '" data-lng="' . $default_value['lng'] . '" data-zoom="' . $default_value['zoom'] . '" data-show-marker="' . $dataShowMarker .'"></div>'
			  . '</div>' . $this->output_explanation();

		return $html;
	}

	function save_value( $value )
	{		
		return json_decode(stripslashes($value), true);
	}
}