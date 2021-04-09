<?php

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Creates custom Options Page
 *
 *
 */
class Cuztom_Options extends Cuztom_Meta
{
	var $id;
	var $title;
	var $slug;
	var $data;
	var $post_type;
	var $option_values;
	var $callback;

	/**
	 * Constructs the class with important vars and method calls
	 *
	 * @param 	string 			$id
	 * @param 	string 			$title
	 * @param 	string 			$slug
	 * @param 	array 			$data
	 *
	 * @since 	0.2
	 *
	 */
	function __construct( $id, $title, $slug, $data = array(), $post_type = null )
	{
		if( ! empty( $slug ) )
		{
			parent::__construct( $title );

			$this->id 				= Cuztom::uglify( $id );
			$this->slug 			= $slug;
			$this->data 			= $data;
			$this->post_type 		= $post_type;
			$this->option_values 	= get_option( $this->id );
			$this->callback 		= array( &$this, 'callback' );

			// Build the meta box and field array
			$this->data = $this->build( $data );

			// Add to array for uninstall
			global $nm_uninstall;
			$nm_uninstall['options'][] = $this->id;

			add_action( 'admin_init', array( &$this, 'register_settings' ) );

			add_action( 'admin_menu', array( &$this, 'add_submenu_page' ) );
		}
	}

	/**
	 * Generates the HTML for the form page and inputs
	 *
	 * @param   integer 		$id
	 * @return  object 			Cuztom_Post_Type
	 *
	 * @since 	0.1
	 *
	 */
	function callback($object, $data = array())
	{
		// Get all inputs from $data
		$data 			= $this->data;
		$meta_type 		= $this->get_meta_type();

		if (!empty($data))
		{

			echo '<div class="wrap">';

			// Creates the header tabs
			echo '<h1>'.$this->title.'</h1>';

			// The Update Message
			if (isset($_GET['settings-updated'])) {
			    echo '<div class="updated below-h2"><p>Updated successfully.</p></div>';
			}

			// Loop through the tabs, and display the options based on the active tab
			echo '<form method="post" action="options.php">';
    		settings_fields( $this->id );
    		do_settings_sections( $this->id );

			if( ! empty( $data ) )
			{

				echo '<input type="hidden" name="cuztom[__activate]" />';
				echo '<div class="cuztom" data-meta-type="' . $meta_type . '">';

					if( ! empty( $this->description ) ) echo '<p class="cuztom-box-description">' . $this->description . '</p>';

					if( ( $data instanceof Cuztom_Tabs ) || ( $data instanceof Cuztom_Accordion ) || ( $data instanceof Cuztom_Bundle ) )
					{
						$data->output( $object );
					}
					else
					{
						echo '<table border="0" cellpadding="0" cellspacing="0" class="form-table cuztom-table">';

							/* Loop through $data */
							foreach( $data as $id_name => $field )
							{
								// get value of each field by key
								$option = get_option( $this->id );
								$value = !empty( $option[$id_name] ) ? $option[$id_name] : '';

								if( ! $field instanceof Cuztom_Field_Hidden )
								{
									echo '<tr>';
										echo '<th class="cuztom-th">';
											echo '<label for="' . $id_name . '" class="cuztom_label">' . $field->label . '</label>';
											echo $field->required ? ' <span class="cuztom-required">*</span>' : '';
											echo '<div class="cuztom-description description">' . $field->description . '</div>';
										echo '</th>';
										echo '<td class="cuztom-td">';

											if( $field->repeatable && $field->_supports_repeatable )
											{
												echo '<a class="button-secondary cuztom-button js-cuztom-add-field js-cuztom-add-sortable" href="#">';
													echo sprintf( '+ %s', __( 'Add', 'cuztom' ) );
												echo '</a>';
												echo '<ul class="js-cuztom-sortable cuztom-sortable cuztom_repeatable_wrap">';
													echo $field->output( $value, $object );
												echo '</ul>';
											}
											else
											{
												echo $field->output( $value, $object );
											}

										echo '</td>';
									echo '</tr>';
								}
								else
								{
									echo $field->output( $value, $object );
								}
							}

						echo '</table>';
					}

				echo '</div>';
			}

			submit_button();
			echo '</form>';

			echo '</div>';

		}
	}

	/**
	 * Registers the setting in the wp_option table, is called 'cuztom'
	 *
	 */
	function register_settings()
	{
		register_setting(
			$this->id, 	// Option group
			$this->id,	// Option name
			array($this, 'sanitize_data') // Sanitize
		);
	}

	/**
	 * Add Options submenu to a parent page
	 *
	 */
	function add_submenu_page()
	{
		add_submenu_page(
			$this->slug,
			$this->title,
			$this->title,
			'edit_posts',
			$this->id,
			$this->callback
		);
	}

	/**
	 * Cleans post meta on removal of dynamic fields
	 *
	 */
	function sanitize_data( $input ) {

		if (!empty($_POST)) {

			// If we are updating the options.php page with dynamic fields
			if ($_POST['option_page'] == $this->id && !empty($this->post_type)) {

				$post_meta_keys		= array();
				$option_keys 		= array();
				$dynamic_fields		= $_POST[$this->id];
				$args 				= array('post_type' => $this->post_type, 'posts_per_page' => -1, 'post_status' => 'any');
				$posts 				= get_posts($args);
				$prefix				= 'nm_dyn_';

				// Add the posted option key names
				foreach ($dynamic_fields as $value) {
					foreach ($value as $post_value) {
						$key_name		= $post_value['_field_name'];
						$option_keys[] 	= Cuztom::uglify($prefix.$key_name);
					}
				}

				if (!empty($posts)) {

					// Loop through all the posts deleting dynamic_fields that arn't used anymore
					foreach ($posts as $post) {

						// Get the post meta for all the posts + add it to an array
						$post_metas = get_post_custom($post->ID);
						foreach ($post_metas as $key => $value) {
							if (strpos($key, 'nm_dyn_') !== false) {
								$post_meta_keys[$key] = false;
							}
						}

						// Check against the posted option values and set to true
						foreach ($post_meta_keys as $post_meta_key => $value) {
							foreach ($option_keys as $option_key) {
								if (strpos($post_meta_key, $option_key) !== false) {
									$post_meta_keys[$post_meta_key] = true;
								}
							}
						}

						// If the post_meta failed the check and isn't being used delete it
						foreach ($post_meta_keys as $post_meta_key => $is_used) {
							if ($is_used == false) {
								delete_post_meta($post->ID, $post_meta_key);
							}
						}

					}

				}


			}

		}

		return $input;

	}


} // End Class