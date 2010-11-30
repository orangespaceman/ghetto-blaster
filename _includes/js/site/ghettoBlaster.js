/**
 * @fileoverview Ghetto Blaster 
 * 
 */
var ghettoBlaster = function(){

	// volume controller ul
	$volumeUl = null;
	
	
	// volume text
	$volText = null;

	
	// volume checking interval
	volInterval = null;
	
	
	// play type (preview or broadcast)
	playType = null;
	


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
		ajaxPath : null,
		
		
		/**
		 * The interval time (in seconds) between volume checks
		 *
		 * 
		 */
		interval : 10
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
		
		
		// store dom elements for later use
		$volume = $("#volume ul");
		$volText = $("li#volume-level");
		
		
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
		
		$("#notify form input").bind('click', function(e){
			e.preventDefault();
			
			notify($("#notify-opt option:selected").val());
		});
		
		$("#notify-prefs a").bind('click', function(e){
			e.preventDefault();
			notifyPrefsPopup();
		});
		
		$("#prefs-form").bind('submit', function(e){
			e.preventDefault();
			
			var checked ="";
			
			$('#prefs-form input[type="checkbox"]:checked').each(function(){
				checked += $(this).val()+",";
			});
			checked = checked.substring(0, checked.length-1);
					
			updatePrefs(checked);
		});
		
		
		// add stop button
		addStop();
		
		
		// check volume every x seconds
		volInterval = setInterval(getVolume, options.interval * 1000);
		
		
		// create broadcast/preview functionality
		var playSelector = $('input[name=play]');
		playType = playSelector.val();
		playSelector.bind('change', function(e){
			playType = $(this).val();
		});
	};
	
		

	/*
	 *
	 */
	var addStop = function() {
		
		// add stop
		$('<a id="stop" href="#">Stop!</a>')
			.appendTo("#stop-holder")
			.bind('click', function(e){
				e.preventDefault();
				this.blur();
				stopSound();
			});
	};
	
	
	
	/*
	 *
	 */
	var play = function(el) {
		var file = $(el).attr('href');

		// condition : broadcast or preview?
		if (playType == "broadcast") {
			_ajax({
				method: "play",
				params: 'file='+file
			});
			
		} else if (playType == "preview") {
			previewTrackPlay(el);
		}
	};
	
	
	/*
	 * 
	 */
	var stopSound = function() {
		// condition : broadcast or preview?
		if (playType == "broadcast") {
			_ajax({
				method: "stop"
			});
		} else if (playType == "preview") {
			previewTrackStop();
		}
	};
	
	
	/*
	 *
	 */	
	var say = function(txt, voice) {
		_ajax({
			method: "say",
			params: 'say='+txt+'&voice='+voice
		});
	};
	
	
	
	/*
	 *
	 */	
	var volumeUp = function() {
		_ajax({
			method: "volumeUp",
			update: $volText
		});
	};



	/*
	 *
	 */	
	var volumeDown = function() {
		_ajax({
			method: "volumeDown",
			update: $volText
		});
	};
	
	
	
	/*
	 *
	 */	
	var mute = function() {
		_ajax({
			method: "mute",
			update: $volText
		});
	};
	
	
	
	/*
	 *
	 */	
	var getVolume = function() {
		_ajax({
			method: "getVolume",
			update: $volText
		});
	};
	
	
	
	/*
	 *
	 */
	var _ajax = function(opts) {
	
		showLoader();
		
		var postData, response;

		postData = 'method='+opts.method;

		if (!!opts.params) {
			postData += '&'+opts.params;
		}

		postData += '&random='+Math.random();

		// submit request
		response = $.post(
			options.ajaxPath,
			postData,
			function(data, textStatus){

				var result = $.parseJSON(data);
				
				if (!!opts.update) {
					opts.update.text(result.volume);
				}
				
				hideLoader();

				//console.log(result);

			});
	};
	
	
	
	
	
	/*
	 *
	 */
	var showLoader = function() {
		$volume.addClass('loading');
	};
	
	
	
	/*
	 *
	 */
	var hideLoader = function() {
		$volume.removeClass('loading');
	};
	
	
	
	/*
	 * 
	 */
	var previewTrackPlay = function(el) {
		YAHOO.MediaPlayer.stop();
		
		var file = $(el).parent()[0];
		YAHOO.MediaPlayer.addTracks(file, null, true);
		YAHOO.MediaPlayer.play();
	};


	/*
	 *
	 */
	var previewTrackStop = function() {
		YAHOO.MediaPlayer.stop();
	};
	
	/*
	 *
	 */
	var notify = function(type){
	
		_ajax({
			method: "notify",
			params: "type="+type
		});
	}
	
	/*
	*
	*/
	
	var notifyPrefsPopup = function(){
		window.open('/notify-prefs.php', 'Notify Preferences', 'width=800, height=600', 'location=no');
	}
	
	/*
	*
	*/
	var updatePrefs = function(checked){
		
		if(checked != ""){
			_ajax({
				method: "updatePref",
				params: "checked="+checked
			})
		}else{
			_ajax({
				method: "updatePref",
			})
		}
	}
	

	/**
	 * Return value, expose certain methods above
	 */
	return {
		init: init
	};
}();