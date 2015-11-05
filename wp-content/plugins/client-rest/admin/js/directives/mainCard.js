(function( app ) {

  var mainCard = function() {
    return {
      templateUrl: cr_plugin_api.admurl + 'partials/mainCard.html',
      scope: {
        item: '='
      },
      restrict: 'EA',
      link: function( scope, el, attrs ) {
        console.log( scope, 'maincard scope' );
      }
    };
  };

  app.directive( 'mainCard', mainCard );

})( angular.module( 'uwApp' ) );
