/*
Plugin Name: PB Recipe
Plugin URI: http://www.pixelboom.net/
Description: Create recipe in your WordPress posts.
Author: PixelBoom
Version: 1.0
Author URI: http://www.pixelboom.net/
*/
var pbRecipeWindow = null;
function pbRecipePrint(id) {
	var content = document.getElementById(id).innerHTML;
	pbRecipeWindow = window.open();
	self.focus();
	pbRecipeWindow.document.open();
	pbRecipeWindow.document.write('<!DOCTYPE html><html><head><link charset="utf-8" href="'+ PB_Recipe.print_css_url +'" rel="stylesheet" type="text/css" /></head><body>');
	pbRecipeWindow.document.write('<div id="pb-recipe-print">');
	pbRecipeWindow.document.write(content);
	pbRecipeWindow.document.write('</div>');
	pbRecipeWindow.document.write('<script>window.onload=function(){setTimeout(function(){window.print(),setTimeout(function(){window.close()},300)},0)};</script>');
	pbRecipeWindow.document.write('</body></html>');
	pbRecipeWindow.document.close();
};