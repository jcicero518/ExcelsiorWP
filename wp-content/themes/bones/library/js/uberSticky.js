var UberSticky = (function( $ ) {
  var 
    configMap = {
      scroll_interval : 10
    },
    stateMap = {
      $container : undefined,
      $header : undefined,
    },
    jqueryMap = {},
    
    stickyHeader, setJqueryMap, initModule;
    
  stickyHeader = function() {
    
  };
  
  setJqueryMap = function() {
    var $container = stateMap.$container;
    
    jqueryMap = {
      $container : $container,
      $header : $container.find( '.header' )
    };
  };
  
  initModule = function( $container ) {
    stateMap.$container = $container;
    setJqueryMap();
  };
  
  return { initModule : initModule };
  
})( jQuery );