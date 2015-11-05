(function( app ) {

  var streamService = function( $http ) {
    var wpAjaxUrl = cr_ajax_obj.ajax_url;
    var wpNonce = cr_ajax_obj.nonce;
    var wpAction = 'crest_get_streams';

    var basePath = cr_plugin_api.admurl;
    var servicePath = basePath + 'uf-api/services/';

    return {
      getStreams: function() {
        return $http.get( servicePath + 'getStreams.php' );
      },
      getRemoteStreams: function() {
        return $http.get( servicePath + 'getRemoteStreams.php' );
      },
      getStreamItems: function( streamid ) {
        return $http.get( servicePath + 'getRemoteStreams.php?method=GetHubItems&streamid=' + streamid );
      },
      getStreamItemData: function( itemid ) {
        return $http.get( servicePath + 'getRemoteItemData.php?method=GetHubItemData&itemid=' + itemid );
      }
    };
  };

  app.factory( 'streamService', streamService );

}( angular.module( 'uwApp')));
