/* global $, cr_ajax_obj */
(function( $ ) {
	'use strict';
	
	var 
	  configMap = {
  	  template: '.pagination'
	  },
	  stateMap = {
  	  $container: undefined
    },
    jqueryMap = {},
    wpAjaxMap = cr_ajax_obj,
    
    setPagination
  ;
  
  $.fn.wpPagination = function( options ) {
    options = $.extend({
      links: 'a',
      action: 'crest_get_page',
      ajaxUrl: cr_ajax_obj.ajax_url,
      next: '.next',
      previous: '.previous',
      disablePreviousNext: true
    }, options );
    
    var WPPagination = function( element ) {
      this.$el = $( element );
      this.init();
    };

    WPPagination.prototype = {
      init: function() {
        this.createLoader();
        this.handlePreviousNextLinks();
				this.handleLinks();
      },
      createLoader: function() {
				var self = this;
				self.$el.before( "<div id='pagination-loader'></div>" );
			},
			handlePreviousNextLinks: function() {
				var self = this;
				var $previous = $( options.previous, self.$el );
				var $next = $( options.next, self.$el );
				
				if ( options.disablePreviousNext ) {
					$previous.remove();
					$next.remove();
				} else {
					$previous.addClass( "clicked" );
					$next.addClass( "clicked" );
				}
			},
			handleLinks: function() {
				var self = this,
					$links = $( options.links, self.$el ),
					$loader = $( "#pagination-loader" );

					$links.click(function( e ) {
						e.preventDefault();
						var $a = $( this ),
							url = $a.attr( "href" ),
							page = url.match( /\d+/g ), // Get the page number
							
							pageNumber = page[1],
							data = {
								action: options.action, // Pass the AJAX action name along with the page number
								page: pageNumber,
								_ajax_nonce: cr_ajax_obj.nonce
							};

						  if ( !$a.hasClass( "clicked" ) ) { // We don't want duplicated posts
							
							  $loader.show(); // Show the loader
							
                $.post( options.ajaxUrl, data, function( html ) {
								  $loader.hide(); // Hide the loader
                  $loader.before( html ); // Insert posts
                  $a.addClass( "clicked" ); // Flag the current link as clicked
							  });
						  }
				  });
			 }
		};
		
		return this.each(function() {
  		var element = this;
  		//var pagination = Object.create( element );
  		var pagination = new WPPagination( element );
  		//console.log(pagination);
    });
    
  };
		
  setPagination = function() {
  	 stateMap.$container = $( configMap.template );
  };
	  
  $(function() {
      setPagination();
      //var $pag = $(".pagination").find('a');
      //$pag.on( 'click', function( event ) {
        //event.preventDefault();
      //}); 
      //console.log(stateMap.$container);
      if ( stateMap.$container.length ) {
        stateMap.$container.wpPagination();
      }
         
  });
	  
	  /*
  	  $(function() {
      setPagination();
      
      var $container = stateMap.$container;
      var $links = $container.find('a');
      
      $links.on( 'click', function( event ) {
        event.preventDefault();
        var this2 = this;
        console.log(this.href);
        $.ajax({
          url: cr_ajax_obj.ajax_url,
          type: 'post',
          data: {
            _ajax_nonce: cr_ajax_obj.nonce,
            action: 'crest_get_page',
            href: this.href
          }
        })
        .done(function( data, status ) {
          console.log(data, 'data');
          console.log(status, 'status');
        });
        
      });
	  });
	  */
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
