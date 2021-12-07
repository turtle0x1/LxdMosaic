# <img src="https://raw.githubusercontent.com/turtle0x1/LxdMosaic/master/src/assets/lxdMosaic/logo.png" height="25" width="25"> LXDMosaic
<a href="https://github.com/turtle0x1/LxdMosaic/labels/more%20input%20required">📢 Give your input </a> (or a star :angel:)
<a href="https://github.com/turtle0x1/LxdMosaic/milestones">🎯  Upcoming releases</a>
<a href="https://lxdmosaic.readthedocs.io/en/latest/">🗃️ Docs</a>

<img src="https://i.imgur.com/vnhrSDW.png" width="50%"> <img src="https://i.imgur.com/xHSjw3J.png" width="49%">

## 🔪 Prepare LXD
Assuming LXD is setup and ready to use, network access to LXD via a password is required for the initial setup of LXDMosaic.

You can do this by logging into your host's and executing the following commands.

```bash
lxc config set core.https_address [::] # make LXD available over IPV4 & IPV6 on all interafaces
lxc config set core.trust_password some-secret-string # password LXDMosaic needs, you will be asked for this later
```
_Once LXDMosaic is setup, you can unset the trust password for greater security._

## 🛫 Install LXDMosaic

#### 💻 SNAP ✔️ Less than 5 users or 5 servers :card_file_box: [docs](https://lxdmosaic.readthedocs.io/en/latest/Snap/)

`sudo snap install lxdmosaic`

#### 🖥️ Script ✔️ More than 5 users or 5 servers

**[:card_file_box: Ubuntu (18.04 & 20.04)](https://lxdmosaic.readthedocs.io/en/latest/#installing-lxdmosaic-ubuntu)**

**[:card_file_box: Debian (Buster & Bullseye)](https://lxdmosaic.readthedocs.io/en/latest/#installing-lxdmosaic-ubuntu)**

**[:card_file_box: Centos 7](https://lxdmosaic.readthedocs.io/en/latest/#installing-lxdmosaic-centos-7) ([⚠️ Deprecated +1 this issue if you need](https://github.com/turtle0x1/LxdMosaic/issues/457))**
