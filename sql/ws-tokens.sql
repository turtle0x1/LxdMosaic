USE `LXD_Manager`;

CREATE TABLE `ws_tokens` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `token` TEXT NOT NULL,
    `used` DATETIME,
    `user_id` INT(11)
);
