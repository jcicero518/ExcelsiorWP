(function( app ) {

  var user = function() {
    return {
      username: ''
    };
  };

  app.factory( 'user', user );

})( angular.module( 'uwApp'));
