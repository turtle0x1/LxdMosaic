# LXDManager

This is an application you can use to do basic management for multiple instances
of lxd

## Why ?

Most of the other lxd managers I saw are only used  to manage one lxd instace which
is no good as I have many!

They also don't really touch on profiles or cloud config which I make heavy use
of.

## Installation

The preferred installation is currently docker but the install script should
do the trick (only tested on ubuntu 18.04)

## LXD Hosts

You need to enable access from the network on your lxd hosts first, you can do this by logging onto your hosts and executing the following (make sure to change the password from "some-secret_string")

```
lxc config set core.https_address [::]
lxc config set core.trust_password some-secret-string
```

When first accessing the web application it will ask you about your "trust password" which is the password you set in the last command

## Install script
**Warning this installs apache, docker, mysql-server, php, git and other
dependecies its best to run in a container or an empty vm to avoid clutering
your system**

In examples you will find an bash script called install_with_clone.sh this will
handle the installation of dependecies and setup this program.

It handles the cloning of the repository so you can just do;

`curl https://raw.githubusercontent.com/turtle0x1/LxdManager/master/examples/install_with_clone.sh >> installLxdManager.sh`

Then give the script execution permissions

`chmod +x installLxdManager.sh`

Then run bellow to setup the program

`sudo ./installLxdManager.sh`

Once installation is complete you need to go to into your browser and goto;

`https://host_ip_address:3000` and allow the self signed certificate (for node)

Then go to the following web address to run the application

`https://host_ip_address`

## Manual

The manual steps for deploying this can be read from the install script which
will handle all the dependencies it should be commented well enough to serve
as instructions!

## Docker

Docker was supported until the 0.1 release where a node server was added and
I frankly didn't have the energy to keep it up to date!

You should install this in a container or a VM!

# Notes

This project was started sometime around 2017 so the code is very meh, it won't
win any prizes.

It will improve over time but it will do for a 0.1 (symfony request obj + symfony
responses will be a good start).

If you take great offense please feel free to open a pull request that improves it
other wise keep the issues related to bugs + feauture requests.

I have opened a bunch of issues for things that I want to implement and need doing
any help would be greatly appreciated.

## Cloud Config

Cloud config is a incredibly powerful tool to provision containers on first run.

This program contains the basic functionality to create and manage cloud config
files and deploy them to containers (through profiles) onto one or many hosts.

Most of the information about cloud config can be read here [here](https://cloudinit.readthedocs.io/en/latest/topics/examples.html)
