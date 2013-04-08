DROP TABLE IF EXISTS `#__pizzabox_schemes`;

ALTER TABLE `#__pizzabox_parts` DROP container_id, DROP scheme_id;
ALTER TABLE `#__pizzabox_flavours` ADD `parts` TEXT AFTER `price`;
ALTER TABLE `#__pizzabox_orders_parts` DROP `scheme_id`, DROP `scheme_name`;

CREATE TABLE IF NOT EXISTS `#__pizzabox_containers_parts` (
	`id`                INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`container_id`      INT(11) NOT NULL,
	`part_id`           INT(11) NOT NULL,
	`minimum`           TINYINT UNSIGNED DEFAULT '1',
	`maximum`           TINYINT UNSIGNED DEFAULT '1'
);
ALTER TABLE `#__pizzabox_containers_parts` ADD UNIQUE (`container_id`, `part_id`);
