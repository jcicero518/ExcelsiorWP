/* global UberWidget, $ */
UberWidget.modal = (function( $ ) {
  //---------------- BEGIN MODULE SCOPE VARIABLES --------------
  var
    configMap = {
      card_selector : $( '.uber-card' ),
      slider_selector : $( '.slick-slider' ),
      modal_main_elem : $( '.main-card-content' ),
      modal_related_elem : $( '.related-cards' ),
      modal_title : $( '.modal-title' ),
      ajax_loader : $( '<div\>' ).attr( 'id', 'ajax-loader' )
    },
    stateMap = {
      $container : undefined,
      is_full_screen : false
    },
    wpAjax = {
      nonce : cr_ajax_obj.nonce,
      url : cr_ajax_obj.ajax_url
    },
    angApp,
    angAppData = {},
    jqueryMap = {},

    onCardLinkOver, onCardLinkOut, slickTheSlider, expandCardModal,
    setJqueryMap, configModule, initModule;

  //------------------- BEGIN EVENT HANDLERS -------------------
  onCardLinkOver = function() {
    $( this ).addClass( 'on' );
  };

  onCardLinkOut = function() {
    $( this ).removeClass( 'on' );
  };
  //-------------------- END EVENT HANDLERS --------------------

  slickTheSlider = function() {
    var
      slider = configMap.slider_selector
    ;

    slider.slick();
  };

  expandCardModal = function( event ) {
    var
      $this = $(this),
      is_full_screen = stateMap.is_full_screen,
      $modal, $modalDialog;

    $modal = $this.parents( '#cardModal' );
    $modal.toggleClass( 'full-screen' );
    if ( is_full_screen === false ) {
      stateMap.is_full_screen = true;
    } else {
      stateMap.is_full_screen = false;
    }
  };

  //--------------------- BEGIN DOM METHODS --------------------
  // Begin DOM method /setJqueryMap/
  setJqueryMap = function() {
    var $container = stateMap.$container;

    jqueryMap = {
      $container : $container,
      $cardModal : $container.find( '#cardModal' ),
      $cardElem : $container.find( '.uber-card' ),
      $linkElem : $container.find( '.link_overlay' )
    };
  };
  // End DOM method /setJqueryMap/
  //---------------------- END DOM METHODS ---------------------

  // Begin public method /configModule/
  // Purpose    : Adjust configuration of allowed keys
  // Arguments  : A map of settable keys and values
  //   * color_name - color to use
  // Settings   :
  //   * configMap.settable_map declares allowed keys
  // Returns    : true
  // Throws     : none
  //
  configModule = function( arg_map ) {

  };

  initModule = function( $container ) {
    stateMap.$container = $container;
    setJqueryMap();

    var
      $linkElem = jqueryMap.$linkElem,
      cardModal = jqueryMap.$cardModal,
      $modalTitle = configMap.modal_title,
      wp_nonce = wpAjax.nonce,
      wp_ajax_url = wpAjax.url;

    //$( '#cardModalEnlarge' ).on( 'click', expandCardModal );

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
        card_id    : button.data( 'card-id' ),
        card_stream_id : button.data( 'card-stream-id' ),
        card_title : button.data( 'card-title' ),
        card_type  : button.data( 'card-type' ),
        card_date  : button.data( 'card-date' )
      };

      modal.find( '.modal-title' ).html( card_data.card_title );

      $.when(
        $.ajax({
          url: wp_ajax_url,
          type: 'post',
          data: {
            //_ajax_nonce: cr_ajax_obj.nonce,
            action: 'crest_get_stream_detail',
            card_id: card_data.card_id,
            card_title: card_data.card_title
          }
        })
        .done(function( data, status ) {
          //console.log(data, 'data');
          //console.log( modal, 'modal' );
          modal.find( '.main-card-content' ).html( data );
          modal.find( '#modal-card-id' ).val( card_data.card_id );
          modal.find( '#modal-stream-id' ).val( card_data.card_stream_id );
        })
      ).then(
        function() {
          $.ajax({
            url: wp_ajax_url,
            type: 'post',
            data: {
              //_ajax_nonce: cr_ajax_obj.nonce,
              action: 'crest_get_transient_data',
              stream_id: card_data.card_stream_id,
              card_type: card_data.card_type
            }
          })
          .done(function( data, status ) {
            console.log( card_data, 'card_data' );
            console.log( data, 'data from ajax for related' );
            // Calls when ready, needs some work
            if ( card_data.card_type === 'youtube' || card_data.card_tpe === 'video' ) {
              onYouTubeIframeAPIReady();
            }
            modal.find( '.related-cards' ).html( data );
          });
        }
      );
    });

    cardModal.on( 'hide.bs.modal', function( evt ) {
	    var
	    	modal = $( this ),
        $player = $( "<div \>" ).attr( 'id', 'player' ),
	    	$mainCard = $( "<div \>" ).attr('class', 'main-card-content'),
        is_full_screen = stateMap.is_full_screen
	    ;


      // if user closes the modal while its still set to full
      // screen, switch it back
      /*if ( is_full_screen ) {
        modal.removeClass( 'full-screen' );
        stateMap.is_full_screen = false;
      }*/
      modal.find( '.main-card' ).empty();
	    modal.find( '.related-cards' ).empty();

	    modal.find( '.main-card' ).append( $player );
	    modal.find( '.main-card' ).append( $mainCard );
	  });
  };

  //var data = JSON.parse( HubItemData );
  $(function() {
    //initModule( $( "body" ) );
	  //$( ".slick-slider" ).slick();
	});

  return {
    initModule : initModule
  };
})( jQuery );
