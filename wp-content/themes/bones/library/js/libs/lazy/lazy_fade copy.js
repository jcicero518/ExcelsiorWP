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
    
    setJqueryMap, fadeInCards, initCardModal, onCardHover, onCardLinkOver, onCardLinkOut, openCardModal, initModule
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
  
  initCardModal = function() {
    var $cardElem = configMap.card_selector;
    
    $cardElem.on( 'show.bs.modal', function( evt ) {
      var button = $( evt ).relatedTarget; // Button that triggered the modal
      var data = button.data( 'id' );
      /*
        AJAX here?
      */
      var modal = $( this );
      //modal.find( '.modal-title' ).text( 'New Title' );
      //modal.find( '.modal-body input' ).val( data );
      
    });
  };
  
  onCardHover = function( evt ) {
    console.log( $( this ) );
    $( this ).parent().find( '.hover-overlay ').toggleClass( 'on' );
  };
  
  onCardLinkOver = function() {
    $( this ).addClass( 'on' );
  };
  
  onCardLinkOut = function() {
    $( this ).removeClass( 'on' );
  };
  
  openCardModal = function() {
    
  };
  
  initModule = function() {
     
    stateMap.$container = $( "body" );
    setJqueryMap();
    // Loop through card collection and add the "in" class
    // to complement "fade" and only show after DOMReady
    fadeInCards();
    
    var $linkElem = jqueryMap.$linkElem;
    $linkElem.hoverIntent({
      over    : onCardLinkOver,
      out     : onCardLinkOut,
      timeout : 5
    });
    
    var cardModal = jqueryMap.$cardModal;
    $( ".link_overlay" ).on( 'click', function() {
      cardModal.modal( 'show', $( this ) );
    });
    
    cardModal.on( 'show.bs.modal', function( evt ) {
      var 
        button = $( evt.relatedTarget ), // Button that triggered the modal
        modal = $( this ),
        loader = configMap.ajax_loader,
        card_data = {}
      ;
      
      
      modal.find( '.main-card-content' ).html( loader );
      loader.show();
      
      card_data = {
        card_id        : button.data( 'card-id' ),
        card_stream_id : button.data( 'card-stream-id' ),
        card_title     : button.data( 'card-title' ),
        card_type      : button.data( 'card-type' ),
        card_date      : button.data( 'card-date' )
      };
      
      modal.find( '.modal-title' ).html( card_data.card_title );
          
      $.when( 
        $.ajax({
          url: 'http://amorphouswebsolutions.com/wp-admin/admin-ajax.php',
          type: 'post',
          data: {
            //_ajax_nonce: cr_ajax_obj.nonce,
            action: 'crest_get_stream_detail',
            card_id: card_data.card_id,
            card_title: card_data.card_title
          }
        })
        .done(function( data, status ) {
          console.log(data, 'data');
          console.log( modal, 'modal' );
        
          modal.find( '.main-card-content' ).html( data );
          modal.find( '#modal-card-id' ).val( card_data.card_id );
          modal.find( '#modal-stream-id' ).val( card_data.card_stream_id );
        })
      ).then(
        function() { 
          $.ajax({
            url: 'http://amorphouswebsolutions.com/wp-admin/admin-ajax.php',
            type: 'post',
            data: {
              //_ajax_nonce: cr_ajax_obj.nonce,
              action: 'crest_get_transient_data',
              stream_id: card_data.card_stream_id
            }
          })
          .done(function( data, status ) {
            console.log( card_data, 'card_data' );
            // Calls when ready, needs some work
            if ( card_data.card_type === 'youtube' || card_data.card_tpe === 'video' ) {
              onYouTubeIframeAPIReady();
            }
            modal.find( '.related-cards' ).html( data );
          });
        }
      );
    });
    
    /*cardModal.on( 'shown.bs.modal', function( evt ) {
      var 
        button = $( evt.relatedTarget ), // Button that triggered the modal
        modal = $( this ),
        loader = configMap.ajax_loader,
        card_data = {}
      ;
      
      card_data = {
        stream_id : modal.find( '#modal-stream-id' )
      };
      
      
      $.ajax({
        url: 'http://amorphouswebsolutions.com/wp-admin/admin-ajax.php',
        type: 'post',
        data: {
          //_ajax_nonce: cr_ajax_obj.nonce,
          action: 'crest_get_transient_data',
          stream_id: card_data.stream_id
        }
      })
      .done(function( data, status ) {
        console.log( data, 'datatransient' );
        modal.find( '.related-cards' ).html( data );
      });
      
    });*/
    
    cardModal.on( 'hide.bs.modal', function( evt ) {
	    var 
	    	modal = $( this ),
	    	$player = $( "<div \>" ).attr( 'id', 'player' ),
	    	$mainCard = $( "<div \>" ).attr('class', 'main-card-content')
	    ;
	    
	    modal.find( '.main-card' ).empty();
	    modal.find( '.related-cards' ).empty();
	    
	    modal.find( '.main-card' ).append( $player );
	    modal.find( '.main-card' ).append( $mainCard );
	    
	  });
	    	
  };
  
  $(function() {
    initModule();
  });
  
  //return { initModule : initModule };
  
})( jQuery );