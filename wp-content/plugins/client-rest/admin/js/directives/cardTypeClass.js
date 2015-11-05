(function() {

  var app = angular.module( 'uwApp' );

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
})();
