## Installation


### Pre Installation

#### Initialise LXD

An opinionated guide on how to setup LXD is to come, for now there are a few guides;

 - <a href="https://linuxcontainers.org/lxd/getting-started-cli/">Official Guide</a>
 - <a href="https://discuss.linuxcontainers.org/t/managing-the-lxd-snap/8178">Managing LXD Snap</a>

#### Make LXD available over the network

When LXDMosaic accesses each LXD server for the first time it needs to be able to authenticate
using a trust password, this is so LXDMOsaic can deploy a trust certificate for future communications.

You can set a trust password by executing the following commands on each LXD server.

```bash
lxc config set core.https_address [::] # make LXD available over IPV4 & IPV6 on all interafaces
lxc config set core.trust_password some-secret-string # password LXDMosaic needs, you will be asked for this later
```

_If you try to connect to LXD server in a cluster we will try to add all cluster members using the same trust password_

### Installing LXDMosaic Ubuntu
```
# Launch a ubuntu container
lxc launch ubuntu: lxdMosaic
# Connect to ubuntu console
lxc exec lxdMosaic bash
#  Download the script
curl https://raw.githubusercontent.com/turtle0x1/LxdMosaic/master/examples/install_with_clone.sh >> installLxdMosaic.sh
# Then give the script execution permissions
chmod +x installLxdMosaic.sh
# Then run bellow to setup the program
./installLxdMosaic.sh
```
### Installing LXDMosaic Centos 7
```
# Launch a centos 7 container
lxc launch images:centos/7/amd64 lxdMosaic
# Connect to centos console
lxc exec lxdMosaic bash
# Download the script
curl https://raw.githubusercontent.com/turtle0x1/LxdMosaic/master/examples/install_with_clone_centos7.sh >> installLxdMosaic.sh
# Then give the script execution permissions
chmod +x installLxdMosaic.sh
# Then run bellow to setup the program
./installLxdMosaic.sh
```

### Post Installation
Once the installation is complete you need to go to into your browser and vist;

`https://container_ip_address`

and accept the self signed certificate, you will then be able to enter your LXD instance
details.
