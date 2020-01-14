
CREATE TABLE `subCategories` (
	`id` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id подкатегории',
	`name` VARCHAR(50) NOT NULL COMMENT 'Название подкатегории',
	`category_id` SMALLINT(5) UNSIGNED NULL DEFAULT NULL COMMENT 'Id категории (ссылка на категорию)',
	PRIMARY KEY (`id`),
	INDEX `category_id` (`category_id`),
	CONSTRAINT `subCategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
