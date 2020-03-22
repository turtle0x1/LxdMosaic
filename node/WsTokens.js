module.exports = class WsTokens {
  constructor(mysqlCon) {
    this.con = mysqlCon;
  }

  isValid(token, userId) {
    return new Promise((resolve, reject) => {
      this.con.query('SELECT count(*) AS count FROM ws_tokens WHERE token = ? AND user_id = ?;',
	  [token, userId],
	  function (err, results) {
         if (results[0].count <= 0) {
           resolve(false);
	 } else {
           resolve(true);
	 }
      });
    });
  }
};
