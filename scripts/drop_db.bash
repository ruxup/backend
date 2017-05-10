#!/bin/bash
PASS=root


function command {
mysql_config_editor set --login-path=local --host=localhost --user=root --password
mysql --login-path=local<<MYSQL_SCRIPT
DROP DATABASE localruxupdb;
MYSQL_SCRIPT
}

if command ; then
	echo "MySQL Db removed."
else
	echo "Command failed."
fi