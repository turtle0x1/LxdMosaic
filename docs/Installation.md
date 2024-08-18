## Installation

### Pre-Installation

#### Initialize LXD

An opinionated guide on how to set up LXD is forthcoming. For now, refer to these guides:

- [Official Guide](https://linuxcontainers.org/lxd/getting-started-cli/)
- [Managing LXD Snap](https://discuss.linuxcontainers.org/t/managing-the-lxd-snap/8178)

#### Make LXD Available Over the Network

When LXDMosaic accesses each LXD server for the first time, it needs to authenticate using a token or trust password. This allows LXDMosaic to deploy a trust certificate for future communications.

First, make LXD available over the network:

```bash
lxc config set core.https_address :8443
```

For LXD version 6+ Token-based authentication is required. Token auth is available in version 5 onwards and is the recommended process:

```bash
lxc config trust add --name lxdmosaic
```

For LXD versions older than 6 You can use a "trust password," but this has security drawbacks and is not recommended:

```bash
lxc config set core.trust_password some-secret-string
```

Note: If you try to connect to an LXD server in a cluster, we will attempt to add all cluster members using the same trust password.

## Installing LXDMosaic on Ubuntu

```bash
# Launch an Ubuntu container
lxc launch ubuntu: lxdMosaic

# Connect to Ubuntu console
lxc exec lxdMosaic bash

# Download the script
curl https://raw.githubusercontent.com/turtle0x1/LxdMosaic/master/examples/install_with_clone.sh >> installLxdMosaic.sh

# Give the script execution permissions
chmod +x installLxdMosaic.sh

# Run the script to set up the program
./installLxdMosaic.sh
```

## Post-Installation
Once the installation is complete, follow these steps:
 - Open your browser and visit: https://container_ip_address
 - Accept the self-signed certificate
 - Follow the on screen instructions