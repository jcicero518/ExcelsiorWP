(function( app ) {
  'use strict';

  var basePath = cr_plugin_api.admurl;
  var classPath = basePath + 'uf-api/';

  var checkUnique = function( $http, $timeout, $q ) {
    var checking = null;

    return {
      require: 'ngModel',
      link: function( scope, el, attrs, ctrl ) {
        console.log( attrs, 'check');

        scope.$watch( attrs.ngModel, function( newVal ) {
          if (!checking) {
            checking = $timeout(function() {
              $http.get( classPath + 'check/checkUnique.php?field=' + attrs.checkUnique + '&fval=' + newVal )
                .success(function( data, status, headers, cfg ) {
                  var isValid = !data;
                  console.log( isValid, 'isValid');
                  ctrl.$setValidity( 'notUnique', isValid );
                  checking = null;
                })
                .error(function( data, status, headers, cfg ) {
                  checking = null;
                });
              }, 500 );
            }
        });
      }
    };
  };

  app.directive( 'checkUnique', checkUnique );

})( angular.module( 'uwApp' ) );
