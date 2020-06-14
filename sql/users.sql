CREATE USER 'lxd'@'localhost' IDENTIFIED WITH mysql_native_password BY 'lxdManagerPasswordComplex321';

GRANT ALL PRIVILEGES ON `LXD_Manager`. * TO 'lxd'@'localhost';
