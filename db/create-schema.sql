create database vndonor default charset utf8 collate utf8_general_ci;
grant all privileges on vndonor.* to 'vndonor'@'localhost' identified by 'vndonor@123';
grant all privileges on vndonor.* to 'vndonor'@'%' identified by 'vndonor@123';
