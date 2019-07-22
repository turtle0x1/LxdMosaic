## cloud-config

Cloud config is a tool that "handles early initialization of a cloud instance."

Its an incredibly powerful tool when combined with LXD profiles to provision
the creation of machines.

You can read more about it <a href="https://cloudinit.readthedocs.io/en/latest/index.html" target="_blank">here</a>

### Creating a cloud-config file

LXDMosaic makes an effort to keep a history of your cloud-config files, It keeps:

 - A namespace
 - Details about the image this cloud-config is targeted with
 - A full copy of exactly what you put into the editor displayed on the screen

 _this is not git, svn or any attempt to mimic any vcs_.

#### Namespace

Currently you can use 1 level of namespacing for storing cloud-configs, this means
that you could have:

 - webservers/My_Clients_Site
 - webservers/My_Personal_Site

And/Or
 - database/My_Clients_Site
 - database/My_Personal_Site

This makes it easier to:

 - Browse your cloud-configs
 - Write a CLI program that can interprit different namespaces

_We may support depper nesting in the future_

#### The Image

Cloud-config files can be written to target all kinds off operating systems,
so the decision to link a cloud-config file to an image may appear odd, especially
as at the time of writing **only the ubuntu images come with cloud-config enabled**

The reason LXDMosaic stores is the image with the file is:

 - You have to fill out less on forms (deploying cloud-config & deployments)
 - If a CLI program is written you wont have to specify the image

#### The config itself

This documentation couldn't cover 1% of what the official documentation does,
so please go and read it <a href="https://cloudinit.readthedocs.io/en/latest/index.html" target="_blank">here</a>

_The example deployment config that comes with LXDMosaic looks like this_

```
#cloud-config

# Apply updates using apt
package_update: true
package_upgrade: true

# Install packages
packages:
 - nodejs
 - npm

runcmd:
 - git clone https://github.com/turtle0x1/nodeExample /root/nodeExample
 - cd /root/nodeExample && npm install
 - npm -g install pm2
 - pm2 start /root/nodeExample/index.js
 - pm2 startup
 - pm2 save
```
