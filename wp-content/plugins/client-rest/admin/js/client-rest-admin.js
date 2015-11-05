/* global $, cr_ajax_obj */
(function( $ ) {
	'use strict';
	
	var
		configMap = {
			streams_element: '.crest_stream_list'
		},
		stateMap = {
			$container: undefined
		},
		jqueryMap = {},
		
		setJqueryMap, getStreams, initModule
	;
	
	setJqueryMap = function() {
		var $container = stateMap.$container;
		
		jqueryMap = {
			$container : $container,
			$streamList : $container.find( configMap.streams_element )
		}
	
	};
	
	getStreams = function() {
		
		$.ajax({
      url: cr_ajax_obj.ajax_url,
      type: 'post',
      data: {
        _ajax_nonce: cr_ajax_obj.nonce,
        action: 'crest_get_streams'
        //href: this.href
      }
    })
    .done(function( data, status ) {
      console.log(data, 'data');
      $( "#streamContainer" ).html( data );
      //jqueryMap.$streamList.html( data );
      //console.log( jqueryMap, 'jq' );
      console.log(status, 'status');
    });
	};
	
	initModule = function( $container ) {
		stateMap.$container = $container;
		setJqueryMap();
		getStreams();
	};
	/**
	 * All of the code for your Dashboard-specific JavaScript source
	 * should reside in this file.
	 *
	 * Note that this assume you're going to use jQuery, so it prepares
	 * the $ function reference to be used within the scope of this
	 * function.
	 *
	 * From here, you're able to define handlers for when the DOM is
	 * ready:
	 */
	  $(function() {
	  	initModule( $( ".client-rest_page_cres_uberflip_page" ) );
	  });
	 /*
	 * Or when the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and so on.
	 *
	 * Remember that ideally, we should not attach any more than a single DOM-ready or window-load handler
	 * for any particular page. Though other scripts in WordPress core, other plugins, and other themes may
	 * be doing this, we should try to minimize doing that in our own work.
	 */

})( jQuery );
