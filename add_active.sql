USE cms_biv;
ALTER TABLE `articles`
 ADD `active` TINYINT(1) NOT NULL DEFAULT '0' 
COMMENT 'Поле видимости статьи. По умолчанию, =0, статья не видима/опубликована.'; 

