-- Create table for users
-- With relation to offers table.

CREATE TABLE `musicstream`.`users` (
  `id` INT NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `offer_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `offer_id_idx` (`offer_id` ASC) VISIBLE,
  CONSTRAINT `offer_id`
    FOREIGN KEY (`offer_id`)
    REFERENCES `musicstream`.`offers` (`offer_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
