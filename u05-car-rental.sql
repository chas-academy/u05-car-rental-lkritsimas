DROP DATABASE IF EXISTS `u05-car-rental`;
CREATE DATABASE `u05-car-rental`;
USE `u05-car-rental`;
CREATE TABLE `customers` (
  `id` CHAR(10) PRIMARY KEY,
  `firstname` VARCHAR(255) NOT NULL,
  `surname` VARCHAR(255) NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  `postcode` CHAR(5) NOT NULL,
  `city` VARCHAR(255) NOT NULL,
  `phone` CHAR(10) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `edited_at` DATETIME NULL
);
CREATE TABLE `vehicles` (
  `id` CHAR(6) PRIMARY KEY,
  `make` VARCHAR(32) NOT NULL,
  `color` VARCHAR(32) NOT NULL,
  `year` SMALLINT(4) NOT NULL,
  `price` DECIMAL(8, 2) NOT NULL,
  `created_at` DATETIME NOT NULL
);
CREATE TABLE `booking` (
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `customer_id` CHAR(10) NOT NULL,
  `vehicle_id` CHAR(6) NOT NULL,
  `rented_at` DATETIME NOT NULL,
  `returned_at` DATETIME NULL
);
CREATE TABLE `colors` (`color` VARCHAR(32) PRIMARY KEY);
CREATE TABLE `makes` (`make` VARCHAR(32) PRIMARY KEY);
ALTER TABLE `booking`
ADD
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;
ALTER TABLE `booking`
ADD
  FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;
ALTER TABLE `vehicles`
ADD
  FOREIGN KEY (`make`) REFERENCES `makes` (`make`);
ALTER TABLE `vehicles`
ADD
  FOREIGN KEY (`color`) REFERENCES `colors` (`color`);
INSERT INTO customers(
    `id`,
    `firstname`,
    `surname`,
    `address`,
    `postcode`,
    `city`,
    `phone`,
    `created_at`
  )
VALUES
  (
    '9309230465',
    'Bill',
    'Gates',
    'Somestreet 123',
    '12345',
    'Silicon Valley',
    '0712345678',
    NOW()
  ),
  (
    '9209258087',
    'Steve',
    'Jobs',
    'RIP',
    '99999',
    'None',
    '0709999999',
    NOW()
  ),
  (
    '6302254344',
    'Steve',
    'Wozniak',
    'Somestreet 123',
    '66666',
    'Silicon Valley',
    '0709999999',
    NOW()
  ),
  (
    '6107280833',
    'Jeff',
    'Bezos',
    'Somestreet 123',
    '11111',
    'Silicon Valley',
    '0709999999',
    NOW()
  ),
  (
    '6207280833',
    'Jim',
    'Davis',
    '711 Maple Street',
    '11111',
    'Somewhere',
    '0709999999',
    NOW()
  );
INSERT INTO colors(`color`)
VALUES
  ('Black'),
  ('White'),
  ('Red'),
  ('Green'),
  ('Blue');
INSERT INTO makes(`make`)
VALUES
  ('Tesla'),
  ('Ford'),
  ('Audi'),
  ('Volvo');
INSERT INTO vehicles(
    `id`,
    `make`,
    `color`,
    `year`,
    `price`,
    `created_at`
  )
VALUES
  ('ABC123', 'Tesla', 'Black', '2019', '400', NOW()),
  ('XYZ789', 'Audi', 'White', '2014', '125', NOW()),
  ('LOL666', 'Ford', 'Red', '2005', '75', NOW()),
  ('WOT999', 'Volvo', 'Blue', '2011', '100', NOW());
INSERT INTO booking(`customer_id`, `vehicle_id`, `rented_at`)
VALUES
  ('9309230465', 'ABC123', NOW()),
  ('9209258087', 'XYZ789', NOW()),
  ('6302254344', 'LOL666', NOW()),
  ('6107280833', 'WOT999', NOW());
