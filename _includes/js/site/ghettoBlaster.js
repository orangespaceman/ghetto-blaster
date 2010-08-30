/**
 * @fileoverview Ghetto Blaster 
 * 
 */
var ghettoBlaster = function(){

	/**
	 * The options passed through to this function
	 *
	 * @var Object
	 * @private
	 */
	var options = {
		
		/**
		 * The location of the AJAX script on the server
		 *
		 * @var String
		 */		
		ajaxPath : null
	};
	
	
	/**
	 * Initialise the functionality
	 * @param {Object} options The initialisation options
	 * @return void
	 * @public
	 */
	var init = function(initOptions) {
		
		// save any options sent through to the intialisation script, if set
		for (var option in options) {
			if (!!initOptions[option] || initOptions[option] === false) {
				options[option] = initOptions[option];
			}
			
			// error check, if no element is specified then stop
			if (!options[option] && options[option] !== false && options[option] !== 0) {
				throw('Required option not specified: ' + option);
				//return false;
			}
		}
		
		// set play links
		$("li.file a").bind('click', function(e){
			e.preventDefault();
			this.blur();
			play(this);
		});
		
		
		// set volume controls
		$("a#volume-up").bind('click', function(e){
			e.preventDefault();
			this.blur();
			volumeUp();
		});
		$("a#volume-down").bind('click', function(e){
			e.preventDefault();
			this.blur();
			volumeDown();
		});
		$("a#mute").bind('click', function(e){
			e.preventDefault();
			this.blur();
			mute();
		});
		
		// speech
		$("#say form").bind('submit', function(e){
			e.preventDefault();
			var txt = $(this).find('input.text').val();
			var voice = $(this).find('select').val();
			if (txt != "") {
				say(txt, voice);
			}
		});
		
		
		// add stop button
		addStop();
	};
	
		

	/*
	 *
	 */
	var play = function(el) {
		
		var postData, response;
		
		var file = $(el).attr('href');

		postData = 'file='+file+'&method=play';
		postData += '&random='+Math.random();

		// submit request
		response = $.post(
			options.ajaxPath,
			postData,
			function(data, textStatus){
				
				var result = $.parseJSON(data);
				
				console.log(result);

			});
	};
	
	
	/*
	 *
	 */
	var addStop = function() {
		
		// add stop
		$('<a id="stop" href="#">Stop!</a>')
			.appendTo("header")
			.bind('click', function(e){
				e.preventDefault();
				this.blur();
				stopSound();
			});
	};
	
	
	
	/*
	 * 
	 */
	var stopSound = function() {
	
		var postData, response;
		
		postData = 'method=stop';
		postData += '&random='+Math.random();

		// submit request
		response = $.post(
			options.ajaxPath,
			postData,
			function(data, textStatus){
				
				var result = $.parseJSON(data);
				
				console.log(result);

		});
	};



	/*
	 *
	 */	
	var volumeUp = function() {
		
		var postData, response;

		postData = 'method=volumeUp';
		postData += '&random='+Math.random();

		// submit request
		response = $.post(
			options.ajaxPath,
			postData,
			function(data, textStatus){

				var result = $.parseJSON(data);
				$("li#volume-level").text(result.volume);

				//console.log(result);

			});
	};



	/*
	 *
	 */	
	var volumeDown = function() {
		
		var postData, response;

		postData = 'method=volumeDown';
		postData += '&random='+Math.random();

		// submit request
		response = $.post(
			options.ajaxPath,
			postData,
			function(data, textStatus){

				var result = $.parseJSON(data);
				$("li#volume-level").text(result.volume);

				//console.log(result);

			});
	};
	
	
	
	/*
	 *
	 */	
	var mute = function() {
		
		var postData, response;

		postData = 'method=mute';
		postData += '&random='+Math.random();

		// submit request
		response = $.post(
			options.ajaxPath,
			postData,
			function(data, textStatus){

				var result = $.parseJSON(data);
				$("li#volume-level").text(result.volume);

				//console.log(result);

			});
	};
	
	
	
	/*
	 *
	 */	
	var say = function(txt, voice) {
		
		var postData, response;

		postData = 'say='+txt+'&voice='+voice+'&method=say';
		postData += '&random='+Math.random();

		// submit request
		response = $.post(
			options.ajaxPath,
			postData,
			function(data, textStatus){

				var result = $.parseJSON(data);

				console.log(result);

			});
	};
	

	/**
	 * Return value, expose certain methods above
	 */
	return {
		init: init
	};
}();