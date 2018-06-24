<?php
/**
 * @package PB_Recipe
 * @since 1.0
 */
if( ! class_exists( 'PB_Recipe_Shortcodes' ) ) {
	class PB_Recipe_Shortcodes{
		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {
			add_shortcode( 'pb-recipe', array( $this, 'pb_recipe' ) );
			if( ! shortcode_exists( 'pb-recipe-index' ) ) {
				add_shortcode( 'pb-recipe-index', array( $this, 'pb_recipe_index') );
			}
		}


		/**
		 * Builds the Recipe Card shortcode output.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function pb_recipe( $atts ) {
			$post_id = get_the_ID();
			$output  = '';

			$recipe_information  = $this->recipe_information( $post_id );
			$recipe_ingredients  = $this->recipe_ingredients( $post_id );
			$recipe_instructions = $this->recipe_instructions( $post_id );
			$recipe_notes        = $this->recipe_notes( $post_id );

			if( ! empty( $recipe_information ) || ! empty( $recipe_ingredients ) || ! empty( $recipe_instructions ) || ! empty( $recipe_notes ) ) {
				
				$output .= '<div id="pb-recipe'. $post_id .'" class="pb-recipe hrecipe" itemscope itemtype="http://schema.org/Recipe">';

					// Information.
					if( ! empty( $recipe_information ) ) {
						$output .= '<div class="pb-recipe-details">';
							$output .= $recipe_information;
						$output .= '</div>';
					}

					// Ingredients.
					$output .= $recipe_ingredients;
					
					// Instructions.
					$output .= $recipe_instructions;

					// Notes
					$output .= $recipe_notes;

					// Print Button.
					$print = pb_recipe_get_option('print');
					if( '1' == $print ) {
						$print_button = apply_filters( 'pb_recipe_print_button_text', esc_html( pb_recipe_get_option( 'print_label' ) ) );
						$output .= '<p class="pb-recipe-print-button"><button type="button" onclick="pbRecipePrint(\'pb-recipe'. $post_id .'\')">'. $print_button .'</button></p>';
					}
					
				$output .= '</div>';
			}

			return $output;
		}


		/**
		 * Recipe Information.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function recipe_information( $post_id ) {
			$output           = '';
			$recipe_title     = get_post_meta( $post_id, '_recipe_title', true );
			$recipe_prep_time = get_post_meta( $post_id, '_prep_time', true );
			$recipe_cook_time = get_post_meta( $post_id, '_cook_time', true );
			$recipe_yield     = get_post_meta( $post_id, '_recipe_yield', true );

			if( ! empty( $recipe_title ) ) {
				$output .= '<h2 class="pb-recipe-title" itemprop="name">'. esc_html( $recipe_title ) .'</h2>';
				if( has_post_thumbnail( $post_id ) ) {
					$thumb_args = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
					if( $thumb_args ) {
						$output .= '<link itemprop="image" href="'. $thumb_args[0] .'" />';
					}
				}
			}

			if( ! empty( $recipe_prep_time ) || ! empty( $recipe_cook_time ) || ! empty( $recipe_yield ) ) {
				$output .= '<ul class="pb-recipe-meta">';
					if( ! empty( $recipe_yield ) ) {
						$output .= sprintf( '<li>%1$s<span itemprop="recipeYield"> %2$s</span></li>', esc_html( pb_recipe_get_option( 'yield_label' ) ), $recipe_yield );
					}
					if( ! empty( $recipe_prep_time ) ) {
						$output .= sprintf( '<li>%1$s<span itemprop="prepTime"> %2$s</span></li>', esc_html( pb_recipe_get_option( 'prep_label' ) ), $recipe_prep_time );
					}
					if( ! empty( $recipe_cook_time ) ) {
						$output .= sprintf( '<li>%1$s<span itemprop="cookTime"> %2$s</span></li>', esc_html( pb_recipe_get_option( 'cook_label' ) ), $recipe_cook_time );
					}
				$output .= '</ul>';
			}

			return $output;
		}


		/**
		 * Recipe Ingredients.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function recipe_ingredients( $post_id ) {
			$recipe_ingredients = get_post_meta( $post_id, '_recipe_ingredients', true );
			$recipe_ingredients = maybe_unserialize( $recipe_ingredients );
			$output             = '';
			$list               = '';

			if( ! empty( $recipe_ingredients ) && is_array( $recipe_ingredients ) ) {
				foreach( $recipe_ingredients as $ingredient ) {
					if( ! empty( $ingredient ) ) {
						$list .= '<li itemprop="recipeIngredient">'. esc_html( $ingredient ) .'</li>';
					}
				}
			}

			if( ! empty( $list ) ) {
				$output .= '<div class="pb-recipe-ingredients">';
					$ingredients_label = pb_recipe_get_option( 'ingredients_label' );
					if( !empty( $ingredients_label ) ) {
						$output .= '<h3 class="pb-recipe-box-title">'. esc_html( $ingredients_label ) .'</h3>';
					}
					$output .= '<div class="pb-recipe-box-content">';
						$output .= '<ul>'. $list .'</ul>';
					$output .= '</div>';
				$output .= '</div>';
			}

			return $output;
		}


		/**
		 * Recipe Instructions.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function recipe_instructions( $post_id ) {
			$recipe_instructions = get_post_meta( $post_id, '_recipe_instructions', true );
			$recipe_instructions = maybe_unserialize( $recipe_instructions );
			$output              = '';
			$list                = '';
			if( ! empty( $recipe_instructions ) && is_array( $recipe_instructions ) ) {
				foreach( $recipe_instructions as $instruction ) {
					if( ! empty( $instruction ) ) {
						$list .= '<li>'. wpautop( $instruction ) .'</li>';
					}
				}
			}

			if( ! empty( $list ) ) {
				$output .= '<div class="pb-recipe-instructions">';
					$instructions_label = pb_recipe_get_option( 'instructions_label' );
					if( ! empty( $instructions_label ) ) {
						$output .= '<h3 class="pb-recipe-box-title">'. esc_html( $instructions_label ) .'</h3>';
					}
					$output .= '<div class="pb-recipe-box-content">';
						$output .= '<ol itemprop="recipeInstructions">'. $list .'</ol>';
					$output .= '</div>';
				$output .= '</div>';
			}

			return $output;
		}


		/**
		 * Recipe Notes.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function recipe_notes( $post_id ) {
			$recipe_notes = get_post_meta( $post_id, '_recipe_notes', true );
			$output       = '';

			if( ! empty( $recipe_notes ) ) {
				$output .= '<div class="pb-recipe-notes">';
					$notes_label = pb_recipe_get_option( 'notes_label' );
					if( ! empty( $notes_label ) ) {
						$output .= '<h3 class="pb-recipe-box-title">'. esc_html( $notes_label ) .'</h3>';
					}
					$output .= '<div class="pb-recipe-box-content">'. wpautop( $recipe_notes ) .'</div>';
				$output .= '</div>';
			}

			return $output;
		}


		/**
		 * Builds the Recipe Index shortcode output.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function pb_recipe_index( $atts ) {}

	}
}