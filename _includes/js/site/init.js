/**
 * Site global JS file
 */

	/**
	 * on Dom ready functionality
	 */
		$(document).ready(function() {
		
			$body = $("body");
			
			// add an extra class to the <body> element for JS-only styling
			$body.addClass("js");
			
			if ($body.attr('id') == "ghetto-blaster") {
				ghettoBlaster.init({
					ajaxPath: "./_includes/php/Ajax.php",
					interval: 10
				});
			}
		});


	/*
	 * Window load calls for all pages
	 */
		$(window).load(function() {

		});
			

	/*
	 * Yahoo media player config
	 */	
		var YMPParams ={
			displaystate:1,
			autoadvance:false,
			parse:false,
			playlink:false
		};
