#!/usr/bin/env bash

error() {
  printf '\E[31m'; echo "$@"; printf '\E[0m'
}

red=$'\e[1;31m'
grn=$'\e[1;32m'
blu=$'\e[1;34m'
end=$'\e[0m'

if [[ ! $EUID -eq 0 ]]; then
    error "This script should be run using sudo or as the root user"
    exit 1
fi

os_distribution=`lsb_release -c -s`

apt-get install -y curl || exit $?

if [ "$os_distribution" != "bullseye" ]
then
    curl -sL https://deb.nodesource.com/setup_10.x | bash -
    if [ $? -ne 0 ]; then exit 1; fi
fi


# Install Dependecies
apt-get install -y apache2 php php-cli php-json php-mysql php-xml php-curl unzip zip git nodejs openssl || exit $?
apt-get install -y mysql-server || apt-get install -y default-mysql-server || exit $?
apt-get install -y --no-install-recommends cron || exit $?

if [ "$os_distribution" == "bullseye" ]
then
    apt-get install -y npm || exit $?
fi

if [ "$(uname -m)" == "aarch64" ] || [ "$(uname -m)" == "armv7l" ]
then
    apt-get install -y gcc g++ make python2.7 || exit $?
    update-alternatives --install /usr/bin/python python /usr/bin/python2.7 1
fi

npm -g install pm2 || exit $?

# Install composer

##Download composer
curl -sS https://getcomposer.org/installer -o composer-setup.php || exit $?
## Install
php composer-setup.php --install-dir=/usr/local/bin --filename=composer || exit $?

## Move the default apache files
a2dissite 000-default
a2dissite default-ssl

# Enable required apache mods
a2enmod ssl
a2enmod headers
a2enmod rewrite
a2enmod proxy
a2enmod proxy_wstunnel
a2enmod proxy_wstunnel
a2enmod proxy_http
a2enmod proxy_balancer

# Move to www & clone repository
cd /var/www || exit

git clone https://github.com/turtle0x1/LxdMosaic.git || exit $?

mkdir -p /var/www/LxdMosaic/src/sensitiveData/certs
mkdir -p /var/www/LxdMosaic/src/sensitiveData/backups
chown -R www-data:www-data /var/www/LxdMosaic/src/sensitiveData/
chown -R www-data:www-data /var/www/LxdMosaic/src/sensitiveData/backups

# Let PHP use as much memory as it wants within apache, typically for backups (#249)
if test -f "/etc/php/7.4/apache2/php.ini"; then
    sed -i -e "s/memory_limit = .*/memory_limit = -1/g" /etc/php/7.4/apache2/php.ini
elif test -f "/etc/php/7.2/apache2/php.ini"; then
    sed -i -e "s/memory_limit = .*/memory_limit = -1/g" /etc/php/7.2/apache2/php.ini
fi

# Move in LxdManager
cd /var/www/LxdMosaic || exit

git checkout v0.11.3

npm install || exit $?

# Install Dependecies
export COMPOSER_ALLOW_SUPERUSER=1; composer install --no-dev || exit $?

PASSWD=`openssl rand -base64 29 | tr -d "=+/" | cut -c1-25`

cp .env.dist .env
# Update env values
## DB Host
sed -i -e "s/DB_HOST=.*/DB_HOST=localhost/g" .env
## DB User
sed -i -e "s/DB_USER=.*/DB_USER=lxd/g" .env
## DB Pass
sed -i -e "s/DB_PASS=.*/DB_PASS=$PASSWD/g" .env

os_name=$(cat /etc/os-release | awk -F '=' '/^NAME/{print $2}' | awk '{print $1}' | tr -d '"')

MYSQL_PASSWD_STRING="IDENTIFIED BY '$PASSWD'"
# Ubuntu uses "real" mysql and in version 20.04 the use mysql 8.0 which changes
# the default password scheme used - which at time of writing the node client
# is not compatible with and so this is required to ensure LXDMosaic installs
# proprerly
if [ "$os_name" == "Ubuntu" ]
then
    MYSQL_PASSWD_STRING="IDENTIFIED WITH mysql_native_password BY '$PASSWD'"
fi

# Import data into mysql
mysql <<EOF
CREATE USER 'lxd'@'localhost' $MYSQL_PASSWD_STRING;
GRANT ALL PRIVILEGES ON \`LXD_Manager\`. * TO 'lxd'@'localhost';
EOF
mysql < sql/seed.sql
mysql < sql/0.1.0.sql
mysql < sql/0.2.0.sql
mysql < sql/0.3.0.sql
mysql < sql/0.5.0.sql
mysql < sql/0.6.0.sql
mysql < sql/0.7.0.sql
mysql < sql/0.9.0.sql

vendor/bin/phinx migrate -e mysql

PASSWD=""

cp examples/lxd_manager.conf /etc/apache2/sites-available/

pm2 start node/events.js || exit $?

pm2 startup || exit $?

pm2 save || exit $?

if [ ! -f /etc/crontab ]; then
    touch /etc/crontab
fi
# Add cron job for gathering data
echo "* * * * * www-data cd /var/www/LxdMosaic/ && vendor/bin/crunz schedule:run" >> /etc/crontab

# Enable site
a2ensite lxd_manager

systemctl restart apache2 || exit $?

IP=`ip route get 1 | sed -n 's/^.*src \([0-9.]*\) .*$/\1/p'`
printf "${grn}\nInstallation successfull \n\n"
printf  "You now need to point your browser at ${blu}https://$IP${end} ${grn}and accept the self signed certificate${end} \n\n"
printf  "ServerName for LxdManager can be changed in /etc/apache2/sites-available/lxd_manager.conf, followed by an apache restart (systemctl restart apache2) \n\n${end}"
