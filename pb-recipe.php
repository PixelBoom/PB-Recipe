<?php
/*
Plugin Name: PB Recipe
Plugin URI: https://github.com/PixelBoom/PB-Recipe/
Description: Create recipe in your WordPress posts.
Author: PixelBoom
Version: 1.0
Author URI: http://www.pixelboom.net/
*/

// If this file is called directly, bail.
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

// The plugin path.
define('PB_RECIPE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

// The plugin URL.
define('PB_RECIPE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// The PB_Recipe class.
if( ! class_exists( 'PB_Recipe' ) ) {
	class PB_Recipe {
		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {
			// Include files.
			$this->includes();

			// Instantiate the core object.
			new PB_Recipe_Metabox();
			new PB_Recipe_Shortcodes();

			// Actions & Hooks.
			add_action( 'wp_enqueue_scripts',    array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts') );
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'action_links' ) );
		}


		/**
		 * Include other files.
		 *
		 * @since 1.0
		 * @access private
		 */
		private function includes() {
			require_once( PB_RECIPE_PLUGIN_PATH . 'includes/customize.php' );
			require_once( PB_RECIPE_PLUGIN_PATH . 'includes/metabox.php' );
			require_once( PB_RECIPE_PLUGIN_PATH . 'includes/shortcodes.php' );
		}


		
		/**
		 * Enqueue scripts & styles
		 *
		 * @since 1.0
		 * @access public
		 */
		public function enqueue_scripts() {
			wp_enqueue_style( 'pb-recipe-style', PB_RECIPE_PLUGIN_URL . 'assets/css/style.css', array(), '1.0' );

			$print = pb_recipe_get_option('print');
			if( '1' == $print ) {
				wp_enqueue_script( 'pb-recipe-script', PB_RECIPE_PLUGIN_URL . 'assets/js/script.js', array(), '1.0', true );
				$l10n = array(
					'print_css_url' => PB_RECIPE_PLUGIN_URL . 'assets/css/print.css'
				);
				wp_localize_script( 'pb-recipe-script', 'PB_Recipe', $l10n );
			}
		}


		/**
		 * Enqueue scripts & styles
		 *
		 * @since 1.0
		 * @access public
		 */
		public function admin_enqueue_scripts() {
			wp_enqueue_style( 'pb-recipe-admin-style', PB_RECIPE_PLUGIN_URL . 'assets/css/admin.css' , array(), '1.0' );

			wp_enqueue_script( 'pb-recipe-admin-script', PB_RECIPE_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery', 'jquery-ui-sortable' ), '1.0', true );
			$l10n = apply_filters( 'pb_recipe_admin_localize_script', array() );
			wp_localize_script( 'pb-recipe-admin-script', 'PB_Recipe', $l10n );
		}


		/**
		 * Applied to the list of links to display on the plugins page.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function action_links( $links ) {
			$custom_links = array(
				'pb_recipe_action_links' => sprintf( '<a href="%1$s">%2$s</a>', admin_url('customize.php?autofocus[panel]=pb_recipe_settings'), esc_html__( 'Settings', 'pb-recipe' ) )
			);
			return array_merge( $custom_links, $links );
		}
	}
}
new PB_Recipe();
