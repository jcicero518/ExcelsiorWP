/*global $, cr_ajax_obj, cr_plugin_api */
(function( $ ) {
  var app = angular.module( 'uwApp' );
  console.log( app, 'app' );
  app.factory( 'streamService', function( $http ) {

    var wpAjaxUrl = cr_ajax_obj.ajax_url;
    var wpNonce = cr_ajax_obj.nonce;
    var wpAction = 'crest_get_streams';

    var basePath = cr_plugin_api.admurl;
    var servicePath = basePath + 'uf-api/services/';

    return {
      getStreams: function() {
        return $http.get( servicePath + 'getStreams.php' );
      },
      getRemoteStreams: function() {
        return $http.get( servicePath + 'getRemoteStreams.php' );
      },
      getStreamItems: function( streamid ) {
        return $http.get( servicePath + 'getRemoteStreams.php?method=GetHubItems&streamid=' + streamid );
      },
      getStreamItemData: function( itemid ) {
        return $http.get( servicePath + 'getRemoteItemData.php?method=GetHubItemData&itemid=' + itemid );
      }
    };
  });

  app.factory( 'dbService', function( $http ) {
    var basePath = cr_plugin_api.admurl;
    var classPath = basePath + 'uf-api/';

    return {
      initTables: function() {
        return $http.get( classPath + 'initDB.php' );
      }
    };
  });

  app.filter( 'youtubeEmbedUrl', function( $sce ) {
    return function( videoId ) {
      return $sce.trustAsResourceUrl( 'http://www.youtube.com/embed/' + videoId );
    };
  });

  /*app.factory( 'dateService', function() {
    var date = new Date();
    var format =

    return {
      getRelativeDate: function( date ) {

      }
    }
  });*/

  app.controller( 'ConfigController', function( $scope, $timeout, streamService, dbService ) {
    console.log($scope, 'ctrl scope');

    $scope.cardData = {
      maxWidth: 250,
      numTiles: 1,
      number: 1,
      streamLoaded: false,
      streamData: {}, // JSON from GetHubStreams
      streamItems:  {} // JSON from GetHubItems
    };

    //$scope.configCtrl.stream = 0;
    $scope.sharer = 'Yes';
    $scope.streamID = 0;
    $scope.number = 1;

    $scope.maxWidth = 250;
    $scope.numTiles = 1;

    $scope.streamLoaded = false;
    $scope.streamItemDataLoaded = false;
    $scope.streamSelected = undefined;
    $scope.streamData = {}; // JSON from GetHubStreams
    $scope.streamItems = {}; // JSON from GetHubItems
    $scope.streamItemData = {}; // JSON from GetHubItemData

    this.initDBTables = function() {
      dbService.initTables().success(function( data ) {
        console.log( data );
      });
    };

    $scope.updateNumber = function( num ) {
      if ( angular.isNumber( num ) ) {
        $scope.number = num;
        $scope.maxWidth = num * 250;
      }
    };

    $scope.changeStream = function( streamid ) {
      console.log(streamid, 'passed streamid');
      streamService.getStreamItems( streamid ).success(function( data ) {
        //$scope.streamItems = data;
        $scope.streamData2 = data;
        $scope.streamLoaded = true;
      });
    };

    $scope.update = function() {
      $scope.newStreamID = $scope.streamSelected;
      $scope.changeStream( $scope.newStreamID );
    };

    $scope.getStreamItemData = function( itemid ) {
      streamService.getStreamItemData( itemid ).success(function( data ) {
        $scope.streamItemData = data;
        $scope.streamItemDataLoaded = true;
      });
    };

    var that = this;

    // initially loads streams from DB
    /*streamService.getStreams().success(function( data ) {
      console.log( data, 'getStreams');
      that.streams = data;
      $scope.streamData = data;
      $scope.streamLoaded = true;
    });*/

    $scope.getRemoteStreams = function() {
      streamService.getRemoteStreams().success(function( data ) {
        that.remoteStreams = data;
        $scope.streamData = data;
        $scope.streamSelected = $scope.streamData[0].HubStream.id;
        $scope.streamLoaded = true;
        console.log( data, 'getRemoteStreams');
        //console.log( data, 'remote' );
        // var log = [];
        // angular.forEach(temp1, function(value, key) { this.push(key + ':' + value); }, log);

      });
    };

    $scope.getRemoteStreams();
    console.log('Controller scope', $scope);

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

        scope.$watch( 'streamSelected', function( oldVal, newVal ) {
          console.log( oldVal, 'streamSelected watch old');
          console.log( newVal, 'streamSelected watch new');
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

        $scope.showOverlay = function( item ) {
            console.log( item, 'hovered item');
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
            size: 'lg',
            resolve: {
              items: function() {
                //return $scope.items;
                //hubItem.current_stream_id = $scope.configCtrl.stream;
                hubItem.current_stream_id = $scope.streamSelected;

                return hubItem;
              }
            }
          });
          console.log( modalInstance, 'modal open promise');
        };
      }
    };
  });

  app.directive( 'animateOnChange', function( $timeout, $animate ) {
    return {
      link: function( scope, el, attrs ) {
        console.log( attrs, 'anum watch attrs');
        scope.$watch( 'animateOnChange', function( ov, nv ) {
          console.log( this, 'watch animate on change');
          console.log( $animate, '$animate');
          if ( 1 === 1 ) {
            el.addClass( 'changed' );
            $timeout(function() {
              el.removeClass( 'changed' );
            }, 1000 );
          }
        });
      }
    };
  });

  app.controller( 'modalInstanceController', function( $scope, $modalInstance, $timeout, streamService, items ) {

    $scope.items = items;
    $scope.itemTitle = items.title;
    $scope.curr_stream_id = items.current_stream_id;
    $scope.itemId = $scope.items.id;

    $scope.modal_full_screen = false;

    $scope.itemDetails = {};
    $scope.itemDetailsLoaded = false;

    $scope.relatedItems = {};
    $scope.relatedItemsLoaded = false;

    streamService.getStreamItemData( $scope.itemId ).success(function( data ) {
      $scope.itemDetails = data[0].HubItem.HubItemContent.data;
      $scope.itemDetailsLoaded = true;
    });

    streamService.getStreamItems( $scope.curr_stream_id ).success(function( data ) {
      $scope.relatedItems = data;
      $scope.relatedItemsLoaded = true;
    });

    $scope.itemType = $scope.itemDetails.type;

    $scope.selected = {
      item: $scope.items[0]
    };

    $scope.showRelated = function( ritem ) {
      console.log( ritem, 'clicked on ritem ');
      $scope.selectedRItem = ritem.HubItem.id;

      streamService.getStreamItemData( $scope.selectedRItem ).success(function( data ) {
        $timeout(function() {
          $scope.itemTitle = data[0].HubItem.title;
          $scope.itemDetails = data[0].HubItem.HubItemContent.data;

          $scope.itemDetailsLoaded = true;
        }, 1000 );

      });
    };

    $scope.toggleEnlarge = function() {
      $scope.modal_full_screen = !$scope.modal_full_screen;
    };

    $scope.ok = function () {
      $modalInstance.close($scope.selected.item);
    };

    $scope.cancel = function () {
      $modalInstance.dismiss('cancel');
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
            el.parents( '.modal.ng-isolate-scope' ).addClass( 'full-screen' );
          } else {
            el.parents( '.modal.ng-isolate-scope' ).removeClass( 'full-screen' );
          }
          console.log( this, 'fs watcher');
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

})( jQuery );
