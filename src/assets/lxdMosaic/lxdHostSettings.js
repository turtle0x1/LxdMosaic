var hostLxdSettings = {
    "backups.compression_algorithm": {
        type: "string",
        scope: "global",
        default: "gzip",
        description: "Compression algorithm to use for new images (bzip2, gzip, lzma, xz or none)"
    },
    "candid.api.key": {
        type: "string",
        scope: "global",
        default: "-",
        description: "Public key of the candid server (required for HTTP-only servers)"
    },
    "candid.api.url": {
        type: "string",
        scope: "global",
        default: "-",
        description: "URL of the the external authentication endpoint using Candid"
    },
    "candid.domains": {
        type: "string",
        scope: "global",
        default: "-",
        description: "Comma-separated list of allowed Candid domains (empty string means all domains are valid)"
    },
    "candid.expiry": {
        type: "integer",
        scope: "global",
        default: "3600",
        description: "Candid macaroon expiry in seconds"
    },
    "cluster.https_address": {
        type: "string",
        scope: "local",
        default: "-",
        description: "Address to use for clustering traffic"
    },
    "cluster.images_minimal_replica": {
        type: "integer",
        scope: "global",
        default: "3",
        description: "Minimal numbers of cluster members with a copy of a particular image (set 1 for no replication, -1 for all members)"
    },
    "cluster.max_standby": {
        type: "integer",
        scope: "global",
        default: "2",
        description: "Maximum number of cluster members that will be assigned the database stand-by role"
    },
    "cluster.max_voters": {
        type: "integer",
        scope: "global",
        default: "3",
        description: "Maximum number of cluster members that will be assigned the database voter role"
    },
    "cluster.offline_threshold": {
        type: "integer",
        scope: "global",
        default: "20",
        description: "Number of seconds after which an unresponsive node is considered offline"
    },
    "core.bgp_address": {
        type: "string",
        scope: "local",
        default: "-",
        description: "Address to bind the BGP server to (BGP)"
    },
    "core.bgp_asn": {
        type: "string",
        scope: "global",
        default: "-",
        description: "The BGP Autonomous System Number to use for the local server"
    },
    "core.bgp_routerid": {
        type: "string",
        scope: "local",
        default: "-",
        description: "A unique identifier for this BGP server (formatted as an IPv4 address)"
    },
    "core.debug_address": {
        type: "string",
        scope: "local",
        default: "-",
        description: "Address to bind the pprof debug server to (HTTP)"
    },
    "core.dns_address": {
        type: "string",
        scope: "local",
        default: "-",
        description: "Address to bind the authoritative DNS server to (DNS)"
    },
    "core.https_address": {
        type: "string",
        scope: "local",
        default: "-",
        description: "Address to bind for the remote API (HTTPS)"
    },
    "core.https_allowed_credentials": {
        type: "bool",
        scope: "global",
        default: "-",
        description: "Whether to set Access-Control-Allow-Credentials HTTP header value to true"
    },
    "core.https_allowed_headers": {
        type: "string",
        scope: "global",
        default: "-",
        description: "Access-Control-Allow-Headers HTTP header value"
    },
    "core.https_allowed_methods": {
        type: "string",
        scope: "global",
        default: "-",
        description: "Access-Control-Allow-Methods HTTP header value"
    },
    "core.https_allowed_origin": {
        type: "string",
        scope: "global",
        default: "-",
        description: "Access-Control-Allow-Origin HTTP header value"
    },
    "core.https_trusted_proxy": {
        type: "string",
        scope: "global",
        default: "-",
        description: "Comma-separated list of IP addresses of trusted servers to provide the client’s address through the proxy connection header"
    },
    "core.metrics_address": {
        type: "string",
        scope: "global",
        default: "-",
        description: "Address to bind the metrics server to (HTTPS)"
    },
    "core.metrics_authentication": {
        type: "bool",
        scope: "global",
        default: "true",
        description: "Whether to enforce authentication on the metrics endpoint"
    },
    "core.proxy_https": {
        type: "string",
        scope: "global",
        default: "-",
        description: "HTTPS proxy to use, if any (falls back to HTTPS_PROXY environment variable)"
    },
    "core.proxy_http": {
        type: "string",
        scope: "global",
        default: "-",
        description: "HTTP proxy to use, if any (falls back to HTTP_PROXY environment variable)"
    },
    "core.proxy_ignore_hosts": {
        type: "string",
        scope: "global",
        default: "-",
        description: "Hosts which don’t need the proxy for use (similar format to NO_PROXY, e.g. 1.2.3.4,1.2.3.5, falls back to NO_PROXY environment variable)"
    },
    "core.shutdown_timeout": {
        type: "integer",
        scope: "global",
        default: "5",
        description: "Number of minutes to wait for running operations to complete before LXD server shut down"
    },
    "core.storage_buckets_address": {
        type: "string",
        scope: "local",
        default: "-",
        description: "Address to bind the storage object server to (HTTPS)"
    },
    "core.trust_ca_certificates": {
        type: "bool",
        scope: "global",
        default: "-",
        description: "Whether to automatically trust clients signed by the CA"
    },
    "core.trust_password": {
        type: "string",
        scope: "global",
        default: "-",
        description: "Password to be provided by clients to set up a trust"
    },
    "images.auto_update_cached": {
        type: "bool",
        scope: "global",
        default: "true",
        description: "Whether to automatically update any image that LXD caches"
    },
    "images.auto_update_interval": {
        type: "integer",
        scope: "global",
        default: "6",
        description: "Interval in hours at which to look for update to cached images (0 disables it)"
    },
    "images.compression_algorithm": {
        type: "string",
        scope: "global",
        default: "gzip",
        description: "Compression algorithm to use for new images (bzip2, gzip, lzma, xz or none)"
    },
    "images.default_architecture": {
        type: "string",
        scope: "-",
        default: "-",
        description: "Default architecture which should be used in mixed architecture cluster",
    },
    "images.remote_cache_expiry": {
        type: "integer",
        scope: "global",
        default: "10",
        description: "Number of days after which an unused cached remote image will be flushed"
    },
    "instances.nic.host_name": {
        type: "string",
        scope: "global",
        default: "random",
        description: "If it is set to random then use the random host interface names but if it’s set to mac, then generate a name in the form lxd<mac_address>(MAC without leading 2 digits)."
    },
    "maas.api.key": {
        type: "string",
        scope: "global",
        default: "-",
        description: "API key to manage MAAS"
    },
    "maas.api.url": {
        type: "string",
        scope: "global",
        default: "-",
        description: "URL of the MAAS server"
    },
    "maas.machine": {
        type: "string",
        scope: "local",
        default: "host name",
        description: "Name of this LXD host in MAAS"
    },
    "network.ovn.integration_bridge": {
        type: "string",
        scope: "global",
        default: "br-int",
        description: "OVS integration bridge to use for OVN networks"
    },
    "network.ovn.northbound_connection": {
        type: "string",
        scope: "global",
        default: "unix:/var/run/ovn/ovnnb_db.sock",
        description: "OVN northbound database connection string"
    },
    "rbac.agent.private_key": {
        type: "string",
        scope: "global",
        default: "-",
        description: "The Candid agent private key as provided during RBAC registration"
    },
    "rbac.agent.public_key": {
        type: "string",
        scope: "global",
        default: "-",
        description: "The Candid agent public key as provided during RBAC registration"
    },
    "rbac.agent.url": {
        type: "string",
        scope: "global",
        default: "-",
        description: "The Candid agent URL as provided during RBAC registration"
    },
    "rbac.agent.username": {
        type: "string",
        scope: "global",
        default: "-",
        description: "The Candid agent username as provided during RBAC registration"
    },
    "rbac.api.expiry": {
        type: "integer",
        scope: "global",
        default: "-",
        description: "RBAC macaroon expiry in seconds"
    },
    "rbac.api.key": {
        type: "string",
        scope: "global",
        default: "-",
        description: "Public key of the RBAC server (required for HTTP-only servers)"
    },
    "rbac.api.url": {
        type: "string",
        scope: "global",
        default: "-",
        description: "URL of the external RBAC server"
    },
    "storage.backups_volume": {
        type: "string",
        scope: "local",
        default: "-",
        description: "Volume to use to store the backup tarballs (syntax is POOL/VOLUME)"
    },
    "storage.images_volume": {
        type: "string",
        scope: "local",
        default: "-",
        description: "Volume to use to store the image tarballs (syntax is POOL/VOLUME)"
    },
}
