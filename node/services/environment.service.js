const dotenv = require('dotenv'),
  dotenvExpand = require('dotenv-expand');

module.exports = class Environment {
    load(path) {
        var envImportResult = dotenvExpand(dotenv.config({
          path: path
        }))

        if (envImportResult.error) {
          throw envImportResult.error;
        }
    }
}
