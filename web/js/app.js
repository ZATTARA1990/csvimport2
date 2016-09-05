angular.module('candidatedevtest', ['ngTable'])

.controller('ctrl', function($scope, NgTableParams) {
  $scope.stock = [
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

  $scope.tableParams = new NgTableParams({
    sorting: {
      name: 'asc'
    }
  }, {dataset: $scope.stock});
});
