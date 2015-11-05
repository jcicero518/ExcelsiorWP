(function( app ) {

  var youtubeEmbedUrl = function( $sce ) {
    return function( videoId ) {
      return $sce.trustAsResourceUrl( 'http://www.youtube.com/embed/' + videoId );
    };
  };

  app.filter( 'youtubeEmbedUrl', youtubeEmbedUrl );

})( angular.module( 'uwApp' ) );
