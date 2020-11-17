## Reset Password

This document is if for you loose admin access to LXDMosaic

First Access the container / host where LXDMosaic is running

```
# Access the host running LXDMosaic
lxc exec lxdMosaic bash

# Access a php shell
php -a

# Generate a password hash for your new password (keep this for mysql later)
php > echo password_hash("YOUR_NEW_PASSSWORD", PASSWORD_DEFAULT);
# Example output
# $2y$10$ctUEvACAfuVDbh/u23blBur/OLJgVxkiJF65Dy1EA4IOq0xCB8q3a

# Exit the php shell
php > exit

# Access a mysql shell
mysql -u root

# Access the database
mysql > use LXD_Manager;

# Find all your users / user id - look for the userId you want to update
mysql > select `User_ID`, `User_Name` from `Users`

# +---------+-----------+
# | User_ID | User_Name |
# +---------+-----------+
# | 1       | admin     |
# +---------+-----------+

mysql > update `user` set `User_Password` = "PASSWORD_HASH_FROM_ABOVE" where `User_ID` = USER_ID_FROM_ABOVE;

# Example end result
# mysql > update `Users` set `User_Password` = "$2y$10$ctUEvACAfuVDbh/u23blBur/OLJgVxkiJF65Dy1EA4IOq0xCB8q3a" where `User_ID` = 1;
```
