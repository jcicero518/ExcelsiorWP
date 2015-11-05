(function( app ) {

  var modalInstanceController = function( $scope, $modalInstance, streamService, items ) {
    $scope.items = items;
    $scope.itemTitle = items.title;
    $scope.curr_stream_id = items.current_stream_id;
    $scope.itemId = $scope.items.id;

    $scope.modal_full_screen = false;

    $scope.itemDetails = {};
    $scope.itemDetailsLoaded = false;

    $scope.relatedItems = {};
    $scope.relatedItemsLoaded = false;

    streamService.getStreamItemData( $scope.itemId ).success(function( data ) {
      $scope.itemDetails = data[0].HubItem.HubItemContent.data;
      $scope.itemDetailsLoaded = true;
    });

    streamService.getStreamItems( $scope.curr_stream_id ).success(function( data ) {
      $scope.relatedItems = data;
      $scope.relatedItemsLoaded = true;
    });

    $scope.itemType = $scope.itemDetails.type;

    $scope.selected = {
      item: $scope.items[0]
    };

    $scope.showRelated = function( ritem ) {
      console.log( ritem, 'clicked on ritem ');
      $scope.selectedRItem = ritem.HubItem.id;

      streamService.getStreamItemData( $scope.selectedRItem ).success(function( data ) {
        $scope.itemTitle = data[0].HubItem.title;
        $scope.itemDetails = data[0].HubItem.HubItemContent.data;

        $scope.itemDetailsLoaded = true;
      });
    };

    $scope.toggleEnlarge = function() {
      $scope.modal_full_screen = !$scope.modal_full_screen;
    };

    $scope.ok = function () {
      $modalInstance.close($scope.selected.item);
    };

    $scope.cancel = function () {
      $modalInstance.dismiss('cancel');
    };

  };

  app.controller( 'modalInstanceController', modalInstanceController );

})( angular.module( 'uwApp' ) );
