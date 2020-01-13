USE cms_biv;
CREATE TABLE subCategory
(
    id smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id подкатегории',
    name VARCHAR(50) NOT NULL  COMMENT 'Название подкатегории',
    category_id  smallint(5)  UNSIGNED  COMMENT 'Id категории (ссылка на категорию)',
    PRIMARY KEY (id),
    FOREIGN KEY (category_id) REFERENCES categories(id) 
)
ENGINE=InnoDB
;