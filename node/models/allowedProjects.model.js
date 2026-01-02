export default class AllowedProjects {
    constructor(database) {
        this.con = database;
    }

    async canAccessHostProject(userId, hostId, project) {
        const isAdmin = await this.isAdmin(userId);

        if (isAdmin) {
            return true;
        }

        const sql = `
      SELECT
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

        return new Promise((resolve) => {
            if (process.env.hasOwnProperty('DB_SQLITE') && process.env.DB_SQLITE !== '') {
                this.con.all(sql, [userId, hostId, project], (err, results) => {
                    resolve(results.length >= 1);
                });
            } else {
                this.con.query(sql, [userId, hostId, project], (err, results) => {
                    resolve(results.length >= 1);
                });
            }
        });
    }

    isAdmin(userId) {
        const sql = `SELECT 1 FROM Users WHERE User_ID = ? AND User_Admin = 1`;

        return new Promise((resolve) => {
            if (process.env.hasOwnProperty('DB_SQLITE') && process.env.DB_SQLITE !== '') {
                this.con.all(sql, [userId], (err, results) => {
                    resolve(results.length >= 1);
                });
            } else {
                this.con.query(sql, [userId], (err, results) => {
                    resolve(results.length >= 1);
                });
            }
        });
    }
}
