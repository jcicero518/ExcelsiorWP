(function( app ) {

  var tabController = function( $scope ) {
    $scope.tabs = [
    {
      title:'Dynamic Title 1',
      content:'Dynamic content 1'
    },
    {
      title:'Dynamic Title 2',
      content:'Dynamic content 2',
      disabled: true
    }
  ];
  };

  app.controller( 'tabController', tabController );

})( angular.module( 'uwApp' ) );
