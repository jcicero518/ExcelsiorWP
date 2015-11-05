(function( app ) {

  console.log( app, 'app from alerts' );
  var alerting = function( $timeout ) {

    var currentAlerts = [];
    var alertTypes = ["success", "info", "danger", "warning"];

    var addWarning = function( message ) {
      addAlert( "warning", message );
    };

    var addDanger = function( message ) {
      addAlert( "danger", message );
    };

    var addInfo = function( message ) {
      addAlert( "info", message );
    };

    var addSuccess = function( message ) {
      addAlert( "success", message );
    };

    var addAlert = function( type, message ) {
      var alert = {
        type: type,
        message: message
      };

      currentAlerts.push( alert );

      /*$timeout(function() {
        removeAlert( alert );
      }, 5000 );*/
    };

    var removeAlert = function(alert) {
      for (var i=0; i<currentAlerts.length; i++) {
        if (currentAlerts[i] === alert) {
          currentAlerts.splice(i, 1);
          break;
        }
      }
    };

    // public and convenience methods
    return {
      addWarning: addWarning,
      addDanger: addDanger,
      addInfo: addInfo,
      addSuccess: addSuccess,
      addAlert: addAlert,
      currentAlerts : currentAlerts,
      alertTypes : alertTypes,
      removeAlert : removeAlert
    };

  };

  app.factory( 'alerting', alerting );

}( angular.module( 'uwApp' )));
