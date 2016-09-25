angular.module('appServices', [])
.factory('dataService', function($http) {
  return {
    get: function() {
      return $http.get('/api/get');
    },
    post: function(data) {
      return $http.post('/api/post', data);
    },
    delete: function(data) {
      return $http.delete('/api/delete', data);
    }
  }
});
