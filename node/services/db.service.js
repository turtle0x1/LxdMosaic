const mysql = require('mysql'),
    sqlite3 = require('sqlite3').verbose();

module.exports = class DbConnection {
    getDbConnection(usingSqllite) {
        if (!usingSqllite) {
            return mysql.createConnection({
                host: process.env.DB_HOST,
                user: process.env.DB_USER,
                password: process.env.DB_PASS,
                database: process.env.DB_NAME,
            });
        }

        return new sqlite3.Database(process.env.DB_SQLITE);
    }
}
