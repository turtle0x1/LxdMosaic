## LXDMosaic Settings


## Instance IP

Value: IP Address / Domain name

The IP address / domain LXDMosaic is available on, if you intend to use
deployments you will need to fill in this setting to enable "phoned home"
functionality

## Record Actions

Value: 1 or 0

Whether to record user actions like create, starting & stoping containers.
Enabling this setting will enable more UI elements in the future (like event logs
for instances)

## Backup Directory

Value: Path accessable to LXDMosaic to write & read from

If you intend to use LXDMosaic to store your backups it will need access to
a local file system path to store them in, place the path to store your instances
here!

The final backup tree will look something like;

    $hostId/$project/$instance/backup.tar.gz

## LDAP

### Ldap Server

Given in the form of a PHP ldap string I.E;

 ldap://IP_ADDRESS:389
 ldaps://IP_ADDRESS:636

### Ldap Lookup User DN

This can either either be a specially crafted user or the admin account, we use
this account to perform searchs when importing users shoud look something like;

`cn=administrator,cn=Users,dc=example,dc=com`

### Ldap Lookup User Password

The password for the lookup user

### Ldap Base DN

The place in the LDAP structure we look for users (objectClass=person is the
search filter for "users to import")

Should look something like;

`ou=Users,dc=example,dc=com`

#### Importing users

We sync your users every hour, you can force a sync of users in the UI but
you shouldn't sync users close to the hour mark I.E dont start syncing at 14:58

Having two imports running at the same time shouldn't cause any real issues on
small systems, but initial imports of large directories might end up with
duplicated users!

It took 4m33.668s to import 4000~ users from a samba ldap directory, I susspect
this time could be bought down even further by using bulk inserts, if you run
the script again after importing 4000 users it completes in 0m0.279s so its
entirely down to inserting! (not samba or lookups!)
