CREATE TABLE `resources` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `resource_type_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `hash` CHAR(128) UNIQUE NOT NULL,
    `value` TEXT DEFAULT NULL,
    `is_read` TINYINT(1) DEFAULT NULL,
    `created` DATETIME NOT NULL
)ENGINE=MyISAM;

CREATE TABLE `resource_types` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` CHAR(32) NOT NULL
)ENGINE=MyISAM;

INSERT INTO `resource_types` ( `name` ) VALUES
( 'data-layer' )
;