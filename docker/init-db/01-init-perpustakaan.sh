#!/bin/bash

mysql --force -uroot -p"${MYSQL_ROOT_PASSWORD}" sim_perpustakaan < /opt/sql/sim_perpustakaan.sql
