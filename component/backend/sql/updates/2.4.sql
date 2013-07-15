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

ALTER TABLE  `#__pizzabox_orders` ADD `address_id` INT(11) NULL AFTER  `user_id`
