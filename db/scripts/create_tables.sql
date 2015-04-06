DROP TABLE `order_lines`, `orders`, `offer_lines`, `offers`, `vegetables`, `units`, `people`, `profiles`;

CREATE TABLE `profiles` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
`name` VARCHAR( 50 ) NULL ,
PRIMARY KEY ( `id` ) ,
UNIQUE ( `name` )
) TYPE = INNODB;

CREATE TABLE `people` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
`login` VARCHAR( 20 ) NOT NULL ,
`profile_id` INT UNSIGNED NOT NULL ,
`fname` VARCHAR( 50 ) NULL ,
`lname` VARCHAR( 100 ) NOT NULL ,
`salutation` VARCHAR( 50 ) NOT NULL ,
`password` VARCHAR( 100 ) NOT NULL ,
`email` VARCHAR( 100 ) NOT NULL ,
`active` BOOLEAN NOT NULL DEFAULT TRUE,
`telephone` VARCHAR( 100 ) NULL ,
`address` VARCHAR( 100 ) NOT NULL ,
`zip` VARCHAR( 10 ) NOT NULL ,
`city` VARCHAR( 100 ) NOT NULL ,
PRIMARY KEY ( `id` ) ,
UNIQUE ( `login` ) ,
INDEX ( `profile_id`)
) TYPE = INNODB ;

ALTER TABLE `people` ADD CONSTRAINT `fk_profiles__people` FOREIGN KEY ( `profile_id` ) REFERENCES `profiles` ( `id` )
ON DELETE restrict;

CREATE TABLE `units` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
`name` VARCHAR( 20 ) NOT NULL ,
`fraction_digits` INT UNSIGNED NOT NULL ,
PRIMARY KEY ( `id` ) ,
UNIQUE ( `name` )
) TYPE = INNODB ;


CREATE TABLE `vegetables` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
`name` VARCHAR( 100 ) NOT NULL ,
`unit_id` INT UNSIGNED NOT NULL ,
PRIMARY KEY ( `id` ) ,
UNIQUE ( `name` ) ,
INDEX ( `unit_id`)
) TYPE = INNODB ;

ALTER TABLE `vegetables` ADD CONSTRAINT `fk_units__vegetables` FOREIGN KEY ( `unit_id` ) REFERENCES `units` ( `id` )
ON DELETE restrict;

CREATE TABLE `offers` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
`state` INT UNSIGNED NOT NULL ,
`name` VARCHAR( 20 ) NOT NULL ,
`introduction` TEXT NULL ,
`end_date`  DATETIME NOT NULL ,
`delivery_date`  DATETIME NOT NULL ,
PRIMARY KEY ( `id` ) ,
UNIQUE ( `name` )
) TYPE = INNODB ;

CREATE TABLE `offer_lines` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
`offer_id` INT UNSIGNED NOT NULL ,
`sort_order` INT UNSIGNED NOT NULL ,
`vegetable_id` INT UNSIGNED NOT NULL ,
`comment` VARCHAR( 100 ) NULL ,
`price` VARCHAR( 20 ) NOT NULL ,
`sold_out` INT(1) UNSIGNED NOT NULL DEFAULT '0',
PRIMARY KEY ( `id` ) ,
INDEX ( `offer_id`) ,
INDEX ( `vegetable_id`)
) TYPE = INNODB ;

ALTER TABLE `offer_lines` ADD CONSTRAINT `fk_offer__offer_line` FOREIGN KEY ( `offer_id` ) REFERENCES `offers` ( `id` )
ON DELETE cascade;

ALTER TABLE `offer_lines` ADD CONSTRAINT `fk_vegetable__offer_line` FOREIGN KEY ( `vegetable_id` ) REFERENCES `vegetables` ( `id` )
ON DELETE restrict;

CREATE TABLE `orders` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
`offer_id` INT UNSIGNED NOT NULL ,
`person_id` INT UNSIGNED NOT NULL ,
`comment` TEXT NULL ,
PRIMARY KEY ( `id` ) ,
INDEX ( `offer_id`) ,
INDEX ( `person_id`)
) TYPE = INNODB ;

ALTER TABLE `orders` ADD CONSTRAINT `fk_offer__order` FOREIGN KEY ( `offer_id` ) REFERENCES `offers` ( `id` )
ON DELETE cascade;

ALTER TABLE `orders` ADD CONSTRAINT `fk_person__order` FOREIGN KEY ( `person_id` ) REFERENCES `people` ( `id` )
ON DELETE cascade;

CREATE TABLE `order_lines` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
`order_id` INT UNSIGNED NOT NULL ,
`offer_line_id` INT UNSIGNED NOT NULL ,
`quantity` INT UNSIGNED NOT NULL ,
PRIMARY KEY ( `id` ) ,
INDEX ( `order_id`) ,
INDEX ( `offer_line_id`)
) TYPE = INNODB ;

ALTER TABLE `order_lines` ADD CONSTRAINT `fk_order__order_line` FOREIGN KEY ( `order_id` ) REFERENCES `orders` ( `id` )
ON DELETE cascade;

ALTER TABLE `order_lines` ADD CONSTRAINT `fk_offer_line__order_line` FOREIGN KEY ( `offer_line_id` ) REFERENCES `offer_lines` ( `id` )
ON DELETE cascade;
