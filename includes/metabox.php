<?php
/**
 * @package PB_Recipe
 * @since 1.0
 */
if( ! class_exists( 'PB_Recipe_Metabox' ) ) {
	class PB_Recipe_Metabox {
		/**
		 * Constructor.
		 * Contains all hooks & actions needed.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'adds' ) );
			add_action( 'save_post',      array( $this, 'save' ) );
		}

		
		/**
		 * Adds the meta box container.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function adds() {
			add_meta_box( 'pb_recipe_card', esc_html__( 'PB Recipe Card', 'pb-recipe' ), array( $this, 'callback' ), array( 'post' ), 'normal', 'high' );
		}

		
		/**
		 * Save the meta when the post is saved.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function save( $post_id ) {
			// Verify that the nonce is valid.
			$nonce = isset( $_POST['pb_recipe_nonce'] ) ? (string) $_POST['pb_recipe_nonce'] : '';
			if( ! wp_verify_nonce( $nonce, 'pb_recipe_nonce' ) ) {
				return $post_id;
			}

			// If this is an autosave, our form has not been submitted,
			// so we don't want to do anything.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}

			// Check the user's permissions.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}

			// Sanitize the user input.
			$_recipe_title = isset( $_POST['pb_recipe_title'] ) ? sanitize_text_field( $_POST['pb_recipe_title'] ) : '';
			$_prep_time    = isset( $_POST['pb_recipe_prep_time'] ) ? sanitize_text_field( $_POST['pb_recipe_prep_time'] ) : '';
			$_cook_time    = isset( $_POST['pb_recipe_cook_time'] ) ? sanitize_text_field( $_POST['pb_recipe_cook_time'] ) : '';
			$_recipe_yield = isset( $_POST['pb_recipe_yield'] ) ? sanitize_text_field( $_POST['pb_recipe_yield'] ) : '';
			$_recipe_notes = isset( $_POST['pb_recipe_notes'] ) ? wp_kses_post( $_POST['pb_recipe_notes'] ) : '';

			$_recipe_ingredients = array();
			if( isset( $_POST['pb_recipe_ingredients'] ) ) {
				$new_ingredients = (array) $_POST['pb_recipe_ingredients'];
				foreach( $new_ingredients as $ingredient ) {
					if( !empty( $ingredient ) ) {
						$_recipe_ingredients[] = wp_strip_all_tags( $ingredient );
					}
				}
			}

			$_recipe_instructions = array();
			if( isset( $_POST['pb_recipe_instructions'] ) ) {
				$new_instructions = (array) $_POST['pb_recipe_instructions'];
				foreach( $new_instructions as $instruction ) {
					if( !empty( $instruction ) ) {
						$_recipe_instructions[] = wp_kses_post( $instruction );
					}
				}
			}

			// Update the meta field.
			if( ! empty( $_recipe_title ) ) {
				update_post_meta( $post_id, '_recipe_title', $_recipe_title );
			}
			else {
				delete_post_meta( $post_id, '_recipe_title' );
			}

			// Prep Time.
			if( ! empty( $_prep_time ) ) {
				update_post_meta( $post_id, '_prep_time', $_prep_time );
			}
			else {
				delete_post_meta( $post_id, '_prep_time' );
			}

			// Cook Time.
			if( ! empty( $_cook_time ) ) {
				update_post_meta( $post_id, '_cook_time', $_cook_time );
			}
			else {
				delete_post_meta( $post_id, '_cook_time' );
			}

			// Yield.
			if( ! empty( $_recipe_yield ) ) {
				update_post_meta( $post_id, '_recipe_yield', $_recipe_yield );
			}
			else {
				delete_post_meta( $post_id, '_recipe_yield' );
			}

			// Notes.
			if( ! empty( $_recipe_notes ) ) {
				update_post_meta( $post_id, '_recipe_notes', $_recipe_notes );
			}
			else {
				delete_post_meta( $post_id, '_recipe_notes' );
			}

			update_post_meta( $post_id, '_recipe_ingredients', $_recipe_ingredients );
			update_post_meta( $post_id, '_recipe_instructions', $_recipe_instructions );
		}

		
		/**
		 * Render Meta Box content.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function callback( $post ) {
			$post_id = $post->ID;
			?>

			<div class="pb-recipe-container">
				<p class="description pb-recipe-description"><?php esc_html_e( 'Display your Recipe using the following shortcode: ', 'pb-recipe' ); ?><span><?php echo '[pb-recipe]'; ?></span></p>

				<div class="pb-recipe-row">
					<div class="pb-recipe-field">
						<label for="pb_recipe_title"><?php esc_html_e( 'Recipe Title:', 'pb-recipe' ); ?></label>
						<input type="text" id="pb_recipe_title" name="pb_recipe_title" value="<?php $this->get_meta( $post_id, '_recipe_title', true ); ?>" autocomplete="off" />
					</div>
					<div class="clear"></div>
					<div class="pb-recipe-time-field">
						<div class="pb-recipe-field">
							<label for="pb_recipe_prep_time"><?php esc_html_e( 'Prep Time:', 'pb-recipe' ); ?></label>
							<input type="text" id="pb_recipe_prep_time" name="pb_recipe_prep_time" value="<?php $this->get_meta( $post_id, '_prep_time', true ); ?>" autocomplete="off" />
						</div>
						<div class="pb-recipe-field">
							<label for="pb_recipe_cook_time"><?php esc_html_e( 'Cook Time:', 'pb-recipe' ); ?></label>
							<input type="text" id="pb_recipe_cook_time" name="pb_recipe_cook_time" value="<?php $this->get_meta( $post_id, '_cook_time', true ); ?>" autocomplete="off" />
						</div>
					</div>
					<div class="clear"></div>
					<div class="pb-recipe-field">
						<label for="pb_recipe_yield"><?php esc_html_e( 'Yield:', 'pb-recipe' ); ?></label>
						<input type="text" id="pb_recipe_yield" name="pb_recipe_yield" value="<?php $this->get_meta( $post_id, '_recipe_yield', true ); ?>" autocomplete="off" />
						<p class="description"><?php esc_html_e( 'The quality produced by the recipe, for example, number of people served, number of servings, etc .', 'pb-recipe' ); ?></p>
					</div>
				</div>
				<div class="clear"></div>

				<div class="pb-recipe-row">
					<h4><?php esc_html_e( 'Ingredients:', 'pb-recipe' ); ?></h4>
					<p class="description"><?php esc_html_e( 'Please add the ingredients for your recipe below. Empty fields will be automatically removed on save. (Example: 1/2 Kg Potatoes)', 'pb-recipe' ); ?></p>
					<ul class="pb-recipe-ingredients">
						<?php
							$_recipe_ingredients = $this->get_meta( $post_id, '_recipe_ingredients', false );
							if( is_array( $_recipe_ingredients ) ) {
								foreach( $_recipe_ingredients as $ingredient ) {
									if( ! empty( $ingredient ) ) {
										echo '<li><input type="text" name="pb_recipe_ingredients[]" value="'. $ingredient .'" autocomplete="off" /></li>';
									}
								}
							}
						?>

					</ul>
					<p><a href="#" class="pb-recipe-add-ingredient"><?php esc_html_e( '+ Add an ingredient', 'pb-recipe' ); ?></a></p>
				</div>
				<div class="clear"></div>

				<div class="pb-recipe-row">
					<h4><?php esc_html_e( 'Instructions:', 'pb-recipe' ); ?></h4>
					<p class="description"><?php esc_html_e( 'Please add the steps for your recipe below. Empty fields will be automatically removed on save.', 'pb-recipe' ); ?></p>
					<ul class="pb-recipe-instructions">
						<?php
							$_recipe_instructions = $this->get_meta( $post_id, '_recipe_instructions', false );
							if( is_array( $_recipe_instructions ) ) {
								foreach( $_recipe_instructions as $instruction ) {
									if( ! empty( $instruction ) ) {
										echo '<li><textarea name="pb_recipe_instructions[]" rows="4" autocomplete="off">'. $instruction .'</textarea></li>';
									}
								}
							}
						?>

					</ul>
					<p><a href="#" class="pb-recipe-add-instruction"><?php esc_html_e( '+ Add an instruction', 'pb-recipe' ); ?></a></p>
				</div>
				<div class="clear"></div>

				<div class="pb-recipe-row pb-recipe-row-last">
					<h4><?php esc_html_e( 'Recipe Notes:', 'pb-recipe' ); ?></h4>
					<textarea name="pb_recipe_notes" rows="6" autocomplete="off"><?php $this->get_meta( $post_id, '_recipe_notes', true ); ?></textarea>
				</div>
			</div>
			<!-- .pb-recipe-container -->
			<?php
			wp_nonce_field( 'pb_recipe_nonce', 'pb_recipe_nonce', false, true );
		}

		
		/**
		 * Retrieve meta value.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function get_meta( $post_id , $key = '', $echo = false ) {
			$meta = get_post_meta( $post_id, $key, true );
			$meta = maybe_unserialize( $meta );
			if( $echo ) {
				echo $meta;
			}
			else {
				return $meta;
			}
		}
	}
}