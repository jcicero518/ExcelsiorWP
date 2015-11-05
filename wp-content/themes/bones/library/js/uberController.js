(function() {
  var app = angular.module('myApp', ['ngSanitize', 'ngRoute', 'ngAnimate', 'ui.bootstrap']);
  
  app.run( ['$rootScope', '$http', '$sce', function( $rootScope, $http, $sce ) {
    $rootScope.$on('$routeChangeSuccess', function (event, current, previous) {
		  if ( current.$$route.title !== '' ) {
			  document.querySelector('title').innerHTML = $sce.trustAsHtml(current.$$route.title);
		  }
		  if ( current.$$route.controller === 'streamController' ) {
  		  current.params.apiMethod = 'GetHubStreams'
  		}
  		if ( current.pathParams.hasOwnProperty( 'pagename' ) ) {
    		if ( current.pathParams.pagename === 'gethubstreams' ) {
    		  current.params.apiMethod = 'GetHubStreams';
    		  
    		}
      }
	  });
    
    $rootScope.api = AppAPI.url;
    $rootScope.siteurl = BlogInfo.uber_site_url;
    $rootScope.library = BlogInfo.uber_site_library;
    $rootScope.partials = BlogInfo.uber_site_partials;
    //$rootScope.json = JSON.parse( json );
    //$rootScope.methods = JSON.parse( methods );
    
    /*
	    Carousel
	  */
	  $rootScope.carouselID = 'carousel-slider';
    $rootScope.controlButtonPrev = 'Prev';
    $rootScope.controlButtonNext = 'Next';
    
  }]);
  
  app.config( ['$httpProvider', '$routeProvider', '$locationProvider', function( $httpProvider, $routeProvider, $locationProvider ) {
    $locationProvider.html5Mode(true);
    
    $routeProvider
    .when('/', {
      templateUrl: BlogInfo.uber_site_partials + 'main.html',
      controller: 'uberController',
      title: 'Uberflip API Home'
    })
    .when('/:pagename', {
      templateUrl: BlogInfo.uber_site_partials + 'page.html',
      controller: 'Page',
      title: 'Page'
    })
    .otherwise({
      redirectTo: '/'
    });
   
  }]);
  
  app.controller( 'ModalCtrl', function( $scope, $q, $http, $timeout ) {
    $scope.getRelated = function( item ) {
      console.log( item );
    };
    
  });
  
  app.controller( 'SlickCtrl', function( $scope, $q, $http, $timeout ) {
    
  });
  
  app.controller( 'Page', function( $scope, $q, $http, $routeParams, MethodAPI ) {
    
    if ( $routeParams.hasOwnProperty( 'pagename' ) ) {
      if ( $routeParams.pagename === 'gethubstreams' ) {
        $scope.apiMethod = 'GetHubStreams';
      } else if ( $routeParams.pagename === 'gethubitems' ) {
        $scope.apiMethod = 'GetHubItems';
      }
    }
    
    if ( $routeParams.hasOwnProperty( 'pagename' ) ) {
      $http.get( $scope.api + 'posts?type[]=page&filter[pagename]=' + $routeParams.pagename ).success( function(res) {
        // returns JSON array object.. of which there is only one, so we are not going to do ng-repeat
        $scope.post = res[0];
      });
    }
  });
  
  app.directive( 'uberData', function() {
    return {
      restrict: 'EA',
      templateUrl: BlogInfo.uber_site_partials + 'directive_page.html',
      controller: function( $scope, $http, $routeParams, $q, MethodAPI ) {
        console.log( $scope, 'uberData ctrl scope' );
        var apiPromise = MethodAPI.getUberValues( $scope.apiMethod );
        apiPromise.then(
          function( data ) {
            $scope.data = data;
          }
        );
      },
      link: function( scope, elem, attrs ) {
        console.log( scope, 'link scope' );
        
      }
    };
  });
  
  app.directive( 'whoWeAre', function() {
    return {
      restrict: 'EAC',
      replace: true,
      templateUrl: BlogInfo.uber_site_partials + 'parallax-group1.html',
      controller: function( $scope, $http ) {
        $http.get( $scope.api + 'posts?type[]=post&filter[category_name]=who-we-are&filter[orderby]=title&filter[order]=ASC' )
          .success( function( res ) {
            $scope.section_title = 'Who We Are';
            $scope.post_www = res;
          }
        );
      }
    };
  });
  
  app.directive( 'whatWeDo', function() {
    return {
      restrict: 'EAC',
      replace: true,
      templateUrl: BlogInfo.uber_site_partials + 'parallax-group2.html',
      controller: function( $scope, $http ) {
        $scope.containerClass = 'animate-fade';
        $http.get( $scope.api + 'posts?type[]=post&filter[category_name]=what-we-do&filter[orderby]=title&filter[order]=ASC' )
          .success( function( res ) {
            $scope.containerClass = 'animate-fade ng-enter';
            $scope.post_wwd = res[0];
          }
        );
      }
    };
  });
  
  app.directive( 'servicesCenters', function() {
    return {
      restrict: 'EAC',
      replace: true,
      templateUrl: BlogInfo.uber_site_partials + 'parallax-group3.html',
      controller: function( $scope, $http ) {
        $scope.containerClass = 'animate-fade';
        $http.get( $scope.api + 'posts?type[]=post&filter[category_name]=services-centers&filter[orderby]=title&filter[order]=ASC' )
          .success( function( res ) {
            $scope.containerClass = 'animate-fade ng-enter';
            $scope.post_tl = res[0];
          }
        );
      }
    };
  });
  
  app.directive( 'whyExcelsior', function() {
    return {
      restrict: 'EAC',
      replace: true,
      templateUrl: BlogInfo.uber_site_partials + 'parallax-group4.html',
      controller: function( $scope, $http ) {
        $scope.containerClass = 'animate-fade';
        $http.get( $scope.api + 'posts?type[]=post&filter[category_name]=why-excelsior&filter[orderby]=title&filter[order]=ASC' )
          .success( function( res ) {
            $scope.containerClass = 'animate-fade ng-enter';
            $scope.post_we = res[0];
          }
        );
      }
    };
  });
  
  app.directive( 'peerPerspectives', function() {
    return {
      restrict: 'EAC',
      replace: true,
      templateUrl: BlogInfo.uber_site_partials + 'parallax-group5.html',
      controller: function( $scope, $http ) {
        $scope.containerClass = 'animate-fade';
        $http.get( $scope.api + 'posts?type[]=post&filter[category_name]=peer-perspectives&filter[orderby]=title&filter[order]=ASC' )
          .success( function( res ) {
            $scope.containerClass = 'animate-fade ng-enter';
            $scope.post_pp = res[0];
          }
        );
      }
    };
  });
  
  app.directive( 'engagementHub', function() {
    return {
      restrict: 'EAC',
      replace: true,
      templateUrl: BlogInfo.uber_site_partials + 'parallax-group6.html',
      controller: function( $scope, $http ) {
        $scope.containerClass = 'animate-fade';
        $http.get( $scope.api + 'posts?type[]=post&filter[category_name]=engagement-hub&filter[orderby]=title&filter[order]=ASC' )
          .success( function( res ) {
            $scope.containerClass = 'animate-fade ng-enter';
            $scope.post_eh = res[0];
          }
        );
      }
    };
  });
  
  app.directive( 'uberSocial', function() {
	  return {
		  restrict: 'EA',
		  templateUrl: BlogInfo.uber_site_partials + 'slick-carousel.html',
		  controller: function( $scope, $http, $timeout, MethodAPI ) {
			  var apiMethod = 'GetHubItems';
			  var apiPromise = MethodAPI.getUberValues( apiMethod );
				apiPromise.then(
          function( data ) {
            $scope.sectionTitle = 'Engagement Hub';
            $scope.slick_data = data;
            console.log( data );
            /*$timeout(function() {
	            jQuery( ".slick-slider" ).slick({
		            dots: true,
		            infinite: false,
		            speed: 1200,
		            lazyload: 'ondemand',
		            slidesToShow: 3,
		            slidesToScroll: 3,
		            responsive: [
			            
			            {
				            breakpoint: 1024,
				            settings: {
					            slidesToShow: 3,
					            slidesToScroll: 3,
					            infinite: true,
					            dots: true
					          }
					        },
					        {
						        breakpoint: 680,
						        settings: {
							        slidesToShow: 2,
							        slidesToScroll: 2
							      }
							    },
							    {
								    breakpoint: 480,
								    settings: {
									    slidesToShow: 1,
									    slidesToScroll: 1
									  }
									}
								]
		            
		          });
		        });*/
          }
        );
      }
    };
  });
  
  app.directive( 'uberCarousel', function() {
		return {
			restrict: 'EAC',
			replace: true,
			templateUrl: BlogInfo.uber_site_partials + 'carousel.html',
			controller: function( $scope, $http, preloader ) {
        $scope.containerClass = 'animate-fade';
        // I keep track of the state of the loading images.
        $scope.isLoading = true;
        $scope.isSuccessful = false;
        $scope.percentLoaded = 0;
        
        $scope.imageLocations = [];
        
        $http({
          method: 'GET',
          url: $scope.api + 'posts?type[]=post&filter[category_name]=slider&filter[orderby]=title&filter[order]=ASC' // from $rootScope
        })
        .success( function( data, status, headers, config ) {
          //$scope.spinElem.stop();
          $scope.containerClass = 'animate-fade ng-enter';
          $scope.postdata = data;
          $scope.postdata[0].defaultSelected = true;
          $scope.numSlides = data.length;
          
          $scope.isSuccessful = true;
          $scope.isLoading = false;
          
          for (var i = 0; i < data.length; i++) {
            $scope.imageLocations.push( $scope.postdata[i].featured_image.source );
          }
          
          var carousel = document.getElementById($scope.carouselID);
                  jQuery(carousel).carousel({
                    interval: 4000
                  });
        });
      }
    };
  });
  
  app.directive( "indicators", function() {
    return {
      restrict: 'E',
      replace: true,
      scope: false,
      templateUrl: BlogInfo.uber_site_partials + 'indicators.html',
      link: function( scope, elem, attrs ) {
        scope.wrapperClass = 'carousel-indicators';
      }
    };
  });
  
  /*app.directive( 'uberCarousel', function() {
    return {
      restrict: 'EAC',
    };
  });*/
  
  app.controller( 'streamController', function( $scope, $http, $routeParams ) {
    if ( $routeParams.hasOwnProperty( 'apiMethod' ) ) {
      console.log( 'has own property' );
      console.log( $routeParams.apiMethod );
    }
  });
  
  app.controller( 'dataController', function( $scope, $http, $routeParams ) {
    $http({
        url: '/Uberflip/includes/getMethodItem.php',
        method: 'GET',
        params: { 
          method : $scope.apiMethod,
          //itemId : itemID 
          itemId: 11863564
        }
      })
      .success(function( data ) {
          $scope.data = data;
          $scope.data_length = data.length;
          
          //$scope.mainData = data.HubItem;
          //$scope.domainData = data.HubItem.Domain;
          //$scope.hubContentData = data.HubItem.HubItemContent.data;
          console.log( data, 'success' );
      })
      .error(function( error ) {
        console.log( error, 'error' );
      });
  });
  
  app.controller( 'uberController', function( $scope, $http, $routeParams ) {
    /*if ($routeParams.hasOwnProperty('ID')) {
      $http.get('wp-json/posts/' + $routeParams.ID).success(function(res) {
        $scope.post = res;
      });
    } else {
      console.log($routeParams);
    }*/
    
    $scope.changeMethod = function( ) {
      var method = this.apiMethod;
      $scope.method = method;
      $http({
        url: '/Uberflip/includes/getMethods.php',
        method: 'GET',
        params: { method : method }
      })
      .success(function( data ) {
          $scope.data = data;
          console.log( data, 'success' );
      })
      .error(function( error ) {
        console.log( error, 'error' );
      });
    };
    
    $scope.apiOpenItem = function( itemID ) {
       $scope.apiMethod = 'GetHubItemData';
       
       $http({
        url: '/Uberflip/includes/getMethodItem.php',
        method: 'GET',
        params: { 
          method : $scope.apiMethod,
          itemId : itemID 
        }
      })
      .success(function( data ) {
          $scope.data = data;
          $scope.data_length = data.length;
          
          //$scope.mainData = data.HubItem;
          //$scope.domainData = data.HubItem.Domain;
          //$scope.hubContentData = data.HubItem.HubItemContent.data;
          console.log( data, 'success' );
      })
      .error(function( error ) {
        console.log( error, 'error' );
      });
    };
    
  });
  
  app.factory( 'MethodAPI', ['$http', '$q', '$timeout', '$log', function( $http, $q, $timeout, $log ) {
    
    var values = {
      url: '/Uberflip/includes/getMethods.php',
      http_method: 'GET'
    };
    
    var methodInstance = {};
    
    methodInstance.getUberValues = function( apiMethod ) {
      return $http({
        url: values.url,
        method: values.http_method,
        params: {
          method: apiMethod
        }
      })
      .then( 
        function( response ) {
          var data = response.data;
          return data;
        },
        function( error ) {
          throw httpError.status;
        }
      );
    };
    
    return methodInstance;
    
  }]);
  
  app.factory(
      "preloader",
      function( $q, $rootScope ) {

          // I manage the preloading of image objects. Accepts an array of image URLs.
          function Preloader( imageLocations ) {

              // I am the image SRC values to preload.
              this.imageLocations = imageLocations;

              // As the images load, we'll need to keep track of the load/error
              // counts when announing the progress on the loading.
              this.imageCount = this.imageLocations.length;
              this.loadCount = 0;
              this.errorCount = 0;

              // I am the possible states that the preloader can be in.
              this.states = {
                  PENDING: 1,
                  LOADING: 2,
                  RESOLVED: 3,
                  REJECTED: 4
              };

              // I keep track of the current state of the preloader.
              this.state = this.states.PENDING;

              // When loading the images, a promise will be returned to indicate
              // when the loading has completed (and / or progressed).
              this.deferred = $q.defer();
              this.promise = this.deferred.promise;

          }


          // ---
          // STATIC METHODS.
          // ---


          // I reload the given images [Array] and return a promise. The promise
          // will be resolved with the array of image locations.
          Preloader.preloadImages = function( imageLocations ) {

              var preloader = new Preloader( imageLocations );

              return( preloader.load() );

          };


          // ---
          // INSTANCE METHODS.
          // ---


          Preloader.prototype = {

              // Best practice for "instnceof" operator.
              constructor: Preloader,


              // ---
              // PUBLIC METHODS.
              // ---


              // I determine if the preloader has started loading images yet.
              isInitiated: function isInitiated() {

                  return( this.state !== this.states.PENDING );

              },


              // I determine if the preloader has failed to load all of the images.
              isRejected: function isRejected() {

                  return( this.state === this.states.REJECTED );

              },


              // I determine if the preloader has successfully loaded all of the images.
              isResolved: function isResolved() {

                  return( this.state === this.states.RESOLVED );

              },


              // I initiate the preload of the images. Returns a promise.
              load: function load() {

                  // If the images are already loading, return the existing promise.
                  if ( this.isInitiated() ) {

                      return( this.promise );

                  }

                  this.state = this.states.LOADING;

                  for ( var i = 0 ; i < this.imageCount ; i++ ) {

                      this.loadImageLocation( this.imageLocations[ i ] );

                  }

                  // Return the deferred promise for the load event.
                  return( this.promise );

              },


              // ---
              // PRIVATE METHODS.
              // ---


              // I handle the load-failure of the given image location.
              handleImageError: function handleImageError( imageLocation ) {

                  this.errorCount++;

                  // If the preload action has already failed, ignore further action.
                  if ( this.isRejected() ) {

                      return;

                  }

                  this.state = this.states.REJECTED;

                  this.deferred.reject( imageLocation );

              },


              // I handle the load-success of the given image location.
              handleImageLoad: function handleImageLoad( imageLocation ) {

                  this.loadCount++;

                  // If the preload action has already failed, ignore further action.
                  if ( this.isRejected() ) {

                      return;

                  }

                  // Notify the progress of the overall deferred. This is different
                  // than Resolving the deferred - you can call notify many times
                  // before the ultimate resolution (or rejection) of the deferred.
                  this.deferred.notify({
                      percent: Math.ceil( this.loadCount / this.imageCount * 100 ),
                      imageLocation: imageLocation
                  });

                  // If all of the images have loaded, we can resolve the deferred
                  // value that we returned to the calling context.
                  if ( this.loadCount === this.imageCount ) {

                      this.state = this.states.RESOLVED;

                      this.deferred.resolve( this.imageLocations );

                  }

              },


              // I load the given image location and then wire the load / error
              // events back into the preloader instance.
              // --
              // NOTE: The load/error events trigger a $digest.
              loadImageLocation: function loadImageLocation( imageLocation ) {

                  var preloader = this;

                  // When it comes to creating the image object, it is critical that
                  // we bind the event handlers BEFORE we actually set the image
                  // source. Failure to do so will prevent the events from proper
                  // triggering in some browsers.
                  var image = $( new Image() )
                      .load(
                          function( event ) {

                              // Since the load event is asynchronous, we have to
                              // tell AngularJS that something changed.
                              $rootScope.$apply(
                                  function() {

                                      preloader.handleImageLoad( event.target.src );

                                      // Clean up object reference to help with the
                                      // garbage collection in the closure.
                                      preloader = image = event = null;

                                  }
                              );

                          }
                      )
                      .error(
                          function( event ) {

                              // Since the load event is asynchronous, we have to
                              // tell AngularJS that something changed.
                              $rootScope.$apply(
                                  function() {

                                      preloader.handleImageError( event.target.src );

                                      // Clean up object reference to help with the
                                      // garbage collection in the closure.
                                      preloader = image = event = null;

                                  }
                              );

                          }
                      )
                      .prop( "src", imageLocation )
                  ;

              }

          };


          // Return the factory instance.
          return( Preloader );

      }
  );
  
})();
