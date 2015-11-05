(function( app ) {

  var dbService = function( $http ) {

    var basePath = cr_plugin_api.admurl;
    var classPath = basePath + 'uf-api/';

    //$http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';

    return {
      initTables: function() {
        return $http.get( classPath + 'initDB.php' );
      },
      addStreamRecord: function( streamID ) {
        return $http.get( classPath + 'services/addStream.php?name=stream&stream_id=' + streamID + '&stream_data=' );
      },
      addStream: function( streamID, streamData ) {
        return $http.get( classPath + 'services/addStream.php?name=stream&stream_id=' + streamID + '&stream_data=' + streamData );
      },
      postStream: function( streamID, streamData ) {
        return $http.post( classPath + 'services/postStream.php', { name: 'stream', stream_id: streamID, stream_data: streamData } );
      },
      postWidget: function( widgetID, streamTitle, streamID, widgetTitle, num_cards, link_text, purl, sharer, textnotes, widgetData ) {
        return $http.post( classPath + 'services/postWidget.php', {
          wid: widgetID,
          stitle: streamTitle,
          fSid: streamID,
          wtitle: widgetTitle,
          num_cards: num_cards,
          link_text: link_text,
          purl: purl,
          sharer: sharer,
          textnotes: textnotes,
          data: widgetData
        });
      },
      getWidgets: function() {
        return $http.get( classPath + 'services/getWidgets.php');
      }
    };
  };

  app.factory( 'dbService', dbService );

})( angular.module( 'uwApp' ) );
