#!/bin/bash

mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" -e "CREATE DATABASE IF NOT EXISTS sim_akademik CHARACTER SET utf8mb4;"
mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" -e "GRANT ALL PRIVILEGES ON sim_akademik.* TO '${MYSQL_USER}'@'%'; FLUSH PRIVILEGES;"
mysql --force -uroot -p"${MYSQL_ROOT_PASSWORD}" sim_akademik < /opt/sql/sim_akademik.sql
