module.exports = class FetchHosts {
    constructor(con){
        this._con = con
    }

    fetchAll() {
      return new Promise((resolve, reject) => {
          let query = 'SELECT * FROM Hosts';
          if(process.env.hasOwnProperty("DB_SQLITE") && process.env.DB_SQLITE !== ""){
              this._con.all(query, function(err, results) {
                resolve(results);
              });
          }else{
              this._con.query(query, function(err, results) {
                resolve(results);
              });
          }
      });
    }
}
