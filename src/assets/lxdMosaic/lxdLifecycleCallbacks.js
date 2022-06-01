var lifecycleCallbackTemplates = {
    instanceStateChange:  function(message, newState){
        let currentLocation = router.getCurrentLocation().url
        if(currentLocation.substr(0, 8) == "instance" || currentLocation == ""){
            let instanceName = message.metadata.source.replace("/1.0/instances/", "");
            let hostUl = $("#sidebar-ul").find("#host-" + message.hostId).find(`[data-container=${instanceName}]`).find("i:eq(0)").replaceWith(`<i class="nav-icon me-1 ${statusCodeIconMap[newState]}" style="min-width: 20px;"></i>`)
        }
    }
}
var lifecycleCallbacks = {
    // "certificate-created": function(){//TODO}
    // "certificate-deleted": function(){//TODO}
    // "certificate-updated": function(){//TODO}
    // "cluster-certificate-updated": function(){//TODO}
    // "cluster-disabled": function(){//TODO}
    // "cluster-enabled": function(){//TODO}
    // "cluster-member-added": function(){//TODO}
    // "cluster-member-removed": function(){//TODO}
    // "cluster-member-renamed": function(){//TODO}
    // "cluster-member-updated": function(){//TODO}
    // "cluster-token-created": function(){//TODO}
    // "config-updated": function(){//TODO}
    // "image-alias-created": function(){//TODO}
    // "image-alias-deleted": function(){//TODO}
    // "image-alias-renamed": function(){//TODO}
    // "image-alias-updated": function(){//TODO}
    // "image-created": function(){//TODO}
    // "image-deleted": function(){//TODO}
    // "image-refreshed": function(){//TODO}
    // "image-retrieved": function(){//TODO}
    // "image-secret-created": function(){//TODO}
    // "image-updated": function(){//TODO}
    // "instance-backup-created": function(){//TODO}
    // "instance-backup-deleted": function(){//TODO}
    // "instance-backup-renamed": function(){//TODO}
    // "instance-backup-retrieved": function(){//TODO}
    // "instance-console": function(){//TODO}
    // "instance-console-reset": function(){//TODO}
    // "instance-console-retrieved": function(){//TODO}
    // "instance-created": function(){//TODO}
    // "instance-deleted": function(){//TODO}
    // "instance-exec": function(){//TODO}
    // "instance-file-deleted": function(){//TODO}
    // "instance-file-pushed": function(){//TODO}
    // "instance-file-retrieved": function(){//TODO}
    // "instance-log-deleted": function(){//TODO}
    // "instance-log-retrieved": function(){//TODO}
    // "instance-metadata-retrieved": function(){//TODO}
    // "instance-metadata-updated": function(){//TODO}
    // "instance-metadata-template-created": function(){//TODO}
    // "instance-metadata-template-deleted": function(){//TODO}
    // "instance-metadata-template-retrieved": function(){//TODO}
    // "instance-paused": function(){//TODO}
    // "instance-renamed": function(){//TODO}
    // "instance-restarted": function(){//TODO}
    // "instance-restored": function(){//TODO}
    // "instance-resumed": function(){//TODO}
    "instance-shutdown": (message) => lifecycleCallbackTemplates.instanceStateChange(message, 102),
    "instance-started": (message) => lifecycleCallbackTemplates.instanceStateChange(message, 103),
    "instance-stopped": (message) => lifecycleCallbackTemplates.instanceStateChange(message, 102),
    // "instance-updated": function(){//TODO}
    // "instance-snapshot-created": function(){//TODO}
    // "instance-snapshot-deleted": function(){//TODO}
    // "instance-snapshot-renamed": function(){//TODO}
    // "instance-snapshot-updated": function(){//TODO}
    // "network-acl-created": function(){//TODO}
    // "network-acl-deleted": function(){//TODO}
    // "network-acl-renamed": function(){//TODO}
    // "network-acl-updated": function(){//TODO}
    "network-created": function(message){
        if(router.getCurrentLocation().url.substr(0, 8) == "networks"){
            let hostUl = $("#sidebar-ul").find("[data-host-id='" + message.hostId + "']")
            let networkName = message.metadata.source.replace("/1.0/networks/", "");
            hostUl.find(".hostContentList").append(`<li class="nav-item">
              <a class="nav-link" href="/networks/${hostIdOrAliasForUrl(message.hostAlias, message.hostId)}/${networkName}" data-navigo>
                <i class="fas fa-ethernet"></i>
                ${networkName}
              </a>
            </li>`)
            router.updatePageLinks()
        }
    },
    "network-deleted": function(message){
        if(router.getCurrentLocation().url.substr(0, 8) == "networks"){
            let hostUl = $("#sidebar-ul").find("[data-host-id='" + message.hostId + "']")
            let networkName = message.metadata.source.replace("/1.0/networks/", "");
            hostUl.find(".hostContentList").find(`[href="/networks/${hostIdOrAliasForUrl(message.hostAlias, message.hostId)}/${networkName}"]`).parent().remove()
            router.updatePageLinks()
        }
    },
    "network-renamed": function(message){
        if(router.getCurrentLocation().url.substr(0, 8) == "networks"){
            let hostUl = $("#sidebar-ul").find("[data-host-id='" + message.hostId + "']")
            let oldName = message.metadata.context.old_name.replace("/1.0/networks/", "");
            let newName = message.metadata.source.replace("/1.0/networks/", "");
            let x = hostUl.find(".hostContentList").find(`[href="/networks/${hostIdOrAliasForUrl(message.hostAlias, message.hostId)}/${oldName}"]`)
            x.attr("href", `/networks/${hostIdOrAliasForUrl(message.hostAlias, message.hostId)}/${newName}`)
            x.html(`<i class="fas fa-ethernet me-2"></i>${newName}`)
            router.updatePageLinks()
        }
    },
    // "network-updated": function(){//TODO}
    // "operation-cancelled": function(){//TODO}
    "profile-created": function(message){
        if(router.getCurrentLocation().url.substr(0, 8) == "profiles"){
            let hostUl = $("#sidebar-ul").find("[data-host-id='" + message.hostId + "']")
            let profileName = message.metadata.source.replace("/1.0/profiles/", "");
            hostUl.find(".hostContentList").append(`<li class="nav-item">
              <a class="nav-link" href="/profiles/${hostIdOrAliasForUrl(message.hostAlias, message.hostId)}/${profileName}" data-navigo>
                <i class="nav-icon fa fa-user"></i>
                ${profileName}
              </a>
            </li>`)
            router.updatePageLinks()
        }
    },
    "profile-deleted": function(message){
        if(router.getCurrentLocation().url.substr(0, 8) == "profiles"){
            let hostUl = $("#sidebar-ul").find("[data-host-id='" + message.hostId + "']")
            let profileName = message.metadata.source.replace("/1.0/profiles/", "");
            hostUl.find(".hostContentList").find(`[href="/profiles/${hostIdOrAliasForUrl(message.hostAlias, message.hostId)}/${profileName}"]`).parent().remove()
            router.updatePageLinks()
        }
    },
    "profile-renamed": function(message){
        if(router.getCurrentLocation().url.substr(0, 8) == "profiles"){
            let hostUl = $("#sidebar-ul").find("[data-host-id='" + message.hostId + "']")
            let oldName = message.metadata.context.old_name.replace("/1.0/profiles/", "");
            let newName = message.metadata.source.replace("/1.0/profiles/", "");
            let x = hostUl.find(".hostContentList").find(`[href="/profiles/${hostIdOrAliasForUrl(message.hostAlias, message.hostId)}/${oldName}"]`)
            x.attr("href", `/profiles/${hostIdOrAliasForUrl(message.hostAlias, message.hostId)}/${newName}`)
            x.html(`<i class="fas fa-user me-2"></i>${newName}`)
            router.updatePageLinks()
        }
    },
    // "profile-updated": function(){//TODO}
    // "project-created": function(){//TODO}
    // "project-deleted": function(){//TODO}
    // "project-renamed": function(){//TODO}
    // "project-updated": function(){//TODO}
    "storage-pool-created": function(message){
        if(router.getCurrentLocation().url.substr(0, 7) == "storage"){
            let hostUl = $("#sidebar-ul").find("[data-host-id='" + message.hostId + "']")
            let profileName = message.metadata.source.replace("/1.0/storage-pools/", "");
            hostUl.find(".hostContentList").append(`<li class="nav-item">
              <a class="nav-link" href="/storage/${hostIdOrAliasForUrl(message.hostAlias, message.hostId)}/${profileName}" data-navigo>
                <i class="nav-icon fa fa-hdd"></i>
                ${profileName}
              </a>
            </li>`)
            router.updatePageLinks()
        }
    },
    "storage-pool-deleted": function(message){
        if(router.getCurrentLocation().url.substr(0, 7) == "storage"){
            let hostUl = $("#sidebar-ul").find("[data-host-id='" + message.hostId + "']")
            let profileName = message.metadata.source.replace("/1.0/storage-pools/", "");
            hostUl.find(".hostContentList").find(`[href="/storage/${hostIdOrAliasForUrl(message.hostAlias, message.hostId)}/${profileName}"]`).parent().remove()
            router.updatePageLinks()
        }
    },
    // "storage-pool-updated": function(){//TODO}
    // "storage-volume-backup-created": function(){//TODO}
    // "storage-volume-backup-deleted": function(){//TODO}
    // "storage-volume-backup-renamed": function(){//TODO}
    // "storage-volume-backup-retrieved": function(){//TODO}
    // "storage-volume-created": function(){//TODO}
    // "storage-volume-deleted": function(){//TODO}
    // "storage-volume-renamed": function(){//TODO}
    // "storage-volume-restored": function(){//TODO}
    // "storage-volume-updated": function(){//TODO}
    // "storage-volume-snapshot-created": function(){//TODO}
    // "storage-volume-snapshot-deleted": function(){//TODO}
    // "storage-volume-snapshot-renamed": function(){//TODO}
    // "storage-volume-snapshot-updated": function(){//TODO}
    // "warning-acknowledged": function(){//TODO}
    // "warning-deleted": function(){//TODO}
    // "warning-reset": function(){//TODO}
}
