-- table orders: Referenz auf people jetzt on delete cascade, damit kunden löschbar,
--    wenn schon Bestellungen vorliegen
ALTER TABLE `orders` DROP FOREIGN KEY `fk_person__order`;

ALTER TABLE `orders` ADD CONSTRAINT `fk_person__order` FOREIGN KEY ( `person_id` ) REFERENCES `people` ( `id` )
ON DELETE cascade;
