CREATE DATABASE IF NOT EXISTS `lmdev_agency` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

-- MEDIUMINT should be enough for id as it can store up to 16777215 if unsigned

-- clients table
CREATE TABLE IF NOT EXISTS `agency_clients` (
  `id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `company_name` VARCHAR(255) NOT NULL,
  `country` TINYINT UNSIGNED NOT NULL,
  `currency` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `vat_number` VARCHAR(20) NOT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- packages
CREATE TABLE IF NOT EXISTS `agency_packages` (
  `id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `currency` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `price` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- clients packages
CREATE TABLE IF NOT EXISTS `agency_clients_packages` (
  `id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` MEDIUMINT UNSIGNED,
  `package_id` MEDIUMINT UNSIGNED,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`client_id`) REFERENCES `agency_clients`(`id`),
  FOREIGN KEY (`package_id`) REFERENCES `agency_packages`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- contact persons of clients
CREATE TABLE IF NOT EXISTS `agency_clients_contacts_persons` (
    `id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `client_id` MEDIUMINT UNSIGNED,
    `firstname` VARCHAR(255) NOT NULL,
    `lastname` VARCHAR(255) NOT NULL,
    `email` VARCHAR(320) NOT NULL,
    `phone` VARCHAR(15) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`client_id`) REFERENCES `agency_clients`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- employees
CREATE TABLE IF NOT EXISTS `agency_employees` (
    `id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `firstname` VARCHAR(255) NOT NULL,
    `lastname` VARCHAR(255) NOT NULL,
    `email` VARCHAR(320) NOT NULL,
    `phone` VARCHAR(15) NOT NULL DEFAULT '',
    `active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- table with account managers assigned to clients
CREATE TABLE IF NOT EXISTS `agency_clients_account_managers` (
    `id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `client_id` MEDIUMINT UNSIGNED,
    `employee_id` MEDIUMINT UNSIGNED,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`client_id`) REFERENCES `agency_clients`(`id`),
    FOREIGN KEY (`employee_id`) REFERENCES `agency_employees`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- table with account managers assigned to clients
CREATE TABLE IF NOT EXISTS `agency_account_managers_clients` (
    `id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employee_id` MEDIUMINT UNSIGNED,
    `client_id` MEDIUMINT UNSIGNED,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`employee_id`) REFERENCES `agency_employees`(`id`),
    FOREIGN KEY (`client_id`) REFERENCES `agency_clients`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- sample data for packages
INSERT INTO `agency_packages` (`name`, `currency`, `price`) VALUES ('Basic', 1, 100.00);
INSERT INTO `agency_packages` (`name`, `currency`, `price`) VALUES ('Standard', 1, 200.00);
INSERT INTO `agency_packages` (`name`, `currency`, `price`) VALUES ('Premium', 1, 300.00);
INSERT INTO `agency_packages` (`name`, `currency`, `price`) VALUES ('Basic', 2, 50.00);
INSERT INTO `agency_packages` (`name`, `currency`, `price`) VALUES ('Standard', 2, 100.00);
INSERT INTO `agency_packages` (`name`, `currency`, `price`) VALUES ('Premium', 2, 150.00);
INSERT INTO `agency_packages` (`name`, `currency`, `price`) VALUES ('Basic', 3, 200.00);
INSERT INTO `agency_packages` (`name`, `currency`, `price`) VALUES ('Standard', 3, 400.00);
INSERT INTO `agency_packages` (`name`, `currency`, `price`) VALUES ('Premium', 3, 600.00);



