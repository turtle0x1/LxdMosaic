import configparser
import os
from datetime import datetime
import json

class GetSystemMetrics():
    def getAll(self):
        return {
            "loadAvg": self.getLoadAvg()
        }

    def getLoadAvg(self):
        f = open("/proc/loadavg", "r")
        loadAvgString = f.read()
        loadAvgString = loadAvgString.replace("\n", "")
        parts = loadAvgString.split(" ")
        return {
            "1": parts[0],
            "5": parts[1],
            "15": parts[2]
        }

class SendData():
    directory = "/etc/lxdMosaic/offlineLogs/"
    def setupLogFolder(self):
        if not os.path.exists(self.directory):
            os.makedirs(self.directory)


    def writeFile(self, data):
        self.setupLogFolder()

        f = open(self.directory + str(datetime.now()) + ".json" , "a")
        f.write(json.dumps(data))
        f.close()


    def send(self, servers, data):
        if servers == {}:
            return self.writeFile(data)

config = configparser.ConfigParser()

config.read("config.ini");

servers = {}

for key in config.sections():
    if "Server" in key:
        if "LxdMosaic" in config[key]["Type"]:
            servers[key] = config[key]["Address"]

getSysMetrics = GetSystemMetrics()
sendData = SendData()


data = getSysMetrics.getAll()
sendData.send(servers, data)
