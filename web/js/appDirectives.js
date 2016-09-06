angular.module('appDirectives', [])
.directive('trackedTable', function() {
  return {
    restrict: 'A',
    priority: -1,
    require: 'ngForm',
    controller: trackedTableController
  }
})
.directive('trackedTableRow', function() {
  return {
    restrict: 'A',
    priority: -1,
    require: ['^trackedTable', 'ngForm'],
    controller: trackedTableRowController
  }
})
.directive('trackedTableCell', function() {
  return {
    restrict: 'A',
    priority: -1,
    scope: true,
    require: ['^trackedTableRow', 'ngForm'],
    controller: trackedTableCellController
  }
});
