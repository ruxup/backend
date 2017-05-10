#!/bin/bash
PASS=root


function command {
mysql_config_editor set --login-path=local --host=localhost --user=root --password
mysql --login-path=local<<MYSQL_SCRIPT
CREATE DATABASE localruxupdb;
GRANT ALL PRIVILEGES ON root.* TO root@'localhost' IDENTIFIED BY '$PASS';
FLUSH PRIVILEGES;
MYSQL_SCRIPT
}

if command ; then
	echo "MySQL Db created."
	echo "Host: localhost"
	echo "Port: 3306"
	echo "Username:   root"
	echo "Password:   your input :)"
	echo "Use these credentials to connect to your local database. (e.g. MySQL Workbench)"
	mysql_config_editor print --all
else
	echo "Command failed."
fi