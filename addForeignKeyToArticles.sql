ALTER TABLE `articles`
 ADD `subCategory_id` SMALLINT(5) UNSIGNED NULL DEFAULT NULL
 COMMENT 'Id подкатегории (ссылка на подкатегорию)' 
; 


ALTER TABLE `articles`
 ADD FOREIGN KEY (`subCategory_id`) REFERENCES `subCategories` (`id`)
;