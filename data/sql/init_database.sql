CREATE DATABASE IF NOT EXISTS `product`;

USE `product`;

CREATE TABLE IF NOT EXISTS `product`
(
  `id_product` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(200)     NOT NULL,
  `brand`      VARCHAR(200)     NOT NULL,
  `stock`      INT(10) DEFAULT 0,
  `uuid`       VARCHAR(36)      NOT NULL,
  PRIMARY KEY (`id_product`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE = InnoDB
  DEFAULT CHARSET=utf8;

INSERT INTO product (id_product, name, brand, stock, uuid) VALUES
(1, 'Raja', 'RJ', 100, '94d89f13-14a1-32eb-8584-0242ac130002'),
(2, 'Pure Linen Plain Shirt', 'ShirtsCo', 44, '94d8899e-14a1-11eb-8584-0242ac130002'),
(3, 'Long Sleeve Nursing Tops', 'ShirtsCo', 12, '94d88c55-14a1-11eb-8584-0242ac130002'),
(4, 'Christian Dior Pure Poison Eau De Parfum Spray', 'Christian Dior', 1031, '94d88e75-14a1-11eb-8584-0242ac130002'),
(5, 'Speed TR Flexweave Shoes', 'Flexweave', 1300, '94d88fda-14a1-11eb-8584-0242ac130002'),
(6, 'Berlin New Shirt', 'ShirtsCo', 2100, '94d89121-14a1-11eb-8584-0242ac130002'),
(7, '2 Pack Bikini Brief', 'ShirtsCo', 3200, '94d8924f-14a1-11eb-8584-0242ac130002'),
(8, 'Organic Moisturizing Lip Balm', 'Organic', 10, '94d89374-14a1-11eb-8584-0242ac130002'),
(9, 'Combi Leather Sandals', 'ShoesCo', 1100, '94d894b4-14a1-11eb-8584-0242ac130002'),
(10, 'Sasha', 'Sasha', 120, '94d896d9-14a1-11eb-8584-0242ac130002'),
(11, 'Womens Bella Surfing Maxi Tank Dress', 'ShirtsCo', 2100, '94d899b5-14a1-11eb-8584-0242ac130002'),
(12, 'Plano Tee', 'TeeCo', 10, '94d89b5a-14a1-11eb-8584-0242ac130002'),
(13, 'Solid Cross Back Bikini Top', 'ShirtsCo', 120, '94d89ca1-14a1-11eb-8584-0242ac130002'),
(14, 'Ottana Sweater', 'Ottana', 3200, '94d89dda-14a1-11eb-8584-0242ac130002'),
(15, 'Brushed Herringbone Pant With Tape Detail In Loose Tapered Fit', 'Ottana', 100, '94d89f13-14a1-11eb-8584-0242ac130002'),
(16, 'Mina', 'Mina', 321, '94d8a04a-14a1-11eb-8584-0242ac130002'),
(17, 'Claire Top', 'Claire', 1321, '94d8a17e-14a1-11eb-8584-0242ac130002'),
(18, 'Calvin Klein Fully Delicious Sheer Plumping Lip Gloss', 'Calvin Klein', 132, '94d8a2d3-14a1-11eb-8584-0242ac130002'),
(19, 'Shara Shara White Stem Sleeping Mask', 'Shara Shara', 1021, '94d8a413-14a1-11eb-8584-0242ac130002'),
(20, 'Classy & Fabulous Coat', 'ShirtsCo', 1332, '94d8a547-14a1-11eb-8584-0242ac130002'),
(21, 'Black Cherry Dress', 'ShirtsCo', 11, '94d8a67d-14a1-11eb-8584-0242ac130002'),
(22, 'Storm Jacket', 'ShirtsCo', 145, '94d8a7aa-14a1-11eb-8584-0242ac130002'),
(23, 'Sweet Daisy Top', 'ShirtsCo', 14400, '94d8a8d9-14a1-11eb-8584-0242ac130002');
