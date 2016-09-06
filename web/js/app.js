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
  }, {dataset: angular.copy($scope.stock)});

  var originalData = angular.copy($scope.stock);
  $scope.cancel = cancel;
  $scope.del = del;
  $scope.save = save;

  function cancel(row, rowForm) {
    var originalRow = resetRow(row, rowForm);
    angular.extend(row, originalRow);
  }

  function del(row) {
    _.remove($scope.tableParams.settings().dataset, function(item) {
      return row === item;
    });
    $scope.tableParams.reload().then(function(data) {
      if (data.length === 0 && $scope.tableParams.total() > 0) {
        $scope.tableParams.page($scope.tableParams.page() - 1);
        $scope.tableParams.reload();
      }
    });
  }

  function resetRow(row, rowForm) {
    row.isEditing = false;
    rowForm.$setPristine();
    $scope.tableTracker.untrack(row);
    return _.findWhere(originalData, function(r) {
      return r.id === row.id;
    });
  }

  function save(row, rowForm) {
    var originalRow = resetRow(row, rowForm);
    angular.extend(originalRow, row);
  }
})
.controller('trackedTableController', function($scope, $parse, $attrs, $element) {
  var tableForm = $element.controller('form');
  var dirtyCellsByRow = [];
  var invalidCellsByRow = [];

  init();

  function init() {
    var setter = $parse($attrs.trackedTable).assign;
    setter($scope);
    $scope.$on('$destroy', function() {
      setter(null);
    });

    $scope.reset = reset;
    $scope.isCellDirty = isCellDirty;
    $scope.setCellDirty = setCellDirty;
    $scope.setCellInvalid = setCellInvalid;
    $scope.untrack = untrack;

    function getCellsForRow(row, cellsByRow) {
      return _.find(cellsByRow, function(entry) {
        return entry.row === row;
      })
    }

    function isCellDirty(row, cell) {
      var rowCells = getCellsForRow(row, dirtyCellsByRow);
      return rowCells && rowCells.cells.indexOf(cell) !== 1;
    }

    function reset() {
      dirtyCellsByRow = [];
      invalidCellsByRow = [];
      setInvalid(false);
    }

    function setCellDirty(row, cell, isDirty) {
      setCellStatus(row, cell, isDirty, dirtyCellsByRow);
    }

    function setCellInvalid(row, cell, isInvalid) {
      setCellStatus(row, cell, isInvalid, invalidCellsByRow);
      setInvalid(invalidCellsByRow.length > 0);
    }

    function setCellStatus(row, cell, value, cellsByRow) {
      var rowCells = getCellsForRow(row, cellsByRow);
      if (!rowCells && !value)
        return;

      if (value) {
        if (!rowCells) {
          rowCells = {
            row: row,
            cells: []
          };
          cellsByRow.push(rowCells);
        }
        if (rowCells.cells.indexOf(cell) === -1)
          rowCells.cells.push(cell);
      }
      else {
        _.remove(rowCells.cells, function(item) {
          return cell === item;
        });
        if (rowCells.cells.length === 0) {
          _.remove(cellsByRow, function(item) {
            return rowCells === item;
          });
        }
      }
    }

    function setInvalid(isInvalid) {
      $scope.$invalid = isInvalid;
      $scope.$valid = !isInvalid;
    }

    function untrack(row) {
      _.remove(invalidCellsByRow, function(item) {
        return item.row === row;
      });
      _.remove(dirtyCellsByRow, function(item) {
        return item.row === row;
      });
      setInvalid(invalidCellsByRow.length > 0);
    }
  }
})
.controller('trackedTableRowContoller', function($attrs, $element, $parse, $scope) {
  var row = $parse($attrs.trackedTableRow)($scope);
  var rowFormCtrl = $element.controller('form');
  var trackedTableCtrl = $element.controller('trackedTable');

  $scope.isCellDirty = isCellDirty;
  $scope.setCellDirty = setCellDirty;
  $scope.setCellInvalid = setCellInvalid;

  function isCellDirty(cell) {
    return trackedTableCtrl.isCellDirty(row, cell);
  }

  function setCellDirty(cell, isDirty) {
    return trackedTableCtrl.setCellDirty(row, cell, isDirty);
  }

  function setCellInvalid(cell, isInvalid) {
    return trackedTableCtrl.setCellInvalid(row, cell, isInvalid);
  }
})
.controller('trackedTableCellController', function($attrs, $element, $scope) {
  var cellFormCtrl = $element.controller('form');
  var cellName = cellFormCtrl.$name;
  var trackedTableRowCtrl = $element.controller('trackedTableRow');

  if (trackedTableRowCtrl.isCellDirty(cellName))
    cellFormCtrl.$setDirty();
  else
    cellFormCtrl.$setPristine();

  $scope.$watch(function() {
    return cellFormCtrl.$dirty;
  }, function(newValue, oldValue) {
    if (newValue === oldValue) return;

    trackedTableRowCtrl.setCellDirty(cellName, newValue);
  });

  $scope.$watch(function() {
    return cellFormCtrl.$invalid;
  }, function(newValue, oldValue) {
    if (newValue === oldValue) return;

    trackedTableRowCtrl.setCellInvalid(cellName, newValue);
  });
});
