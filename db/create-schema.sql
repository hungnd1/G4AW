create database g4aw default charset utf8 collate utf8_general_ci;
grant all privileges on vndonor.* to 'g4aw'@'localhost' identified by '';
grant all privileges on vndonor.* to 'g4aw'@'%' identified by '';
