CREATE TABLE IF NOT EXISTS `#__pizzabox_containers` (
  `id`                INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`              VARCHAR( 50 ) NOT NULL,
  `desc`              TEXT,
  `image`             VARCHAR( 255 ),
  `price`             DECIMAL( 8,2 ),
  `ordering`          INT( 11 ) DEFAULT '0',
  `checked_out`       INT( 11 ),
  `checked_out_time`  DATETIME,
  `published`         TINYINT( 1 )
);

CREATE TABLE IF NOT EXISTS `#__pizzabox_schemes` (
  `id`                INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `container_id`      INT( 11 ) NOT NULL,
  `name`              VARCHAR( 50 ) NOT NULL,
  `desc`              TEXT,
  `image`             VARCHAR( 255 ),
  `price`             DECIMAL( 8,2 ),
  `ordering`          INT( 11 ) DEFAULT '0',
  `checked_out`       INT( 11 ),
  `checked_out_time`  DATETIME,
  `published`         TINYINT( 1 )

);

CREATE TABLE IF NOT EXISTS `#__pizzabox_parts` (
  `id`                INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `container_id`      INT( 11 ),
  `scheme_id`         INT( 11 ),
  `name`              VARCHAR( 50 ) NOT NULL,
  `desc`              TEXT,
  `image`             VARCHAR( 255 ),
  `price`             DECIMAL( 8,2 ),
  `ordering`          INT( 11 ) DEFAULT '0',
  `checked_out`       INT( 11 ),
  `checked_out_time`  DATETIME,
  `published`         TINYINT( 1 )
);

CREATE TABLE IF NOT EXISTS `#__pizzabox_flavours` (
  `id`                INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`              VARCHAR( 50 ) NOT NULL,
  `desc`              TEXT,
  `image`             VARCHAR( 255 ),
  `price`             DECIMAL( 8,2 ),
  `ordering`          INT( 11 ) DEFAULT '0',
  `checked_out`       INT( 11 ),
  `checked_out_time`  DATETIME,
  `published`         TINYINT( 1 )

);

CREATE TABLE IF NOT EXISTS `#__pizzabox_orders` (
  `id`                INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id`           INT( 11 ),
  `address_id`           INT( 11 ),
  `status_id`         INT( 11 ),
  `datetime`          DATETIME,
  `delivery`          DATETIME,
  `name`              VARCHAR( 50 ),
  `ordering`          INT( 11 ) DEFAULT '0',
  `checked_out`       INT( 11 ),
  `checked_out_time`  DATETIME,
  `published`         TINYINT( 1 )  
);

CREATE TABLE IF NOT EXISTS `#__pizzabox_orders_parts` (
  `id`                INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `order_id`          INT( 11 ) NOT NULL,
  `container_number`  INT( 11 ) NOT NULL,
  `container_id`      INT( 11 ) NOT NULL,
  `container_name`    VARCHAR( 50 ),
  `scheme_id`         INT( 11 ) NOT NULL,
  `scheme_name`       VARCHAR( 50 ),
  `part_id`           INT( 11 ) NOT NULL,
  `part_name`         VARCHAR( 50 ),
  `flavour_id`        INT( 11 ) NOT NULL,
  `flavour_name`      VARCHAR( 50 )
);

CREATE TABLE IF NOT EXISTS `#__pizzabox_status` (
  `id`                INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`              VARCHAR( 50 ) NOT NULL,
  `desc`              TEXT,
  `lock`              TINYINT( 1 ),  
  `image`             VARCHAR( 255 ),
  `ordering`          INT( 11 ) DEFAULT '0',
  `checked_out`       INT( 11 ),
  `checked_out_time`  DATETIME,
  `published`         TINYINT( 1 )
);

INSERT INTO `#__pizzabox_status` (`id`,`name`,`desc`,`lock`,`ordering`,`checked_out`,`checked_out_time`,`published`) 
  VALUES ('1','Submitted','Default status','0','1','0','0000-00-00 00:00:00','1')
  ON DUPLICATE KEY UPDATE `id` = `id`;

CREATE TABLE IF NOT EXISTS `#__pizzabox_addresses` (
  `id`                INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id`           INT( 11 ) NOT NULL,
  `name`              VARCHAR( 50 ) NOT NULL,
  `street`            VARCHAR( 255 ),
  `zip`               VARCHAR( 6 ),
  `city`              VARCHAR( 255 ),
  `state`             VARCHAR( 255 ),
  `country`           VARCHAR( 255 ),
  `ordering`          INT( 11 ) DEFAULT '0',
  `checked_out`       INT( 11 ),
  `checked_out_time`  DATETIME,
  `published`         TINYINT( 1 )
);
