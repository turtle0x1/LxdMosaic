# # #!/usr/bin/env bash

sudo dpkg --purge --force-all lxd lxc

apt-get update

# # Install Dependecies
apt-get install -y apache2 php php-cli php-json php-mysql php-xml php-curl unzip zip git nodejs openssl || exit $?
apt-get install -y mysql-server || apt-get install -y default-mysql-server || exit $?
apt-get install -y --no-install-recommends cron || exit $?
apt-get install -y --no-install-recommends php-xdebug || exit $?

mkdir -p src/sensitiveData/certs
mkdir -p src/sensitiveData/backups

sudo chmod -R 777 src/sensitiveData/certs
sudo chmod -R 777 src/sensitiveData/backups

npm install || exit $?

# Install Dependecies
/usr/bin/php7.4 composer install || exit $?

cp .env.dist .env

sed -i -e "s/DB_HOST=.*/DB_HOST=localhost/g" .env
## DB User
sed -i -e "s/DB_USER=.*/DB_USER=root/g" .env
## DB Pass
sed -i -e "s/DB_PASS=.*/DB_PASS=root/g" .env

sed -i -e "s/LXD_CERTS_DIR=.*/LXD_CERTS_DIR=\/home\/runner\/work\/LxdMosaic\/LxdMosaic\/src\/sensitiveData\/certs\//g" .env

sudo systemctl start mysql

sudo mysql -u root -proot < sql/seed.sql
sudo mysql -u root -proot < sql/0.1.0.sql
sudo mysql -u root -proot < sql/0.2.0.sql
sudo mysql -u root -proot < sql/0.3.0.sql
sudo mysql -u root -proot < sql/0.5.0.sql
sudo mysql -u root -proot < sql/0.6.0.sql
sudo mysql -u root -proot < sql/0.7.0.sql
sudo mysql -u root -proot < sql/0.9.0.sql

vendor/bin/phinx migrate -e mysql
