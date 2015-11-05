(function( app ) {

  var widgetList = function( dbService ) {
    return {
      templateUrl: cr_plugin_api.admurl + 'partials/widgetList.html',
      /*scope: {
        item: '='
      },*/
      restrict: 'EA',
      link: function( scope, el, attrs ) {
        scope.widgetPromise = dbService.getWidgets();
        scope.widgetData = {};
        scope.widgetPromise.success(function( data ) {
          console.log( data, 'promise data');
          scope.widgets = data;
        });
      },
      controller: function( $scope, $parse, dbService ) {
        //
      }
    };
  };

  var cardPreview = function() {
    return {
      restrict: 'EA',
      templateUrl: cr_plugin_api.admurl + 'partials/cardPreview.html',
      scope: {
        data: '='
      },
      link: function( scope, el, attrs ) {
        console.log( scope, 'preview scope');
      }
    };
  };

  app.directive( 'widgetList', widgetList );
  app.directive( 'cardPreview', cardPreview );

})( angular.module( 'uwApp' ) );
