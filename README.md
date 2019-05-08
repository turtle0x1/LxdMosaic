# LXD Mosaic

This is an application you can use to do basic management for multiple instances
of LXD


<img src="https://i.imgur.com/rp1912o.png" width="428"> <img src="https://i.imgur.com/xTBv82W.png" width="428">

<img src="https://i.imgur.com/47Q3kEh.png" width="428"><img src="https://i.imgur.com/KXbwvnL.png" width="428">



## Why ?

Most of the other LXD managers I saw are only used  to manage one LXD instance which
is no good as I have many!

They also don't really touch on profiles or cloud config which I make heavy use
of.

## Install Prep

### LXD Hosts

You need to enable access from the network on your LXD hosts first, you can do this by logging onto your hosts and executing the following (make sure to change the password from "some-secret_string")

```bash
lxc config set core.https_address [::]
lxc config set core.trust_password some-secret-string
```

When first accessing the web application it will ask you about your "trust password" which is the password you set in the last command

## Installation

The preferred installation method is using a ubuntu container.

### Install script
**Warning this installs apache, docker, mysql-server, php, git and other
dependencies its best to run in a container or an empty VM to avoid cluttering
your system**

In examples you will find an bash script called install_with_clone.sh this will
handle the installation of dependencies and setup this program.

It handles the cloning of the repository so you can just do;
#### Ubuntu
```bash
# Launch a centos 7 container
lxc launch ubuntu: lxdMosaic
# Connect to centos console
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

`https://host_ip_address:3000` and allow the self signed certificate (for node)

Then go to the following web address to run the application

`https://host_ip_address`

# Notes

This project was started sometime around 2017 so the code is very meh, it won't
win any prizes.

It will improve over time but it will do for a 0.1 (symfony request obj + symfony
responses will be a good start).

If you take great offence please feel free to open a pull request that improves it
other wise keep the issues related to bugs + feature requests.

I have opened a bunch of issues for things that I want to implement and need doing
any help would be greatly appreciated.

## Cloud Config

Cloud config is a incredibly powerful tool to provision containers on first run.

This program contains the basic functionality to create and manage cloud config
files and deploy them to containers (through profiles) onto one or many hosts.

Most of the information about cloud config can be read here [here](https://cloudinit.readthedocs.io/en/latest/topics/examples.html)

## Built With

Use lots of composer libraries and an extended [coreui](https://coreui.io/) for the frontend
