module.exports = class WsTokens {
  constructor(mysqlCon) {
    this.con = mysqlCon;
  }

  isValid(token, userId) {
    return new Promise((resolve, reject) => {
      this.con.query('SELECT count(*) AS count FROM `User_Api_Tokens` WHERE `UAT_Token` = ? AND `UAT_User_ID` = ?;',
	  [token, userId],
	  function (err, results) {
        resolve(results[0].count > 0);
      });
    });
  }
};
