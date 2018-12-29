# LXDManager

This is an application you can use to do basic managment for multiple instaces
of lxd

## Why ?

Most of the other lxd managers I saw are only used  to manage one lxd instace which
is no good as I have many!

They also dont really touch on profiles or cloud config which I make heavy use
of.

## Installation

This has only been done on ubuntu 18.04 but it should work any where apache does

1. Install apache, php & composer

2. cd /var/www/

3. git clone https://github.com/turtle0x1/LxdManager.git

4. Install mysql file from sql/seed.sql this creates some empty for keeping track of
   - Hosts and cert names
   - Cloud config files and their revisions

5. Setup access through a webserver (there is an apache config example under /examples)

6. Add a hosts entry for your domain name (the default apache config uses lxd.local)

7. Change .env.dist to .env and fill out the values

8. Go to your browser and load the site

## Notes

This project was started sometime around 2017 so the code is very meh, it won't
win any prizes.

It will improve over time but it will do for a 0.1 (symfony request obj + symfony
responses will be a good start).

If you take great offence please feel free to open a pull request that improves it
other wise keep the issues related to bugs + feauture requests.

I have opened a bunch of issues for things that I want to implement and need doing
any help would be greatlly appreciated.

### Cloud Config

Cloud config is a incredibly powerfull tool to provision containers on first run.

This program contains the basic functionality to create and manage cloud config
files and deploy them to containers (through profiles) onto one or many hosts.

Most of the information about cloud config can be read here [here](https://cloudinit.readthedocs.io/en/latest/topics/examples.html)
