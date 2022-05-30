var lxdDevicesProperties = {
    "none": {
        "description": "A none type device doesn’t have any property and doesn’t create anything inside the instance. It’s only purpose it to stop inheritance of devices coming from profiles.",
        "supportedInstanceTypes": ["container", "vm"],
        "properties": []
    },
    // "nic": {
    //     //TODO
    // },
    "disk": {
        "description": "Disk entries are essentially mountpoints inside the instance. They can either be a bind-mount of an existing file or directory on the host, or if the source is a block device, a regular mount.",
        "supportedInstanceTypes": ["container", "vm"],
        "properties": [
            {
                "name": "limits.read",
                "type": "string",
                "default": "-",
                "required": false,
                "description": "I/O limit in byte/s (various suffixes supported, see below) or in iops (must be suffixed with “iops”)"
            },
            {
                "name": "limits.write",
                "type": "string",
                "default": "-",
                "required": false,
                "description": "I/O limit in byte/s (various suffixes supported, see below) or in iops (must be suffixed with “iops”)"
            },
            {
                "name": "limits.max",
                "type": "string",
                "default": "-",
                "required": false,
                "description": "Same as modifying both limits.read and limits.write"
            },
            {
                "name": "path",
                "type": "string",
                "default": "-",
                "required": true,
                "description": "Path inside the instance where the disk will be mounted (only for containers)."
            },
            {
                "name": "source",
                "type": "string",
                "default": "-",
                "required": true,
                "description": "Path on the host, either to a file/directory or to a block device"
            },
            {
                "name": "required",
                "type": "boolean",
                "default": "true",
                "required": false,
                "description": "Controls whether to fail if the source doesn’t exist"
            },
            {
                "name": "readonly",
                "type": "boolean",
                "default": "false",
                "required": false,
                "description": "Controls whether to make the mount read-only"
            },
            {
                "name": "size",
                "type": "string",
                "default": "",
                "required": false,
                "description": "Disk size in bytes (various suffixes supported). This is only supported for the rootfs (/)"
            },
            {
                "name": "size.state",
                "type": "string",
                "default": "",
                "required": false,
                "description": "Same as size above but applies to the filesystem volume used for saving runtime state in virtual machines."
            },
            {
                "name": "recursive",
                "type": "boolean",
                "default": "false",
                "required": false,
                "description": "Whether or not to recursively mount the source path"
            },
            {
                "name": "pool",
                "type": "string",
                "default": "-",
                "required": false,
                "description": "The storage pool the disk device belongs to. This is only applicable for storage volumes managed by LXD"
            },
            {
                "name": "propagation",
                "type": "string",
                "default": "-",
                "required": false,
                "description": "Controls how a bind-mount is shared between the instance and the host. (Can be one of private, the default, or shared, slave, unbindable, rshared, rslave, runbindable, rprivate. Please see the Linux Kernel shared subtree documentation for a full explanation)"
            },
            {
                "name": "shift",
                "type": "boolean",
                "default": "-",
                "required": false,
                "description": "Setup a shifting overlay to translate the source uid/gid to match the instance (only for containers)"
            },
            {
                "name": "raw.mount.options",
                "type": "string",
                "default": "-",
                "required": false,
                "description": "Filesystem specific mount options"
            },
            {
                "name": "ceph.user_name",
                "type": "string",
                "default": "admin",
                "required": false,
                "description": "If source is Ceph or CephFS then Ceph user_name must be specified by user for proper mount"
            },
            {
                "name": "ceph.cluster_name",
                "type": "string",
                "default": "ceph",
                "required": false,
                "description": "If source is Ceph or CephFS then Ceph cluster_name must be specified by user for proper mount"
            },
            {
                "name": "boot.priority",
                "type": "integer",
                "default": "ceph",
                "required": false,
                "description": "Boot priority for VMs (higher boots first)"
            },
        ]
    },
    "unix-char": {
        "description": "Unix character device entries simply make the requested character device appear in the instance’s /dev and allow read/write operations to it.",
        "supportedInstanceTypes": ["container"],
        "properties": [
            {
                "name": "source",
                "type": "string",
                "default": "-",
                "required": false,
                "description": "Path on the host"
            },
            {
                "name": "path",
                "type": "string",
                "default": "-",
                "required": false,
                "description": "Path inside the instance (one of “source” and “path” must be set)"
            },
            {
                "name": "major",
                "type": "int",
                "default": "device on host",
                "required": false,
                "description": "Device major number"
            },
            {
                "name": "minor",
                "type": "int",
                "default": "Device on host",
                "required": false,
                "description": "Device minor number"
            },
            {
                "name": "uid",
                "type": "int",
                "default": "0",
                "required": false,
                "description": "UID of the device owner in the instance"
            },
            {
                "name": "gid",
                "type": "int",
                "default": "0",
                "required": false,
                "description": "GID of the device owner in the instance"
            },
            {
                "name": "mode",
                "type": "int",
                "default": "0660",
                "required": false,
                "description": "Mode of the device in the instance"
            },
            {
                "name": "required",
                "type": "boolean",
                "default": true,
                "required": false,
                "description": "Whether or not this device is required to start the instance"
            },
        ]
    },
    "unix-block": {
        "description": "Unix block device entries simply make the requested block device appear in the instance’s /dev and allow read/write operations to it.",
        "supportedInstanceTypes": ["container"],
        "properties": [
            {
                "name": "source",
                "type": "string",
                "default": "-",
                "required": false,
                "description": "Path on the host"
            },
            {
                "name": "path",
                "type": "string",
                "default": "-",
                "required": false,
                "description": "Path inside the instance (one of “source” and “path” must be set)"
            },
            {
                "name": "major",
                "type": "int",
                "default": "device on host",
                "required": false,
                "description": "Device major number"
            },
            {
                "name": "minor",
                "type": "int",
                "default": "Device on host",
                "required": false,
                "description": "Device minor number"
            },
            {
                "name": "uid",
                "type": "int",
                "default": "0",
                "required": false,
                "description": "UID of the device owner in the instance"
            },
            {
                "name": "gid",
                "type": "int",
                "default": "0",
                "required": false,
                "description": "GID of the device owner in the instance"
            },
            {
                "name": "mode",
                "type": "int",
                "default": "0660",
                "required": false,
                "description": "Mode of the device in the instance"
            },
            {
                "name": "required",
                "type": "boolean",
                "default": true,
                "required": false,
                "description": "Whether or not this device is required to start the instance"
            },
        ]
    },
    "usb": {
        "description": "USB device entries simply make the requested USB device appear in the instance.",
        "supportedInstanceTypes": ["container", "vm"],
        "properties": [
            {
                "name": "vendorid",
                "type": "string",
                "default": "-",
                "required": false,
                "description": "The vendor id of the USB device"
            },
            {
                "name": "productid",
                "type": "string",
                "default": "-",
                "required": false,
                "description": "The product id of the USB device"
            },
            {
                "name": "uid",
                "type": "int",
                "default": "0",
                "required": false,
                "description": "UID of the device owner in the instance"
            },
            {
                "name": "gid",
                "type": "int",
                "default": "0",
                "required": false,
                "description": "GID of the device owner in the instance"
            },
            {
                "name": "mode",
                "type": "int",
                "default": "0660",
                "required": false,
                "description": "Mode of the device in the instance"
            },
            {
                "name": "required",
                "type": "boolean",
                "default": true,
                "required": false,
                "description": "Whether or not this device is required to start the instance. (The default is false, and all devices are hot-pluggable)"
            }
        ]
    },
    gpu: {
        "description": "GPU device entries simply make the requested gpu device appear in the instance.",
        "supportedInstanceTypes": ["container", "vm"],
        "typeKey": "gputype",
        "types": {
            "physical": {
                "type": "physical",
                "supportedInstanceTypes": ["container", "vm"],
                "description": "Passes through an entire GPU.",
                "properties": [
                    {
                        "name": "vendorid",
                        "type": "string",
                        "default": "-",
                        "required": false,
                        "description": "The vendor id of the GPU device"
                    },
                    {
                        "name": "productid",
                        "type": "string",
                        "default": "-",
                        "required": false,
                        "description": "The product id of the GPU device"
                    },
                    {
                        "name": "id",
                        "type": "string",
                        "default": "-",
                        "required": false,
                        "description": "The card id of the GPU device"
                    },
                    {
                        "name": "pci",
                        "type": "string",
                        "default": "-",
                        "required": false,
                        "description": "The pci address of the GPU device"
                    },
                    {
                        "name": "uid",
                        "type": "int",
                        "default": "0",
                        "required": false,
                        "description": "UID of the device owner in the instance (container only)"
                    },
                    {
                        "name": "gid",
                        "type": "int",
                        "default": "0",
                        "required": false,
                        "description": "GID of the device owner in the instance (container only)"
                    },
                    {
                        "name": "mode",
                        "type": "int",
                        "default": "0660",
                        "required": false,
                        "description": "Mode of the device in the instance (container only)"
                    },
                ]
            },
            "mdev": {
                "type": "mdev",
                "supportedInstanceTypes": ["vm"],
                "description": "Creates and passes through a virtual GPU into the instance.",
                "properties": [
                    {
                        "name": "vendorid",
                        "type": "string",
                        "default": "-",
                        "required": false,
                        "description": "The vendor id of the GPU device"
                    },
                    {
                        "name": "productid",
                        "type": "string",
                        "default": "-",
                        "required": false,
                        "description": "The product id of the GPU device"
                    },
                    {
                        "name": "id",
                        "type": "string",
                        "default": "-",
                        "required": false,
                        "description": "The card id of the GPU device"
                    },
                    {
                        "name": "pci",
                        "type": "string",
                        "default": "-",
                        "required": false,
                        "description": "The pci address of the GPU device"
                    },
                    {
                        "name": "mdev",
                        "type": "string",
                        "default": "-",
                        "required": true,
                        "description": "The mdev profile to use (e.g. i915-GVTg_V5_4)"
                    }
                ]
            },
            "mig": {
                "type": "mig",
                "supportedInstanceTypes": ["container"],
                "description": "Creates and passes through a MIG compute instance. This currently requires NVIDIA MIG instances to be pre-created.",
                "warnings": [
                    "Either “mig.uuid” (Nvidia drivers 470+) or both “mig.ci” and “mig.gi” (old Nvidia drivers) must be set."
                ],
                "properties": [
                    {
                        "name": "vendorid",
                        "type": "string",
                        "default": "-",
                        "required": false,
                        "description": "The vendor id of the GPU device"
                    },
                    {
                        "name": "productid",
                        "type": "string",
                        "default": "-",
                        "required": false,
                        "description": "The product id of the GPU device"
                    },
                    {
                        "name": "id",
                        "type": "string",
                        "default": "-",
                        "required": false,
                        "description": "The card id of the GPU device"
                    },
                    {
                        "name": "pci",
                        "type": "string",
                        "default": "-",
                        "required": false,
                        "description": "The pci address of the GPU device"
                    },
                    {
                        "name": "mig.ci",
                        "type": "int",
                        "default": "-",
                        "required": false,
                        "description": "Existing MIG compute instance ID"
                    },
                    {
                        "name": "mig.gi",
                        "type": "int",
                        "default": "-",
                        "required": false,
                        "description": "Existing MIG GPU instance ID"
                    },
                    {
                        "name": "mig.uuid",
                        "type": "string",
                        "default": "-",
                        "required": false,
                        "description": "Existing MIG device UUID (“MIG-” prefix can be omitted)"
                    },
                ]
            },
            "sriov": {
                "type": "sriov",
                "supportedInstanceTypes": ["vm"],
                "description": "Passes a virtual function of an SR-IOV enabled GPU into the instance.",
                "warnings": [],
                "properties": [
                    {
                        "name": "vendorid",
                        "type": "string",
                        "default": "-",
                        "required": false,
                        "description": "The vendor id of the GPU device"
                    },
                    {
                        "name": "productid",
                        "type": "string",
                        "default": "-",
                        "required": false,
                        "description": "The product id of the GPU device"
                    },
                    {
                        "name": "id",
                        "type": "string",
                        "default": "-",
                        "required": false,
                        "description": "The card id of the GPU device"
                    },
                    {
                        "name": "pci",
                        "type": "string",
                        "default": "-",
                        "required": false,
                        "description": "The pci address of the GPU device"
                    },
                ]
            },
        }
    },
    infiniband: {
        "description": "GPU device entries simply make the requested gpu device appear in the instance.",
        "supportedInstanceTypes": ["container"],
        "properties": [
            {
                "name": "nictype",
                "type": "string",
                "default": "-",
                "required": true,
                "description": "The device type, one of “physical”, or “sriov”"
            },
            {
                "name": "name",
                "type": "string",
                "default": "-",
                "required": false,
                "description": "The name of the interface inside the instance"
            },
            {
                "name": "hwaddr",
                "type": "string",
                "default": "-",
                "required": false,
                "description": "The MAC address of the new interface. Can be either full 20 byte variant or short 8 byte variant (which will only modify the last 8 bytes of the parent device)"
            },
            {
                "name": "mtu",
                "type": "integer",
                "default": "-",
                "required": false,
                "description": "The MTU of the new interface"
            },
            {
                "name": "parent",
                "type": "string",
                "default": "-",
                "required": true,
                "description": "The name of the host device or bridge"
            },
        ]
    },
    // proxy: {
    //     //TODO
    // },
    "unix-hotplug": {
        "description": "Unix hotplug device entries make the requested unix device appear in the instance’s /dev and allow read/write operations to it if the device exists on the host system. Implementation depends on systemd-udev to be run on the host.",
        "supportedInstanceTypes": ["container"],
        "properties": [
            {
                "name": "vendorid",
                "type": "string",
                "default": "-",
                "required": false,
                "description": "The vendor id of the unix device"
            },
            {
                "name": "productid",
                "type": "string",
                "default": "-",
                "required": false,
                "description": "The product id of the USB device"
            },
            {
                "name": "uid",
                "type": "int",
                "default": "0",
                "required": false,
                "description": "UID of the device owner in the instance"
            },
            {
                "name": "gid",
                "type": "int",
                "default": "0",
                "required": false,
                "description": "GID of the device owner in the instance"
            },
            {
                "name": "mode",
                "type": "int",
                "default": "0660",
                "required": false,
                "description": "Mode of the device in the instance"
            },
            {
                "name": "required",
                "type": "boolean",
                "default": "false",
                "required": false,
                "description": "Whether or not this device is required to start the instance. (The default is false, and all devices are hot-pluggable)"
            },
        ]
    },
    "tpm": {
        "description": "TPM device entries enable access to a TPM emulator.",
        "supportedInstanceTypes": ["container", "vm"],
        "properties": [
            {
                "name": "path",
                "type": "string",
                "default": "-",
                "required": true,
                "description": "Path inside the instance (only for containers)."
            },
        ]
    },
    "pci": {
        "description": "PCI device entries are used to pass raw PCI devices from the host into a virtual machine.",
        "supportedInstanceTypes": ["vm"],
        "properties": [
            {
                "name": "address",
                "type": "string",
                "default": "-",
                "required": true,
                "description": "PCI address of the device."
            },
        ]
    },
}
