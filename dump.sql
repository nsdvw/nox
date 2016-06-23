-- can login by "demo" password, "admin@example.com" email

CREATE TABLE user (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  surname VARCHAR(50) NOT NULL,
  salt VARCHAR(255) NOT NULL,
  hash VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  UNIQUE (email)
) ENGINE=InnoDB CHARACTER SET = utf8 COLLATE utf8_general_ci;

INSERT INTO user (id, name, surname, salt, hash, email) VALUES
  (1, 'Иван', 'Иванов', 'demo', '6f4504dd6d8322708f1aa68a05c7ca9cfc2ee782',
  'admin@example.com');
