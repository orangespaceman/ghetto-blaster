/**
 * Site global JS file
 */

	/**
	 * on Dom ready functionality
	 */
		$(document).ready(function() {
		
			// add an extra class to the <body> element for JS-only styling
			$("body").addClass("js");
	
			// init order by
			ghettoBlaster.init({
				ajaxPath: "./_includes/php/Ajax.php"
			});
			
		});


	/*
	 * Window load calls for all pages
	 */
		$(window).load(function() {
			
		});