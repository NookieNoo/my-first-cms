CREATE TABLE `article_users` (
    `article_id` SMALLINT(5) UNSIGNED NOT NULL COMMENT 'Ссылка на статью',
    `user_id` INT(11) NOT NULL COMMENT 'Ссылка на автора',
    PRIMARY KEY (`article_id`, `user_id`),
    FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
)
COMMENT='Таблица связи статей и пользователей'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;


