module.exports = class Hosts {
  constructor(mysqlCon, filesystem, rp) {
    this.con = mysqlCon;
    this.fs = filesystem;
    this.rp = rp;
    this.hostDetails = {};
  }

  getHosts() {
    return this.hostDetails;
  }

  loadHosts() {
    return new Promise((resolve, reject) => {
      this.hostDetails = {};
      this.loadHostsFromDb().then(mysqlResults => {
        this.addDetails(mysqlResults).then(hosts => {
          this.hostDetails = hosts;
          resolve(hosts);
        });
      });
    });
  }

  loadHostsFromDb() {
    return new Promise((resolve, reject) => {
      this.con.query('SELECT * FROM Hosts', function(err, results) {
        resolve(results);
      });
    });
  }

  addDetails(results) {
    return new Promise((resolve, reject) => {
      var promises = [];
      for (let i = 0; i < results.length; i++) {
        if (results[i].Host_Online == 0) {
          promises.push(null);
          continue;
        }

        let lxdClientCert = certDir + results[i].Host_Cert_Only_File;
        let lxdClientKey = certDir + results[i].Host_Key_File;

        let stringUrl = results[i].Host_Url_And_Port;
        let urlURL = new URL(results[i].Host_Url_And_Port);

        results[i].cert = this.fs.readFileSync(lxdClientCert);
        results[i].key = this.fs.readFileSync(lxdClientKey);

        promises.push(
          this.getServerInfo(stringUrl, results[i].cert, results[i].key)
        );
      }

      Promise.all(promises).then(values => {
        let output = {};
        for (let i = 0; i < results.length; i++) {
          if (results[i].Host_Online == 0) {
            continue;
          }

          var portRegex = /:[0-9]+/;

          let stringUrl = results[i].Host_Url_And_Port;
          let urlURL = new URL(results[i].Host_Url_And_Port);

          let hostWithOutProto = stringUrl.replace('https://', '');
          let hostWithOutProtoOrPort = hostWithOutProto.replace(portRegex, '');

          let hostInfo = values[i];

          output[results[i].Host_ID] = {
            hostId: results[i].Host_ID,
            cert: results[i].cert,
            key: results[i].key,
            hostWithOutProto: hostWithOutProto,
            hostWithOutProtoOrPort: hostWithOutProtoOrPort,
            port: urlURL.port,
            supportsVms: hostInfo.metadata.api_extensions.includes(
              'virtual-machines'
            ),
          };
        }
        resolve(output);
      });
    });
  }

  getServerInfo(stringUrl, lxdClientCert, lxdClientKey) {
    return this.rp({
      uri: `${stringUrl}/1.0`,
      cert: lxdClientCert,
      key: lxdClientKey,
      rejectUnauthorized: false,
      json: true,
    });
  }
};
