
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- index_engine_index
-- ---------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `index_engine_index`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `visible` TINYINT DEFAULT 0 NOT NULL,
    `code` VARCHAR(255) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `entity` VARCHAR(64) NOT NULL,
    `serialized_columns` LONGTEXT NOT NULL,
    `serialized_condition` LONGTEXT NOT NULL,
    `index_engine_driver_configuration_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `version` INTEGER DEFAULT 0,
    `version_created_at` DATETIME,
    `version_created_by` VARCHAR(100),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `unique_index_engine_index_code` (`code`),
    INDEX `idx_index_engine_index_index_engine_driver_configuration_fk` (`index_engine_driver_configuration_id`),
    CONSTRAINT `fk_index_engine_index_index_engine_driver_configuration_id`
        FOREIGN KEY (`index_engine_driver_configuration_id`)
        REFERENCES `index_engine_driver_configuration` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- index_engine_driver_configuration
-- ---------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `index_engine_driver_configuration`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(255) NOT NULL,
    `driver_code` VARCHAR(64) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `serialized_configuration` LONGTEXT NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `UNIQUE_1` (`code`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- index_engine_index_template
-- ---------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `index_engine_index_template`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `visible` TINYINT DEFAULT 0 NOT NULL,
    `code` VARCHAR(255) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `entity` VARCHAR(64) NOT NULL,
    `serialized_columns` LONGTEXT NOT NULL,
    `serialized_condition` LONGTEXT NOT NULL,
    `index_engine_driver_configuration_id` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `version` INTEGER DEFAULT 0,
    `version_created_at` DATETIME,
    `version_created_by` VARCHAR(100),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `unique_index_engine_index_code` (`code`),
    INDEX `idx_index_engine_index_template_engine_driver_configuration_fk` (`index_engine_driver_configuration_id`),
    CONSTRAINT `fk_index_engine_index_template_engine_driver_configuration_id`
        FOREIGN KEY (`index_engine_driver_configuration_id`)
        REFERENCES `index_engine_driver_configuration` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- index_engine_index_version
-- ---------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `index_engine_index_version`
(
    `id` INTEGER NOT NULL,
    `visible` TINYINT DEFAULT 0 NOT NULL,
    `code` VARCHAR(255) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `entity` VARCHAR(64) NOT NULL,
    `serialized_columns` LONGTEXT NOT NULL,
    `serialized_condition` LONGTEXT NOT NULL,
    `index_engine_driver_configuration_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `version` INTEGER DEFAULT 0 NOT NULL,
    `version_created_at` DATETIME,
    `version_created_by` VARCHAR(100),
    PRIMARY KEY (`id`,`version`),
    CONSTRAINT `index_engine_index_version_FK_1`
        FOREIGN KEY (`id`)
        REFERENCES `index_engine_index` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- index_engine_index_template_version
-- ---------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `index_engine_index_template_version`
(
    `id` INTEGER NOT NULL,
    `visible` TINYINT DEFAULT 0 NOT NULL,
    `code` VARCHAR(255) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `entity` VARCHAR(64) NOT NULL,
    `serialized_columns` LONGTEXT NOT NULL,
    `serialized_condition` LONGTEXT NOT NULL,
    `index_engine_driver_configuration_id` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `version` INTEGER DEFAULT 0 NOT NULL,
    `version_created_at` DATETIME,
    `version_created_by` VARCHAR(100),
    PRIMARY KEY (`id`,`version`),
    CONSTRAINT `index_engine_index_template_version_FK_1`
        FOREIGN KEY (`id`)
        REFERENCES `index_engine_index_template` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
