## LXDMosaic SNAP

Here you will find any SNAP specific instructions.


### Ports
You can set the Apache ports LXDMOsaic listens on using the following command

`sudo snap set lxdmosaic ports.http=81 ports.https=444`

### LXD Plug
Give LXDMsoaic access to the LXD socket if LXD isn't available over the network

`sudo snap connect lxdmosaic:lxd lxd:lxd`
