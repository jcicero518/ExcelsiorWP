/*jslint browser: true, white: true */
/*global $ */

var lazy_unveil = (function( $ ) {
	var
		configMap = { 
			dummyImage : 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==',
			unveil_time : 20,
			anim_time : 100
		},
		stateMap = { 
			$container : undefined
		},
		jqueryMap = { 
			$container : undefined,
			$images : undefined
		},
		
		lazyLoadImages, setJqueryMap, initModule;
	
	lazyLoadImages = function( ) {
		var 
			$images = jqueryMap.$images,
			image, image_src, image_class, stop_flag = false;
			
		$images.each(function( ) {
			image = $(this);
			image_src = $(this).attr('src');
			image_class = $(this).attr('class');
			
			if ( $(this).hasClass('slide') || $(this).parent('div').hasClass('item') ) {
				stop_flag = true;
				$(this).attr('src', image_src)
					.css('opacity', 1);
			}
			
			if ( !stop_flag ) {
				
				$(this)
					.attr('data-src', image_src)
					.attr('src', configMap.dummyImage)
					.unveil(configMap.unveil_time, function() {
						$(this).load(function() {
							$(this).animate({
								opacity : 1
								}, configMap.anim_time);
					});
				});
			} else {
				stop_flag = false;
			}
		});
		
	};
	
	setJqueryMap = function( ) {
		var $container = stateMap.$container;
		
		jqueryMap = { 
			$images : $container.find( 'img' )
		};
	};
	
	initModule = function( $container ) {
		stateMap.$container = $container;
		setJqueryMap();
		
		lazyLoadImages();
	};
	
	return { initModule : initModule };
	
})( jQuery );

jQuery(function( $ ) {
	lazy_unveil.initModule( $("body") );
});