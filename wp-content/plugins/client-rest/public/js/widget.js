/* global WidgetInfo, HubItemData, RespJson, $ */
var UberWidget = (function( $ ) {
  //---------------- BEGIN MODULE SCOPE VARIABLES --------------
  var
    configMap = {
      card_selector : $( '.uber-card' ),
      slider_selector : $( '.slick-slider' ),
      modal_main_elem : $( '.main-card' ),
      modal_main_elem_content : $( '.main-card-content' ),
      modal_related_elem : $( '.related-cards' ),
      modal_title : $( '.modal-title' ),
      fblike : $( '.fb-like' ),
      ajax_loader : $( '<div\>' ).attr( 'id', 'ajax-loader' )
    },
    stateMap = {
      $container : undefined,
      related_card_selected_id : undefined,
      is_full_screen : false
    },
    wpAjax = {
      nonce : cr_ajax_obj.nonce,
      url : cr_ajax_obj.ajax_url
    },
    angApp,
    angAppData = {},
    jqueryMap = {},
    modalCollMap = {},

    onCardLinkOver, onCardLinkOut, slickTheSlider, expandCardModal, toggleRelatedColl,
    relatedCardSelect, loadFbCode, setJqueryMap, setModalCollMap, configModule, initModule;

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
    // update modal columns based on expanded state
    toggleRelatedColl();
  };
  
  toggleRelatedColl = function() {
    var
      $mainColl = configMap.modal_main_elem,
      $relatedColl = configMap.modal_related_elem,
      is_full_screen = stateMap.is_full_screen;

    if ( is_full_screen ) {
      $relatedColl.hide();
      $mainColl.addClass( 'col-md-12' );
    } else {
      $relatedColl.show();
      $mainColl.removeClass( 'col-md-12' );
    }
  };

  relatedCardSelect = function() {
    var
      related_card_id = stateMap.related_card_selected_id,
      $cardModal = jqueryMap.$cardModal,
      $relatedCardsContainer, $relatedCards;

    $relatedCardsContainer = $cardModal.find( '.related-cards' );
    $relatedCards = $relatedCardsContainer.find( '.panel-default' );

    $relatedCards.each(function() {
      var currentId = $( this ).data( 'itemId' );

      if ( currentId === related_card_id ) {
        $( this ).addClass( 'card-selected' );
      } else {
        $( this ).removeClass( 'card-selected' );
      }
    });
  };
  
  loadFbCode = (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=262970880381882";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
    


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
  
  setModalCollMap = function() {
    var $modal = jqueryMap.$cardModal;

    modalCollMap = {
      $modal : $modal,
      $mainCard : $modal.find( '.main-card' ),
      $mainCardContent : $modal.find( '.main-card-content' ),
      $relatedSide : $modal.find( '.related-side' ),
      $relatedCards : $modal.find( '.related-cards' ),
      $bothSides : $modal.find( '.slim-scroll' ),
      $modalTitle : $modal.find( '.modal-title' )
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
    if ( typeof RespJson !== undefined ) {
      //angAppData = JSON.parse( RespJson.resp_json );
      //console.log( angAppData );
      //console.log( angApp );
    }

    stateMap.$container = $container;
    setJqueryMap();
    slickTheSlider();


    var
      $linkElem = jqueryMap.$linkElem,
      cardModal = jqueryMap.$cardModal,
      $modalTitle = configMap.modal_title,
      $fb = configMap.fblike,
      wp_nonce = wpAjax.nonce,
      wp_ajax_url = wpAjax.url;

    UberWidget.modal.initModule( cardModal );

    $( '#cardModalEnlarge' ).on( 'click', expandCardModal );

    $linkElem.hoverIntent({
      over    : onCardLinkOver,
      out     : onCardLinkOut,
      timeout : 5
    });

    $linkElem.on( 'click', function() {
      cardModal.modal( 'show', $( this ) );
    });

    cardModal.on( 'show.bs.modal', function( evt ) {
      var
        button = $( evt.relatedTarget ), // Button that triggered the modal
        modal = $( this ),
        loader = configMap.ajax_loader,
        card_data = {}
      ;
      
      setModalCollMap();
      
      modal.find( '.main-card-content' ).html( loader );
      modal.find( '.related-cards' ).html( loader );
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
            _ajax_nonce: wp_nonce,
            action: 'crest_get_stream_detail',
            card_id: card_data.card_id,
            card_title: card_data.card_title
          }
        })
        .done(function( data, status ) {
          console.log( data, 'main card' );
          modal.find( '.main-card-content' ).html( data );
          modal.find( '.main-card-content' ).addClass( card_data.card_type );
          modal.find( '#modal-card-id' ).val( card_data.card_id );
          modal.find( '#modal-stream-id' ).val( card_data.card_stream_id );
          if ( card_data.card_type === 'facebook' ) {
            /*var $plHolder = $( ".fb-placeholder" );
            var $fbLikeButton = $( "<div \>" );
            $fbLikeButton.addClass( 'fb-like' );
            $fbLikeButton
              .attr( 'data-layout', 'standard' )
              .attr( 'data-action', 'like' )
              .attr( 'data-show-faces', 'false' )
              .attr( 'data-share', 'true' );
              */
          }
        })
      ).then(
        function() {
          $.ajax({
            url: wp_ajax_url,
            type: 'post',
            data: {
              _ajax_nonce: wp_nonce,
              action: 'crest_get_transient_data',
              stream_id: card_data.card_stream_id,
              card_type: card_data.card_type
            }
          })
          .done(function( data, status ) {
            // Calls when ready, needs some work
            if ( card_data.card_type === 'youtube' || card_data.card_type === 'video' ) {
              onYouTubeIframeAPIReady();
            }

            var $relatedCards = modal.find( '.related-cards' );
            $relatedCards.html( data );

            $( '.panel-default' ).on( 'click', function() {
              var itemId = $( this ).data( 'itemId' );
              var itemTitle = $( this ).data( 'itemTitle' );
              var cardType = $( this ).data( 'cardType' );
              stateMap.related_card_selected_id = itemId;

              relatedCardSelect();

              modal.find( '.main-card-content' ).html( loader );

              $.ajax({
                url: wp_ajax_url,
                type: 'post',
                data: {
                  _ajax_nonce: wp_nonce,
                  action: 'crest_get_stream_detail',
                  card_id: itemId,
                  card_title: itemTitle
                }
            })
            .done(function( data, status ) {

              //modal.find( '.related-cards' ).html( loader );
              loader.show();

              modal.find( '.modal-title' ).html( itemTitle );
              modal.find( '.main-card-content' ).html( data );
              if ( cardType === 'youtube' || cardType === 'video' ) {
                var $player = $( "<div \>" ).attr( 'id', 'player' );
                modal.find( '.main-card > #player' ).remove();
                modal.find( '.main-card ').append( $player );
                onYouTubeIframeAPIReady();
              }
            });
          });
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
      if ( is_full_screen ) {
        modal.removeClass( 'full-screen' );
        stateMap.is_full_screen = false;
        // return modal columns to original state
        toggleRelatedColl();
      }

      modal.find( '.main-card' ).empty();
	    modal.find( '.related-cards' ).empty();

	    modal.find( '.main-card' ).append( $player );
	    modal.find( '.main-card' ).append( $mainCard );
	  });
  };

  //var data = JSON.parse( HubItemData );
  $(function() {
    initModule( $( "body" ) );
	  //$( ".slick-slider" ).slick();
	});

  return {
    initModule : initModule
  };
})( jQuery );
