(function($) {
	"use strict";

	// Metaboxes.
	var PB_Recipe = {

		init: function() {
			this.addIngredients();
			this.addInstructions();
			this.sortableInit();
		},

		addIngredients: function() {
			$(document).on('click', '.pb-recipe-add-ingredient', function(e) {
				e.preventDefault();
				$('.pb-recipe-ingredients').append('<li><input type="text" name="pb_recipe_ingredients[]" value="" autocomplete="off" /></li>');
			});
		},

		addInstructions: function() {
			$(document).on('click', '.pb-recipe-add-instruction', function(e) {
				e.preventDefault();
				$('.pb-recipe-instructions').append('<li><textarea name="pb_recipe_instructions[]" rows="4" autocomplete="off"></textarea></li>');
			});
		},

		sortableInit: function() {
			if( ! $.fn.sortable ) {
				return;
			}

			$(".pb-recipe-instructions, .pb-recipe-ingredients").sortable().trigger("sortupdate");
		}
	}

	PB_Recipe.init();

})(jQuery);