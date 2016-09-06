module.exports = {
  "extends": "standard",
  "installedESLint": true,
  "env": {
    "browser": true,
    "jquery": true
  },
  "plugins": [
    "standard",
    "html",
    "json"
  ],
  "globals": {
    "angular": true,
    "_": true
  },
  "rules": {
    "semi": 0,
    "space-before-function-paren": 0,
    "brace-style": [1, "stroustrup"],
    "curly": ["error", "multi-or-nest"],
    "no-path-concat": 0
  }
};
