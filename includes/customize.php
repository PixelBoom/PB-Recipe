<?php
/**
 * @package PB_Recipe
 * @since 1.0
 */
if( ! class_exists( 'PB_Recipe_Customize' ) ) {
	class PB_Recipe_Customize {

		/**
		 * Set defaults.
		 *
		 * @since 1.0
		 */
		public static function defaults( $key = 'all' ) {
			$args = array(
				  'print'                => '1'
				, 'print_label'          => 'Print'
				, 'prep_label'           => 'Prep Time:'
				, 'cook_label'           => 'Cook Time:'
				, 'yield_label'          => 'Yield:'
				, 'ingredients_label'    => 'Ingredients'
				, 'instructions_label'   => 'Instructions'
				, 'notes_label'          => 'Notes'
				, 'background_color'     => '#ffffff'
				, 'border_color'         => '#cccccc'
				, 'title_color'          => '#000000'
				, 'meta_color'           => '#333333'
				, 'box_title_color'      => '#000000'
				, 'ingredients_color'    => '#333333'
				, 'instructions_color'   => '#333333'
				, 'notes_color'          => '#333333'
				, 'print_bg_color'       => '#000000'
				, 'print_color'          => '#ffffff'
				, 'print_bg_hover_color' => '#ff0000'
				, 'print_hover_color'    => '#ffffff'
			);

			$custom = apply_filters( 'pb_recipe_option_defaults', array() );
			$args = wp_parse_args( (array) $custom, $args );

			if( 'all' != $key ) {
				return isset( $args[$key] ) ? $args[$key] : false;
			}

			return $args;
		}


		/**
		 * Register WP Customize.
		 *
		 * @since 1.0
		 */
		public static function register( $wp_customize ) {
			// Add Panel.
			$wp_customize->add_panel( 'pb_recipe_settings', array( 'title' => esc_html__( 'PB Recipe Settings', 'pb-recipe' ), 'capability' => 'edit_theme_options' ) );

			// Display Settings.
			$wp_customize->add_section( 'pb_recipe_settings_display', array( 'title' => esc_html__( 'Display', 'pb-recipe' ), 'panel' => 'pb_recipe_settings' ) );

				// Print Button.
				$wp_customize->add_setting('pb_recipe_options[print]', array( 'type' => 'option', 'default' => self::defaults('print'), 'sanitize_callback' => 'pb_recipe_sanitize_checkbox' ) );
				$wp_customize->add_control('pb_recipe_options[print]', array( 'label' => esc_html__( 'Display Print Button?', 'pb-recipe' ), 'type' => 'checkbox', 'section' => 'pb_recipe_settings_display' ) );

				// Labels.
				$wp_customize->add_setting('pb_recipe_options[print_label]', array( 'type' => 'option', 'default' => self::defaults('print_label'), 'sanitize_callback' => 'sanitize_text_field' ) );
				$wp_customize->add_control('pb_recipe_options[print_label]', array( 'label' => esc_html__( 'Print Button Text:', 'pb-recipe' ), 'type' => 'text', 'section' => 'pb_recipe_settings_display' ) );

				$wp_customize->add_setting('pb_recipe_options[prep_label]', array( 'type' => 'option', 'default' => self::defaults('prep_label'), 'sanitize_callback' => 'sanitize_text_field' ) );
				$wp_customize->add_control('pb_recipe_options[prep_label]', array( 'label' => esc_html__( '"Prep Time" Label:', 'pb-recipe' ), 'type' => 'text', 'section' => 'pb_recipe_settings_display' ) );

				$wp_customize->add_setting('pb_recipe_options[cook_label]', array( 'type' => 'option', 'default' => self::defaults('cook_label'), 'sanitize_callback' => 'sanitize_text_field' ) );
				$wp_customize->add_control('pb_recipe_options[cook_label]', array( 'label' => esc_html__( '"Cook Time" Label:', 'pb-recipe' ), 'type' => 'text', 'section' => 'pb_recipe_settings_display' ) );

				$wp_customize->add_setting('pb_recipe_options[yield_label]', array( 'type' => 'option', 'default' => self::defaults('yield_label'), 'sanitize_callback' => 'sanitize_text_field' ) );
				$wp_customize->add_control('pb_recipe_options[yield_label]', array( 'label' => esc_html__( '"Yield" Label:', 'pb-recipe' ), 'type' => 'text', 'section' => 'pb_recipe_settings_display' ) );

				$wp_customize->add_setting('pb_recipe_options[ingredients_label]', array( 'type' => 'option', 'default' => self::defaults('ingredients_label'), 'sanitize_callback' => 'sanitize_text_field' ) );
				$wp_customize->add_control('pb_recipe_options[ingredients_label]', array( 'label' => esc_html__( '"Ingredients" Label:', 'pb-recipe' ), 'type' => 'text', 'section' => 'pb_recipe_settings_display' ) );

				$wp_customize->add_setting('pb_recipe_options[instructions_label]', array( 'type' => 'option', 'default' => self::defaults('instructions_label'), 'sanitize_callback' => 'sanitize_text_field' ) );
				$wp_customize->add_control('pb_recipe_options[instructions_label]', array( 'label' => esc_html__( '"Instructions" Label:', 'pb-recipe' ), 'type' => 'text', 'section' => 'pb_recipe_settings_display' ) );

				$wp_customize->add_setting('pb_recipe_options[notes_label]', array( 'type' => 'option', 'default' => self::defaults('notes_label'), 'sanitize_callback' => 'sanitize_text_field' ) );
				$wp_customize->add_control('pb_recipe_options[notes_label]', array( 'label' => esc_html__( '"Notes" Label:', 'pb-recipe' ), 'type' => 'text', 'section' => 'pb_recipe_settings_display' ) );


			// Color Settings.
			$wp_customize->add_section( 'pb_recipe_settings_colors', array( 'title' => esc_html__( 'Colors', 'pb-recipe' ), 'panel' => 'pb_recipe_settings' ) );
				
				// Background Color.
				$wp_customize->add_setting( 'pb_recipe_options[background_color]', array( 'default' => self::defaults('background_color'), 'type' => 'option', 'sanitize_callback' => 'sanitize_hex_color' ) );
				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pb_recipe_options[background_color]', array( 'label' => esc_html__( 'Recipe Background Color', 'pb-recipe' ), 'section' => 'pb_recipe_settings_colors' ) ) );

				// Border Color.
				$wp_customize->add_setting( 'pb_recipe_options[border_color]', array( 'default' => self::defaults('border_color'), 'type' => 'option', 'sanitize_callback' => 'sanitize_hex_color' ) );
				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pb_recipe_options[border_color]', array( 'label' => esc_html__( 'Recipe Border Color', 'pb-recipe' ), 'section' => 'pb_recipe_settings_colors' ) ) );

				// Recipe Title Color.
				$wp_customize->add_setting( 'pb_recipe_options[title_color]', array( 'default' => self::defaults('title_color'), 'type' => 'option', 'sanitize_callback' => 'sanitize_hex_color' ) );
				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pb_recipe_options[title_color]', array( 'label' => esc_html__( 'Recipe Title Color', 'pb-recipe' ), 'section' => 'pb_recipe_settings_colors' ) ) );

				// Recipe Meta Color.
				$wp_customize->add_setting( 'pb_recipe_options[meta_color]', array( 'default' => self::defaults('meta_color'), 'type' => 'option', 'sanitize_callback' => 'sanitize_hex_color' ) );
				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pb_recipe_options[meta_color]', array( 'label' => esc_html__( '"Prep Time/Cook Time/Yield" Color', 'pb-recipe' ), 'section' => 'pb_recipe_settings_colors' ) ) );

				// Recipe Box Title Color.
				$wp_customize->add_setting( 'pb_recipe_options[box_title_color]', array( 'default' => self::defaults('box_title_color'), 'type' => 'option', 'sanitize_callback' => 'sanitize_hex_color' ) );
				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pb_recipe_options[box_title_color]', array( 'label' => esc_html__( '"Ingredients/Instructions/Notes" Title Color', 'pb-recipe' ), 'section' => 'pb_recipe_settings_colors' ) ) );

				// Ingredients Text Color.
				$wp_customize->add_setting( 'pb_recipe_options[ingredients_color]', array( 'default' => self::defaults('ingredients_color'), 'type' => 'option', 'sanitize_callback' => 'sanitize_hex_color' ) );
				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pb_recipe_options[ingredients_color]', array( 'label' => esc_html__( '"Ingredients" Text Color', 'pb-recipe' ), 'section' => 'pb_recipe_settings_colors' ) ) );

				// Instructions Text Color.
				$wp_customize->add_setting( 'pb_recipe_options[instructions_color]', array( 'default' => self::defaults('instructions_color'), 'type' => 'option', 'sanitize_callback' => 'sanitize_hex_color' ) );
				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pb_recipe_options[instructions_color]', array( 'label' => esc_html__( '"Instructions" Text Color', 'pb-recipe' ), 'section' => 'pb_recipe_settings_colors' ) ) );

				// Notes Text Color.
				$wp_customize->add_setting( 'pb_recipe_options[notes_color]', array( 'default' => self::defaults('notes_color'), 'type' => 'option', 'sanitize_callback' => 'sanitize_hex_color' ) );
				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pb_recipe_options[notes_color]', array( 'label' => esc_html__( '"Notes" Text Color', 'pb-recipe' ), 'section' => 'pb_recipe_settings_colors' ) ) );

				// Print Button Color.
				$wp_customize->add_setting( 'pb_recipe_options[print_bg_color]', array( 'default' => self::defaults('print_bg_color'), 'type' => 'option', 'sanitize_callback' => 'sanitize_hex_color' ) );
				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pb_recipe_options[print_bg_color]', array( 'label' => esc_html__( 'Print Button BG Color', 'pb-recipe' ), 'section' => 'pb_recipe_settings_colors' ) ) );

				$wp_customize->add_setting( 'pb_recipe_options[print_color]', array( 'default' => self::defaults('print_color'), 'type' => 'option', 'sanitize_callback' => 'sanitize_hex_color' ) );
				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pb_recipe_options[print_color]', array( 'label' => esc_html__( 'Print Button Text Color', 'pb-recipe' ), 'section' => 'pb_recipe_settings_colors' ) ) );

				$wp_customize->add_setting( 'pb_recipe_options[print_bg_hover_color]', array( 'default' => self::defaults('print_bg_hover_color'), 'type' => 'option', 'sanitize_callback' => 'sanitize_hex_color' ) );
				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pb_recipe_options[print_bg_hover_color]', array( 'label' => esc_html__( 'Print Button BG Hover Color', 'pb-recipe' ), 'section' => 'pb_recipe_settings_colors' ) ) );

				$wp_customize->add_setting( 'pb_recipe_options[print_hover_color]', array( 'default' => self::defaults('print_hover_color'), 'type' => 'option', 'sanitize_callback' => 'sanitize_hex_color' ) );
				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pb_recipe_options[print_hover_color]', array( 'label' => esc_html__( 'Print Button Text Hover Color', 'pb-recipe' ), 'section' => 'pb_recipe_settings_colors' ) ) );

		}


		/**
		 * CSS output.
		 *
		 * @since 1.0
		 */
		public static function output_style() {
			$data   = '';

			// Background Color.
			$data .= self::css('background_color', '.pb-recipe', 'background-color');

			// Border Color
			$data .= self::css('border_color', '.pb-recipe, ul.pb-recipe-meta, .pb-recipe-notes', 'border-color');

			// Recipe Title Color.
			$data .= self::css('title_color', '.pb-recipe-title', 'color');

			// Meta Color.
			$data .= self::css('meta_color', 'ul.pb-recipe-meta', 'color');

			// Box Title Color.
			$data .= self::css('box_title_color', '.pb-recipe-box-title', 'color');

			// Ingredients Color.
			$data .= self::css('ingredients_color', '.pb-recipe-ingredients .pb-recipe-box-content', 'color');

			// Instructions Color.
			$data .= self::css('instructions_color', '.pb-recipe-instructions .pb-recipe-box-content', 'color');

			// Notes Color.
			$data .= self::css('notes_color', '.pb-recipe-notes .pb-recipe-box-content', 'color');

			// Print Button.
			$data .= self::css('print_bg_color', '.pb-recipe-print-button button', 'background-color');
			$data .= self::css('print_color', '.pb-recipe-print-button button', 'color');
			$data .= self::css('print_bg_hover_color', '.pb-recipe-print-button button:hover', 'background-color');
			$data .= self::css('print_hover_color', '.pb-recipe-print-button button:hover', 'color');

			if( ! empty( $data ) ) {
				echo '<style type="text/css">'. $data .'</style>'. "\n";
			}
		}


		/**
		 * Retrieve value.
		 *
		 * @since 1.0
		 */
		public static function get_option( $key = '', $default = false ) {
			$saved   = (array) get_option( 'pb_recipe_options' );
			$args    = self::defaults();
			$options = wp_parse_args( $saved, $args );

			return isset( $options[$key] ) ? $options[$key] : $default;
		}


		/**
		 * Gerenate CSS.
		 *
		 * @since 1.0
		 */
		public static function css( $key = '', $selector = '', $style = '', $before = '', $after = '' ) {
			$default = self::defaults($key);
			$value   = self::get_option($key, $default);
			$return  = '';

			if( $value && $value !== $default ) {
				$return = sprintf('%s{%s:%s;}'
					, $selector
					, $style
					, $before . $value . $after
				);
			}

			return $return;
		}
	}

	// Set-up.
	add_action( 'customize_register', array( 'PB_Recipe_Customize', 'register' ) );
	add_action( 'wp_head', array( 'PB_Recipe_Customize', 'output_style' ) );


	// Sanitization.
	function pb_recipe_sanitize_checkbox( $input ) {
		return (1 == $input) ? 1 : '';
	}

}

// Retrieve option value.
function pb_recipe_get_option( $key = '', $default = false, $echo = false ) {
	$output = PB_Recipe_Customize::get_option($key, $default);
	if( $echo ) {
		echo $output;
	}
	else {
		return $output;
	}
}