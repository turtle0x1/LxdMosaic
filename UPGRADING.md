## When to upgrade

You should only upgrade when a new version is tagged,

Restarting the `pm2` and `apache2` / `httpd` process may interupt running
process please be careful when upgrading!

## Between minor releases I.E X.X.0 -> X.X.1

```
# There should never be any DB changes between minor versions & buts its more
# likely there will be dependency changes, the general advise is as follows

cd /var/www/LxdMosaic

git fetch

git checkout vX.X.X

composer install

npm i

pm2 restart all

#Ubuntu/Debian
systemctl restart apache2
#Centos
systemctl restart httpd

```

## 0.10.X -> 0.11.0
```
cd /var/www/LxdMosaic

git fetch

git checkout v0.11.0

npm i

composer install --no-dev

vendor/bin/phinx migrate -e mysql

# New apache config required (dont forget to update any domain names etc)

## Ubuntu
cp examples/lxd_manager.conf /etc/apache2/sites-available/

## Centos
cp examples/lxd_manager_centos.conf /etc/httpd/conf.d/

# Restart node server - be careful may interupt console sessions
pm2 restart all

#Ubuntu
systemctl restart apache2
#Centos
systemctl restart httpd
```

## 0.9.X -> 0.10.0
```
cd /var/www/LxdMosaic

git fetch

git checkout v0.10.0

npm i

composer install --no-dev

# Restart node server - be careful may interupt console sessions
pm2 restart all

#Ubuntu
systemctl restart apache2
#Centos
systemctl restart httpd
```

## 0.8.X -> 0.9.0
```
# Remove all the cron jobs from the users crontab
crontab -r

# Add the new cron tab (this one cronjob covers all cron jobs)
echo "* * * * * www-data cd /var/www/LxdMosaic/ && vendor/bin/crunz schedule:run" >> /etc/crontab

cd /var/www/LxdMosaic

git fetch

git checkout v0.9.0

npm i

composer install

mysql < sql/0.9.0.sql

#Ubuntu
systemctl restart apache2
#Centos
systemctl restart httpd
```

## 0.7.X -> 0.8.0

```
cd /var/www/LxdMosaic

git fetch

git checkout v0.8.0

npm i

composer install

# Important
echo "LXD_CERTS_DIR=/var/www/LxdMosaic/src/sensitiveData/certs/" >> /var/www/LxdMosaic/.env

#Ubuntu
systemctl restart apache2
#Centos
systemctl restart httpd
```

## 0.6.X -> 0.7.0

```
cd /var/www/LxdMosaic

git fetch

git checkout v0.7.0

npm i

composer install

mysql < sql/0.7.0.sql

#Ubuntu
systemctl restart apache2
#Centos
systemctl restart httpd
```

## 0.6.0 -> 0.6.1

```
cd /var/www/LxdMosaic

git fetch

git checkout v0.6.1

```
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
