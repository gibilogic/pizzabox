CREATE TABLE IF NOT EXISTS `#__pizzabox_containers` (
  `id`                INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`              VARCHAR(50) NOT NULL,
  `desc`              TEXT,
  `image`             VARCHAR(255),
  `price`             DECIMAL(8,2),
  `ordering`          INT(11) DEFAULT '0',
  `checked_out`       INT(11) DEFAULT '0',
  `checked_out_time`  DATETIME DEFAULT '0000-00-00 00:00:00',
  `published`         TINYINT(1) DEFAULT '1'
);

CREATE TABLE IF NOT EXISTS `#__pizzabox_parts` (
  `id`                INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`              VARCHAR(50) NOT NULL,
  `desc`              TEXT,
  `image`             VARCHAR(255),
  `price`             DECIMAL(8,2),
  `ordering`          INT(11) DEFAULT '0',
  `checked_out`       INT(11) DEFAULT '0',
  `checked_out_time`  DATETIME DEFAULT '0000-00-00 00:00:00',
  `published`         TINYINT(1) DEFAULT '1'
);

CREATE TABLE IF NOT EXISTS `#__pizzabox_containers_parts` (
	`id`                INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`container_id`      INT(11) NOT NULL,
	`part_id`           INT(11) NOT NULL,
	`minimum`           TINYINT UNSIGNED DEFAULT '1',
	`maximum`           TINYINT UNSIGNED DEFAULT '1'
);

ALTER TABLE `#__pizzabox_containers_parts` ADD UNIQUE (`container_id`, `part_id`);

CREATE TABLE IF NOT EXISTS `#__pizzabox_flavours` (
  `id`                INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`              VARCHAR(50) NOT NULL,
  `desc`              TEXT,
  `image`             VARCHAR(255),
  `price`             DECIMAL(8,2),
  `parts`             TEXT DEFAULT '[]',
  `ordering`          INT(11) DEFAULT '0',
  `checked_out`       INT(11) DEFAULT '0',
  `checked_out_time`  DATETIME DEFAULT '0000-00-00 00:00:00',
  `published`         TINYINT(1) DEFAULT '1'
);

-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__pizzabox_orders` (
  `id`                INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id`           INT(11),
  `address_id`        INT(11),
  `status_id`         INT(11) DEFAULT '1',
  `datetime`          DATETIME,
  `delivery`          DATETIME,
  `name`              VARCHAR(50),
  `ordering`          INT(11) DEFAULT '0',
  `checked_out`       INT(11) DEFAULT '0',
  `checked_out_time`  DATETIME DEFAULT '0000-00-00 00:00:00',
  `published`         TINYINT(1) DEFAULT '1'
);

CREATE TABLE IF NOT EXISTS `#__pizzabox_orders_parts` (
  `id`                INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `order_id`          INT(11) NOT NULL,
  `container_id`      INT(11) NOT NULL,
  `part_id`           INT(11) NOT NULL,
  `flavour_id`        INT(11) NOT NULL
);

-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__pizzabox_status` (
  `id`                INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`              VARCHAR(50) NOT NULL,
  `desc`              TEXT,
  `lock`              TINYINT(1),
  `image`             VARCHAR(255),
  `ordering`          INT(11) DEFAULT '0',
  `checked_out`       INT(11) DEFAULT '0',
  `checked_out_time`  DATETIME DEFAULT '0000-00-00 00:00:00',
  `published`         TINYINT(1) DEFAULT '1'
);

INSERT INTO `#__pizzabox_status` (`id`,`name`,`desc`,`lock`,`ordering`)
  VALUES ('1','Submitted','Default status','0','1')
  ON DUPLICATE KEY UPDATE `id` = `id`;

-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__pizzabox_addresses` (
  `id`                INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id`           INT(11) NOT NULL,
  `name`              VARCHAR(50) NOT NULL,
  `street`            VARCHAR(255),
  `zip`               VARCHAR( 6 ),
  `city`              VARCHAR(255),
  `state`             VARCHAR(255),
  `country`           VARCHAR(255),
  `ordering`          INT(11) DEFAULT '0',
  `checked_out`       INT(11),
  `checked_out_time`  DATETIME DEFAULT '0000-00-00 00:00:00',
  `published`         TINYINT(1) DEFAULT '1'
);
