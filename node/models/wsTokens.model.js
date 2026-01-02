export default class  WsTokens {
  constructor(mysqlCon) {
    this.con = mysqlCon;
  }

  isValid(token, userId) {
    return new Promise((resolve, reject) => {
        if(process.env.hasOwnProperty("DB_SQLITE") && process.env.DB_SQLITE !== ""){
          this.con.all('SELECT count(*) AS count FROM `User_Api_Tokens` WHERE `UAT_Token` = ? AND `UAT_User_ID` = ?;',
    	  [token, userId],
    	  function (err, results) {
            resolve(results[0].count > 0);
          });
      }else{
          this.con.query('SELECT count(*) AS count FROM `User_Api_Tokens` WHERE `UAT_Token` = ? AND `UAT_User_ID` = ?;',
          [token, userId],
          function (err, results) {
            resolve(results[0].count > 0);
          });
      }

    });
}
};
