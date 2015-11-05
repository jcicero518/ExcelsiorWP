(function( app ) {

  var basePath = cr_plugin_api.admurl;

  var alerts = function( alerting ) {
    return {
      restrict: 'AE',
      templateUrl: basePath + 'partials/alerts.html',
      scope: true,
      link: function( scope, el, attrs ) {
        scope.currentAlerts = alerting.currentAlerts;
      },
      controller: function( $scope ) {
        $scope.removeAlert = function(alert) {
          alerting.removeAlert(alert);
        };
      }
    };
  };

  app.directive( 'alerts', alerts );

})( angular.module( 'uwApp' ) );
