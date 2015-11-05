/*global $ */
(function( $ ) {
  var
    configMap = {
      card_selector : $( '.uber-card' ),
      ajax_loader : $( '<div\>' ).attr( 'id', 'ajax-loader' )
    },
    stateMap = {
      $container : undefined
    },
    jqueryMap = {},

    setJqueryMap, fadeInCards, initModule
  ;

  setJqueryMap = function() {
    var $container = stateMap.$container;

    jqueryMap = {
      $container : $container,
      $cardElem : $container.find( '.uber-card' ),
      $linkElem : $container.find( '.link_overlay' ),
      $cardModal : $container.find( '#cardModal' )
    };

  };

  fadeInCards = function() {
    var $cardColl = jqueryMap.$cardElem;

    $cardColl.each( function() {
      $(this).addClass( 'in' );
    });
  };

  initModule = function() {
    stateMap.$container = $( "body" );
    setJqueryMap();
    // Loop through card collection and add the "in" class
    // to complement "fade" and only show after DOMReady
    fadeInCards();
  };

  $(function() {
    initModule();
  });

  //return { initModule : initModule };

})( jQuery );
