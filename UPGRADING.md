## When to upgrade

You should only upgrade when a new version is tagged,

Restarting the `pm2` and `apache2` process may interupt running process please
be careful when upgrading!

## 0.5.0 -> 0.5.1

```
cd /var/www/LxdMosaic

git fetch

git checkout 0.5.1

```

## 0.4.0 -> 0.5.0

The upgrade path is as follows for both ubuntu & centos

```
cd /var/www/LxdMosaic

git pull

mysql < 0.5.0.sql

# This is new but recomended
git checkout 0.5.0

```


## 0.3.0 -> 0.4.0

The upgrade path is as follows for both ubuntu & centos

```
cd /var/www/LxdMosaic

git pull
```

## 0.2.0 -> 0.3.0

The upgrade path is as follows

### Ubuntu
```
cd /var/www/LxdMosaic

git pull

mysql < sql/0.3.0.sql

pm2 restart all
systemctl restart apache2
```

### Centos
```
cd /var/www/LxdMosaic

git pull

mysql < sql/0.3.0.sql

pm2 restart all
systemctl restart httpd
```

## 0.1.0 -> 0.2.0

The upgrade path is as follows

### Ubuntu
```
cd /var/www/LxdMosaic

git pull

mysql < sql/0.2.0.sql

pm2 restart all
systemctl restart apache2
```

### Centos
```
cd /var/www/LxdMosaic

git pull

mysql < sql/0.2.0.sql

pm2 restart all
systemctl restart httpd
```

## [Below version 0.1.0]

Versions below 0.1.0 should re-install
