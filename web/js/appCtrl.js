angular.module('appCtrl', []).controller('ctrl', function($scope, dataService, uiGridConstants) {
  var stockData = [
    {name: 'Macbook Pro', qty: 5, price: 1500},
    {name: 'Macbook', qty: 3, price: 999},
    {name: 'Asus Zenbook', qty: 4, price: 1200},
    {name: 'Dell XPS', qty: 4, price: 1400},
    {name: 'HP Envy', qty: 2, price: 899},
    {name: 'Lenovo Yoga', qty: 7, price: 999},
    {name: 'Acer', qty: 10, price: 599},
    {name: 'HP Spectre', qty: 7, price: 1499},
    {name: 'Mac Pro', qty: 1, price: 2999},
    {name: 'Asus K', qty: 8, price: 949},
    {name: 'Toshiba L', qty: 6, price: 799},
    {name: 'Dell Inspiron', qty: 6, price: 899}
  ];

  // Table initialization using angular-ui-grid
  $scope.stock = {
    enableFiltering: true,
    enableRowSelection: true,
    enableSelectionBatchEvent: true,
    showGridFooter: true,
    showColumnFooter: false,
    paginationPageSizes: [25, 50, 75, 100],
    paginationPageSize: 10,
    columnDefs: [
      {name: 'name'},
      {name: 'qty'},
      {name: 'price'}
    ],
    data: stockData,
    onRegisterApi: function(gridApi) {
      $scope.gridApi = gridApi;
      gridApi.selection.on.rowSelectionChanged($scope, function() {
        if (gridApi.selection.getSelectedCount() === 1 && !$scope.itemSelected)
          $scope.itemSelected = true;
        else if (gridApi.selection.getSelectedCount() === 0)
          $scope.itemSelected = false
      });
      gridApi.selection.on.rowSelectionChangedBatch($scope, function() {
        if (!$scope.itemSelected)
          $scope.itemSelected = true;
        else if ($scope.itemSelected)
          $scope.itemSelected = false;
      });
    }
  };

  // When false then 'Add item' button is shown otherwise 'Delete' button is shown
  $scope.itemSelected = false;

  $scope.addItem = function() {
    $scope.stock.data.unshift({
      name: 'New Item',
      qty: 0,
      price: 0
    });
  };

  $scope.deleteItems = function() {
    angular.forEach($scope.gridApi.selection.getSelectedRows(), function (data, index) {
      $scope.stock.data.splice($scope.stock.data.lastIndexOf(data), 1);
    });
    $scope.gridApi.selection.clearSelectedRows();
    $scope.itemSelected = false;
  };
});
