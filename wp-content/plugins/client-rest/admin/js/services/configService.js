(function( app ) {

  var config = function() {
    return {
      stream : {},
      streamLoaded : false,
      streamItems : 1,
      sharer : false,
      formButtons : {
        initDB: false,
        refresh: false
      }
    };
  };

  app.factory( 'config', config );

})( angular.module( 'uwApp'));
