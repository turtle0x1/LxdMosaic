import fs from 'fs';

export default class  Filesystem {
    checkAndAwaitFileExists(filePath) {
        var startDate = new Date();
        while (!fs.existsSync(filePath)) {
            var seconds = (new Date().getTime() - startDate.getTime()) / 1000;
            if (seconds > 10) {
                return false
            }
        }
        return true;
    }

    readFileSync(path, options){
        return fs.readFileSync(path, options)
    }

    existsSync(path){
        return fs.existsSync(path)
    }
}
