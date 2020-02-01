## When to upgrade

You should only upgrade when a new version is tagged,

Restarting the `pm2` and `apache2` process may interupt running process please
be careful when upgrading!

## 0.5.X -> 0.6.0

```
cd /var/www/LxdMosaic

git fetch

git checkout 0.6.0

mkdir -p /var/www/LxdMosaic/src/sensitiveData/backups

chown -R www-data:www-data /var/www/LxdMosaic/src/sensitiveData/backups

npm i

composer install

mysql < sql/0.6.0.sql

# Required for downloading backups (Ubuntu)
sed -i 's/memory_limit\s*=.*/memory_limit=1024M/g' /etc/php/7.2/apache2/php.ini
# Required for downloading backups (Centos)
sed -i 's/memory_limit\s*=.*/memory_limit=1024M/g' /etc/php.ini

pm2 restart all

#Ubuntu
systemctl restart apache2
#Centos
systemctl restart httpd
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
