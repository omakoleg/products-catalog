-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 5.0.82.1
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 01.08.2012 21:33:46
-- Версия сервера: 5.5.22-0ubuntu1
-- Версия клиента: 4.1

-- 
-- Отключение внешних ключей
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Установка кодировки, с использованием которой клиент будет посылать запросы на сервер
--
SET NAMES 'utf8';

-- 
-- Установка базы данных по умолчанию
--

--
-- Описание для таблицы category
--
DROP TABLE IF EXISTS category;
CREATE TABLE IF NOT EXISTS category (
  id INT(11) NOT NULL AUTO_INCREMENT,
  parent_id INT(11) DEFAULT NULL,
  name VARCHAR(255) DEFAULT NULL,
  slug VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX UK_category_id (id),
  INDEX UK_category_slug (slug)
)
ENGINE = INNODB
AUTO_INCREMENT = 20
AVG_ROW_LENGTH = 1820
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Описание для таблицы feature
--
DROP TABLE IF EXISTS feature;
CREATE TABLE IF NOT EXISTS feature (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) DEFAULT NULL,
  display_type ENUM('regular','color','backblack') NOT NULL DEFAULT 'regular',
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 13
AVG_ROW_LENGTH = 3276
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Описание для таблицы lookup
--
DROP TABLE IF EXISTS lookup;
CREATE TABLE IF NOT EXISTS lookup (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(128) NOT NULL,
  code INT(11) NOT NULL,
  type VARCHAR(128) NOT NULL,
  position INT(11) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 6
AVG_ROW_LENGTH = 3276
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--
-- Описание для таблицы product
--
DROP TABLE IF EXISTS product;
CREATE TABLE IF NOT EXISTS product (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) DEFAULT NULL,
  price DECIMAL(10, 2) DEFAULT NULL,
  description VARCHAR(255) DEFAULT NULL,
  is_new INT(1) DEFAULT 0,
  ref VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX UK_product_description (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 27
AVG_ROW_LENGTH = 1024
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Описание для таблицы category_to_product
--
DROP TABLE IF EXISTS category_to_product;
CREATE TABLE IF NOT EXISTS category_to_product (
  category_id INT(11) NOT NULL,
  product_id INT(11) NOT NULL,
  PRIMARY KEY (category_id, product_id),
  INDEX UK_category_to_product_product_id (product_id),
  CONSTRAINT FK_category_to_product_category_id FOREIGN KEY (category_id)
    REFERENCES category(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_category_to_product_product_id FOREIGN KEY (product_id)
    REFERENCES product(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AVG_ROW_LENGTH = 682
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Описание для таблицы feature_value
--
DROP TABLE IF EXISTS feature_value;
CREATE TABLE IF NOT EXISTS feature_value (
  id INT(11) NOT NULL AUTO_INCREMENT,
  feature_id INT(11) NOT NULL,
  name VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_feature_value_feature_id FOREIGN KEY (feature_id)
    REFERENCES feature(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 18
AVG_ROW_LENGTH = 1260
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Описание для таблицы product_image
--
DROP TABLE IF EXISTS product_image;
CREATE TABLE IF NOT EXISTS product_image (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) DEFAULT NULL,
  product_id INT(11) DEFAULT NULL,
  is_default INT(1) DEFAULT 0,
  PRIMARY KEY (id),
  INDEX UK_product_image_product_id (product_id),
  CONSTRAINT FK_product_image_product_id FOREIGN KEY (product_id)
    REFERENCES product(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 62
AVG_ROW_LENGTH = 1820
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Описание для таблицы attribute
--
DROP TABLE IF EXISTS attribute;
CREATE TABLE IF NOT EXISTS attribute (
  id INT(11) NOT NULL AUTO_INCREMENT,
  feature_id INT(11) NOT NULL,
  feature_value_id INT(11) NOT NULL,
  product_id INT(11) NOT NULL,
  is_filtered INT(1) DEFAULT 1,
  PRIMARY KEY (id),
  CONSTRAINT FK_attribute_feature_id FOREIGN KEY (feature_id)
    REFERENCES feature(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_attribute_feature_value_id FOREIGN KEY (feature_value_id)
    REFERENCES feature_value(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_attribute_product_id FOREIGN KEY (product_id)
    REFERENCES product(id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 35
AVG_ROW_LENGTH = 1638
CHARACTER SET utf8
COLLATE utf8_general_ci;


-- 
-- Включение внешних ключей
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;