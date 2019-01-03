#!/usr/bin/env bash
error() {
  printf '\E[31m'; echo "$@"; printf '\E[0m'
}

if [[ !$EUID -eq 0 ]]; then
    error "This script should be run using sudo or as the root user"
    exit 1
fi

# Install Dependecies
apt-get install apache2 php7.2 php7.2-cli php7.2-json php7.2-mysql php7.2-dom unzip zip mysql-server git -y

# Install composer

##Download composer
curl -sS https://getcomposer.org/installer -o composer-setup.php
## Install
php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Move to www & clone repository
cd /var/www

git clone https://github.com/turtle0x1/LxdManager.git

mkdir -p /var/www/LxdManager/src/sensitiveData/certs
chown -R www-data:www-data /var/www/LxdManager/src/sensitiveData/certs

# Move in LxdManager
cd /var/www/LxdManager

# Install Dependecies
composer install

cp .env.dist .env

# Update env values
## DB Host
sed -i -e 's/DB_HOST=/DB_HOST=localhost/g' .env
## DB User
sed -i -e 's/DB_USER=/DB_USER=lxd/g' .env
## DB Pass
sed -i -e 's/DB_PASS=/DB_PASS=lxdManagerPasswordComplex321/g' .env

# Import data into mysql
mysql < sql/users.sql
mysql < sql/seed.sql

cp examples/lxd_manager.conf /etc/apache2/sites-available/

# Enable required apache mods
a2enmod ssl
a2enmod headers
a2enmod rewrite

# Enable site
a2ensite lxd_manager

systemctl restart apache2

echo -e "\033[32mInstallation successfull you should now point your browser at https://this_hosts_ip_address \n"
echo -e  "  or \n\nyou could add lxd.local to your hosts file (on your pc) E.G \n"
echo -e  "  this_hosts_ip_address lxd.local \n"
echo -e  "ServerName for LxdManager can be changed in /etc/apache2/sites-available/lxd_manager.conf, followed by an apache restart (systemctl restart apache2) \n"
