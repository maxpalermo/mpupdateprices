CREATE TABLE IF NOT EXISTS `{_DB_PREFIX_}mp_update_prices` ( 
    `id_update_prices` INT NOT NULL AUTO_INCREMENT , 
    `col_reference` VARCHAR(100) NOT NULL , 
    `col_price` INT NOT NULL , 
    `row_start_from` INT NOT NULL , 
    `match_type` INT NOT NULL , 
    `is_tax_included` BOOLEAN NOT NULL , 
    `has_price_variations` BOOLEAN NOT NULL,
    `variation_price` BOOLEAN NOT NULL , 
    `variation_method` INT NOT NULL , 
    `variation_amount` INT NOT NULL , 
    `variation_value` FLOAT NOT NULL , 
    `variation_round_method` INT NOT NULL , 
    `variation_round_amount` INT NOT NULL , 
    PRIMARY KEY (`id_update_prices`)
) ENGINE = InnoDB;