/*global $, cr_ajax_obj, cr_plugin_api */
(function( app ) {
  //var app = angular.module( 'uwApp' );
  app.controller( 'ConfigController', function( $scope, $timeout, streamService, dbService, user, alerting ) {
    console.log($scope, 'ctrl scope');
    var model = this;

    /*
      Alerts
    */
    model.alertTypes = alerting.alertTypes;
    model.alertType = model.alertTypes[0]; // success
    model.alertMessage = '';

    model.createAlert = function( msg ) {
      model.alertMessage = msg;
      alerting.addAlert( model.alertTypes[1], msg );
    };

    /*
      Test Alert Button
    */
    $scope.testAlert = function() {
      console.log( model.alertType,  'alertType');
      console.log( model.alertTypes, 'alertTypes');
      console.log( alerting, 'alerting');
      alerting.addAlert( model.alertTypes[1], 'Form submitted');
    };

    /*
      Config Form Model Defaults
    */
    model.number = 1;
    model.linktext = 'More';
    model.sharer = 'Yes';
    model.textnotes = '';

    /*
      Slider Defaults
    */
    $scope.maxWidth = 250;
    $scope.numTiles = 1;

    /*
      Stream Model Defaults
    */
    $scope.streamLoaded = false;
    $scope.streamItemDataLoaded = false;
    $scope.streamListData = {}; // JSON from GetHubStreams
    $scope.streamItems = {}; // JSON from GetHubItems
    $scope.streamItemData = {}; // JSON from GetHubItemData

    /*
      Widget Model Defaults
    */
    $scope.widgets = {};

    /*
      Debug Buttons
    */
    $scope.formButtons = {
      initDB: true,
      refresh: false
    };

    /*
      Initialize DB tables. Runs the initDB.php
      file that drops tables and re-creates them.
    */
    this.initDBTables = function() {
      dbService.initTables().success(function( data ) {
        console.log( data );
      });
    };

    /*
      Refresh widget model on click - reloads
      the widget list on the dashboard
    */
    $scope.refreshTabs = function() {
        $timeout(function() {
          $scope.widgetPromise = dbService.getWidgets();
          $scope.widgetPromise.success(function( data ) {
            $scope.widgets = data;
          });
        }, 1000 );
    };

    /*
      Refresh slider number model, sets
      max width of slider and updates number
      of cards to display
    */
    $scope.updateNumber = function( num ) {
      if ( angular.isNumber( num ) ) {
        $scope.number = num;
        $scope.maxWidth = num * 250;
      }
    };

    /*
      Retrieves JSON of available GetHubItems to
      populate the streamItems model given a stream ID
      Used to populate "cards" on click.
      @scope.streamItems
      @model.streamItems
      @@ streamLoaded = true
    */
    $scope.changeStream = function( streamid ) {
      streamService.getStreamItems( streamid ).success(function( data ) {
        $scope.streamItems = data;
        model.streamItems = data;
        $scope.streamLoaded = true;
      });
    };

    /*
      Retrieves JSON of available GetHubItemData to
      populate the streamItemData model given an item ID
      @scope.streamItemData
      @@ streamItemDataLoaded = true
    */
    $scope.getStreamItemData = function( itemid ) {
      streamService.getStreamItemData( itemid ).success(function( data ) {
        $scope.streamItemData = data;
        $scope.streamItemDataLoaded = true;
      });
    };

    /*
      Run getStreams from streamService to initially
      populate the streams model / streamData
      @model.streams
      @scope.streamData
    */
    streamService.getStreams().success(function( data ) {
      model.streams = data;
      $scope.streamData = data;
    });

    /*
      Retrieve list of available streams from
      the streamService and populates the
      streamData model.
      @ model.remoteStreams
      @ $scope.streamData
    */
    $scope.getRemoteStreams = function() {
      streamService.getRemoteStreams().success(function( data ) {
        model.remoteStreams = data;
        $scope.streamData = data;
      });
    };

    /*
      Config model form submission.
      POST model attributes to dbService methods
      postStream & postWidget.
      Update formSubmitted state
    */
    model.submit = function() {
      $scope.formSubmitted = true;

      // Create alert
      model.createAlert( 'Success! Your new widget\'s embed code is below' );

      // pass stream ID as model.stream
      dbService.postStream( model.stream, model.streamItems )
        .success(function( data ) {
          // Success. Do something here.
        })
        .error(function( error ) {
          console.log( error, 'error' );
        });
      // pass number of model parameters
      dbService.postWidget(
        model.stream, 'Stream Title',
        model.stream, model.title,
        model.number, model.linktext,
        model.url, model.sharer,
        model.textnotes, model.streamItems )

        .success(function( data ) {
          // Success, do something with data.
        })
        .error(function( error ) {
          console.log( error, 'error' );
        });
    };

    // Call func to populate remoteStreams & streamData
    $scope.getRemoteStreams();

  });

  /*
    Handles click event on code embed textarea
  */
  app.directive( 'selectCopy', function() {
    return {
      restrict: 'AE',
      link: function( scope, el, attrs, ctrl ) {
        el.on( 'click', function() {
          this.select();
        });
      }
    };
  });

  app.directive( 'refreshState', function() {
    return {
      link: function( scope, el, attrs, ctrl ) {
        console.log( el, 'el');
        el.button( "loading...");
      }
    };
  });

  app.directive( 'refreshLoader', function() {
    return {
      restrict: 'AE',
      template: '<div class="uf-widget-ajax-loader"></div>',
      link: function( scope, el, attrs, ctrl ) {
        console.log( el, 'refresh el');
      }
    };
  });

  app.directive( 'ufWidgetCard', function() {
    return {
      restrict: 'E',
      /*scope: {
        cardData: '='
      },*/
      scope: true,
      templateUrl: cr_plugin_api.admurl + 'partials/ufWidgetCard.html',
      link: function(scope, el, attrs, ctrl) {
        var
          slider = el.find('.slick-slider'),
          loader = el.find('.uf-widget-ajax-loader'),
          cOverlay = el.find('.link_overlay');

        slider.on( 'init', function( evt, slick ) {
          slider.find( '.uber-card' ).addClass( 'in' );
          loader.hide();
        });

        slider.on( 'unslick', function( evt, slick ) {
          slider.find( '.uber-card' ).removeClass( 'in' );
          loader.show();
        });

        scope.$watch( 'model.stream', function( oldVal, newVal ) {
          if ( oldVal !== undefined ) {
            scope.initSlider( slider );
          }
        });

        console.log( scope, 'attr scope' );
      },
      controller: function($scope, $timeout, $modal) {
        $scope.initSlider = function( slider ) {
          if ( slider.hasClass( 'slick-initialized' ) ) {
            slider.slick( 'unslick' );
            slider.addClass( 'slick-slider' );
          }
          setTimeout(function() {
            slider.slick({
              slidesToShow : $scope.number,
              slidesToScroll : $scope.number
            });
          }, 1500 );
        };

        $scope.animationsEnabled = true;
        $scope.items = ['item1', 'item2', 'item3'];

        $scope.hubItem = {};

        $scope.openModal = function( hubItem ) {
          console.log( hubItem, 'passed item');
          var modalInstance = $modal.open({
            animation: $scope.animationsEnabled,
            templateUrl: cr_plugin_api.admurl + 'partials/ufWidgetModal.html',
            controller: 'modalInstanceController',
            windowClass: 'uber-card-modal',
            size: 'lg',
            resolve: {
              items: function() {
                //return $scope.items;
                hubItem.current_stream_id = $scope.model.stream;
                return hubItem;
              }
            }
          });
        };
      }
    };
  });


  app.directive( 'cardTypeClass', function() {
    return {
      restrict: 'A',
      link: function( scope, el, attrs ) {
        var type = attrs.cardTypeClass || '';
        if ( type.length ) {
          el.addClass( type );
        }
      }
    };
  });

  app.directive( 'modalSize', function() {
    return {
      restrict: 'A',
      link: function( scope, el, attrs ) {
        scope.$watch( 'modal_full_screen', function( val ) {
          if ( val ) {
            el.parents( '.modal.uber-card-modal' ).addClass( 'full-screen' );
          } else {
            el.parents( '.modal.uber-card-modal' ).removeClass( 'full-screen' );
          }
        });
      }
    };
  });
  /*app.directive( 'alerting', function() {
    return {
      restrict: 'AE',
      scope: true,
      link: function( scope, el, attrs ) {
        scope.currentAlert = alerting.currentAlerts;
      }
    }
  })*/

  app.directive( 'checkLoaded', function() {
    return {
      restrict: 'A',
      link: function( scope, el, attrs ) {
        //
      }
    };
  });

})( angular.module( 'uwApp' ) );
