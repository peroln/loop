CREATE DATABASE IF NOT EXISTS show_memory_db;

-- create the users for each database
CREATE USER 'show_memory_user'@'%' IDENTIFIED BY 't2HTv4wUbK7JVTT665Y3h';

GRANT ALL PRIVILEGES ON *.* TO 'show_memory_user'@'%' WITH GRANT OPTION;

FLUSH PRIVILEGES;

