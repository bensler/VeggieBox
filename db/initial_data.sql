DELETE FROM `offers`;
DELETE FROM `vegetables`;
DELETE FROM `units`;
DELETE FROM `people`;
DELETE FROM `profiles`;


INSERT INTO `units` (`id`, `fraction_digits`, `name`) VALUES ('1', '1', 'kg');
INSERT INTO `units` (`id`, `fraction_digits`, `name`) VALUES ('2', '0', 'Stk');
INSERT INTO `units` (`id`, `fraction_digits`, `name`) VALUES ('3', '0', 'Bund');

INSERT INTO `vegetables` (`id`, `name`, `order_unit_id`, `price_unit_id`) VALUES ('10', 'Porree', '1', '1');
INSERT INTO `vegetables` (`id`, `name`, `order_unit_id`, `price_unit_id`) VALUES ('11', 'Tomaten', '1', '1');
INSERT INTO `vegetables` (`id`, `name`, `order_unit_id`, `price_unit_id`) VALUES ('12', 'gelber Paprika', '1', '1');
INSERT INTO `vegetables` (`id`, `name`, `order_unit_id`, `price_unit_id`) VALUES ('13', 'Rotkohl', '2', '1');

INSERT INTO `profiles` (`id`, `name`) VALUES ('1', 'Kunde');
INSERT INTO `profiles` (`id`, `name`) VALUES ('2', 'Admin');


-- INSERT INTO `people` (`id`, `login`, `profile_id`, `fname`, `lname`, `salutation`, `password`, `email`, `telephone`, `address`, `zip`, `city`)
--    VALUES (...);
-- INSERT INTO `people` (`id`, `login`, `profile_id`, `fname`, `lname`, `salutation`, `password`, `email`, `telephone`, `address`, `zip`, `city`) 
--    VALUES (...);

INSERT INTO `offers` (
 `id`, `state`, `name`, `end_date`, `introduction`
) VALUES (
 '1', '300', 'KW 01/09', '2009/1/1 12:00', 'Vorlage'
);
