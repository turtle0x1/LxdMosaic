var http = require('http');
var https = require('https');
var fs = require('fs');

module.exports = class Hosts {
  constructor(fetchHosts) {
    this._fetchHosts = fetchHosts
    this.hostDetails = {};
    this.certDir = process.env.LXD_CERTS_DIR;
  }

  loadHosts() {
    return new Promise((resolve, reject) => {
      this.hostDetails = {};
      this._fetchHosts.fetchAll().then(mysqlResults => {
        this.addDetails(mysqlResults).then(hosts => {
          this.hostDetails = hosts;
          resolve(hosts);
        });
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

        let lxdClientCert = this.certDir + results[i].Host_Cert_Only_File;
        let lxdClientKey = this.certDir + results[i].Host_Key_File;

        let socketPath = results[i].Host_Socket_Path;

        let stringUrl = "";

        if(socketPath == '' || socketPath == null){
            stringUrl = results[i].Host_Url_And_Port;
        }

        results[i].cert = fs.readFileSync(lxdClientCert);
        results[i].key = fs.readFileSync(lxdClientKey);

        promises.push(
          this.getServerInfo(stringUrl, results[i].cert, results[i].key, socketPath)
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
          let port = null;
          let socketPath = results[i].Host_Socket_Path;

          if(socketPath == '' || socketPath == null){
              port = new URL(results[i].Host_Url_And_Port).port
          }

          let hostWithOutProto = stringUrl.replace('https://', '');
          let hostWithOutProtoOrPort = hostWithOutProto.replace(portRegex, '');

          let hostInfo = values[i];
          let alias = results[i].Host_Alias == "" ? results[i].Host_Url_And_Port : results[i].Host_Alias;

          output[results[i].Host_ID] = {
            hostId: results[i].Host_ID,
            cert: results[i].cert,
            key: results[i].key,
            socketPath: socketPath,
            hostWithOutProto: hostWithOutProto,
            hostWithOutProtoOrPort: hostWithOutProtoOrPort,
            port: port,
            alias: alias,
            supportsVms: hostInfo.metadata.api_extensions.includes(
              'virtual-machines'
            ),
          };
        }
        resolve(output);
      });
    });
  }

  getServerInfo(stringUrl, lxdClientCert, lxdClientKey, socketPath) {
        return new Promise((resolve, reject)=>{
            const options = {
              cert: lxdClientCert,
              key: lxdClientKey,
              rejectUnauthorized: false,
              json: true,
              path: "/1.0"
            };

            const callback = res => {
              res.setEncoding('utf8');
              let chunks = [];
              res.on('data', function(data) {
                chunks.push(data);
              }).on('end', function() {
                resolve(JSON.parse(chunks.join('')))
              }).on('error', function(data){
                  reject(data)
              });
            };

            if(socketPath == null){
                let url = new URL(stringUrl)
                options.host = url.hostname
                options.port = url.port
                const clientRequest = https.request(options, callback);
                clientRequest.end();
            }else{
                options.socketPath = socketPath
                const clientRequest = http.request(options, callback);
                clientRequest.end();
            }
        })
    }
};
