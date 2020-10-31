# LXD Mosaic
<a href="https://github.com/turtle0x1/LxdMosaic/labels/more%20input%20required">I want <b>YOUR</b> input :loudspeaker:</a>

<a href="https://github.com/turtle0x1/LxdMosaic/issues/new?assignees=&labels=&template=feature_request.md&title=">I want <b>YOUR</b> ideas :thought_balloon:</a>

I also like stars, If you find yourself that way inclinded :angel:

<img src="https://i.imgur.com/vnhrSDW.png" width="50%"> <img src="https://i.imgur.com/xHSjw3J.png" width="49%">

<img src="https://i.imgur.com/YRRWcsj.png" width="50%"><img src="https://i.imgur.com/sj5pAYi.png" width="49%">

## Documentation

Please refer to the documentation <a href="https://lxdmosaic.readthedocs.io/en/latest/"> here </a>

**Default username & password is admin test123 - Change the password in settings ASAP**

Lost your admin password ? See <a href="https://lxdmosaic.readthedocs.io/en/latest/resetPassword/"> the docs</a>

## Installation

### Prep LXD Instances

You need to enable access from the network on your LXD hosts first, you can do this by logging onto your hosts and executing the following (make sure to change the password from "some-secret_string")

```bash
lxc config set core.https_address [::]
lxc config set core.trust_password some-secret-string #remember this you will be asked later
```

## Launching LXDMosaic

The preferred installation method is using a ubuntu container.

### SNAP

SNAP is now available for those interested, **if you're a company / power user - don't use snap**,
your support needs aren't best serviced.

"Home users" SNAP is perfectly fine & encouraged.

`sudo snap install lxdmosaic`

[![Get it from the Snap Store](https://snapcraft.io/static/images/badges/en/snap-store-black.svg)](https://snapcraft.io/lxdmosaic)

### Install script
**Warning this installs apache, mysql-server, php, git and other
dependencies its best to run in a container or an empty VM to avoid cluttering
your system**

In examples you will find an bash script called install_with_clone.sh this will
handle the installation of dependencies and setup this program.

It handles the cloning of the repository so you can just do;
#### Ubuntu (18.04 & 20.04) / Debian (Buster & Bullseye)
```bash
# Launch a ubuntu container
lxc launch ubuntu: lxdMosaic
# Connect to ubuntu console
lxc exec lxdMosaic bash
# Download the script
curl https://raw.githubusercontent.com/turtle0x1/LxdMosaic/master/examples/install_with_clone.sh >> installLxdMosaic.sh
# Then give the script execution permissions
chmod +x installLxdMosaic.sh
# Then run bellow to setup the program
./installLxdMosaic.sh
```
#### Centos 7
```bash
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

Once installation is complete you need to go to into your browser and goto;

`https://host_ip_address`

## Built With

Use lots of composer libraries and an extended [coreui](https://coreui.io/) for the frontend
