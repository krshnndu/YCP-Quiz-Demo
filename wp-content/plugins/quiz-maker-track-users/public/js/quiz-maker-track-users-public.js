(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(document).ready(function(){
		var ctrlDown = false,
			ctrlKey = 17,
			cmdKey = 91,
			cKey = 67;
		var copy_count = 0;
		var hint_count = 0;

		// Document Ctrl + C
		$(document).keydown(function(e) {
			if (e.keyCode == ctrlKey || e.keyCode == cmdKey) ctrlDown = true;
		}).keyup(function(e) {
			if (e.keyCode == ctrlKey || e.keyCode == cmdKey) ctrlDown = false;
		});

		$(document).keydown(function(e) {
			if (ctrlDown && (e.keyCode == cKey)){
				copy_count ++;  
				$(document).find('.ays_quiz_track_users').attr( 'data-copy', copy_count );
			} 
		});

		//Hint Click Count
		$(document).on('click', '.ays_question_hint_container', function(){
			hint_count ++;
			$(document).find('.ays_quiz_track_users').attr( 'data-hint', hint_count );
		});
	});

})( jQuery );
