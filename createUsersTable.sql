USE cms_biv;
CREATE TABLE users
(
id INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id пользователя',
username VARCHAR(50) NOT NULL  COMMENT 'Логин пользователя',
password VARCHAR(50) NOT NULL COMMENT 'Пароль пользователя',
activityStatus TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Статус активности
 пользователя, по умолчанию = 1, т.е. активен',
PRIMARY KEY (id)
)
COLLATE='utf8_general_ci';

