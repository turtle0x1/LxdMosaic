import dotenv from 'dotenv';
import dotenvExpand from 'dotenv-expand';

export default class  Environment {
    load(path) {
        var envImportResult = dotenvExpand(dotenv.config({
          path: path
        }))

        if (envImportResult.error) {
          throw envImportResult.error;
        }
    }
}
