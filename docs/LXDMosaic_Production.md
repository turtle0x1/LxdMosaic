## LXDMosaic Production Settings

Settings / events operators of LXDMosaic should be aware of when running in
production.

## MySQL Bin Log
_This issue does not impact SNAP users_

Its been reported <a href="https://github.com/turtle0x1/LxdMosaic/issues/465">here</a>
that the MySQL binlog can grow to large unexpected sizes using the default
configuration.

By default the log is cleared every 30 days and most users wont notice the logs
size but if you are impacted, you could increase the frequency the binlog is cleared.
There is a good answer on <a href="https://dba.stackexchange.com/a/41054/81907">Stackexchange</a>
suggesting the procedure to is something like;

```
# Update setting in running mysql
mysql> SET GLOBAL expire_logs_days = 3;

## Update my.cnf to include this
[mysqld]
expire_logs_days=3

```
We aren't database experts but we don't recommend disabling this feature entirely,
it seems unsafe in the event of a power loss. Proceed with caution.
