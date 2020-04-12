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

curl -sL https://rpm.nodesource.com/setup_10.x | bash -

# Install remi repo
yum install -y http://rpms.remirepo.net/enterprise/remi-release-7.rpm

# Install mariadb 10.4 repo
cat >/etc/yum.repos.d/MariaDB.repo<<EOF
[mariadb]
name = MariaDB
baseurl = http://yum.mariadb.org/10.4/centos7-amd64
gpgkey=https://yum.mariadb.org/RPM-GPG-KEY-MariaDB
gpgcheck=1
EOF

# Install Dependecies
yum --enablerepo=remi-safe,remi-php72 install -y httpd php php-cli php-json php-mysqlnd php-dom php-curl unzip zip nodejs git mod_ssl MariaDB-server MariaDB-compat

# Enable apache and mariadb, start mariadb
systemctl enable httpd
systemctl enable mariadb
systemctl start mariadb

# install pm2
npm -g install pm2

#Download composer
curl -sS https://getcomposer.org/installer -o composer-setup.php
# Install
php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Move to www & clone repository
cd /var/www || exit

git clone https://github.com/turtle0x1/LxdMosaic.git

mkdir -p /var/www/LxdMosaic/src/sensitiveData/certs
mkdir -p /var/www/LxdMosaic/src/sensitiveData/backups
chown -R apache:apache /var/www/LxdMosaic/src/sensitiveData/
chown -R www-data:www-data /var/www/LxdMosaic/src/sensitiveData/backups

# Move in LxdManager
cd /var/www/LxdMosaic || exit

git checkout v0.7.1

npm install

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
mysql < sql/0.1.0.sql
mysql < sql/0.2.0.sql
mysql < sql/0.3.0.sql
mysql < sql/0.5.0.sql
mysql < sql/0.6.0.sql
mysql < sql/0.7.0.sql


# Install a self-signed certificate as CentOS doesn't ship with one
mkdir -p /etc/ssl/private
openssl req -new -newkey rsa:2048 -days 3650 -nodes -x509 \
            -subj "/C=NA/ST=NoState/L=NoLocale/O=Blah/CN=mosaic" \
            -keyout /etc/ssl/private/ssl-cert-snakeoil.key  -out /etc/ssl/certs/ssl-cert-snakeoil.pem

cp examples/lxd_manager_centos.conf /etc/httpd/conf.d/

pm2 start node/events.js

pm2 startup

pm2 save

# Add cron job for gathering data
crontab -l | { cat; echo "*/5 * * * * php /var/www/LxdMosaic/src/cronJobs/fleetAnalytics.php"; } | crontab -
crontab -l | { cat; echo "*/1 * * * * php /var/www/LxdMosaic/src/cronJobs/hostsOnline.php"; } | crontab -

systemctl restart httpd

printf "${grn}\nInstallation successfull \n\n"
printf  "${grn}Point your browser at ${blu}https://myip${end} ${red}and accept the self signed certificate${end} \n"
printf  "${grn} \n or \n\nyou could add lxd.local to your hosts file (on your pc) E.G \n"
printf  " \n myip lxd.local \n\n"
printf  "ServerName for LxdManager can be changed in /etc/httpd/conf.d/lxd_manager_centos.conf, followed by an apache restart (systemctl restart httpd) \n${end}"
