module.exports = {
  "extends": "standard",
  "installedESLint": true,
  "env": {
    "browser": true,
    "jquery": true,
    "node": true,
    "mongo": true
  },
  "plugins": [
    "standard",
    "html",
    "json"
  ],
  "globals": {
    "angular": true,
    "context": true,
    "dataLoaded": true
  },
  "rules": {
    "semi": 0,
    "space-before-function-paren": 0,
    "brace-style": [1, "stroustrup"],
    "curly": ["error", "multi-or-nest"],
    "no-path-concat": 0
  }
};
