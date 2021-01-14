var WebSocket = require('ws');
var internalUuidv1 = require('uuid/v1');

module.exports = class AllowedProjects {
  constructor(database) {
    this.con = database;
  }

  canAccessHostProject(userId, hostId, project) {
    return new Promise(async (resolve, reject) => {
        let isAdmin = await this.isAdmin(userId);

        if(isAdmin){
            return resolve(true);
        }

        let sql = `SELECT
                        1
                    FROM
                        User_Allowed_Projects
                    WHERE
                        (
                            UAP_User_ID = ?
                            AND
                            UAP_Host_ID = ?
                            AND
                            UAP_Project = ?
                        )`;

        if(process.env.hasOwnProperty("DB_SQLITE") && process.env.DB_SQLITE !== ""){
            this.con.all(sql, [userId, hostId, project], function(err, results) {
                resolve(results.length >= 1);
            });
        }else{
            this.con.query(sql, [userId, hostId, project], function(err, results) {
                resolve(results.length >= 1);
            });
        }
    });
  }

  isAdmin(userId){
      return new Promise((resolve, reject) => {
          let sql = `SELECT 1 FROM Users WHERE User_ID = ? AND User_Admin = 1`;

          if(process.env.hasOwnProperty("DB_SQLITE") && process.env.DB_SQLITE !== ""){
              this.con.all(sql, [userId], function(err, results) {
                  resolve(results.length >= 1);
              });
          }else{
              this.con.query(sql, [userId], function(err, results) {
                  resolve(results.length >= 1);
              });
          }
      });

  }
};
