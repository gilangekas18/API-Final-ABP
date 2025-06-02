CREATE DATABASE tugas_api_kuliah CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

use tugas_api_kuliah;
CREATE TABLE categories (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE menu (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

INSERT INTO categories (name) VALUES
('Coffee'),
('Non Coffee'),
('Dessert');

INSERT INTO menu (category_id, name, price) VALUES
(1, 'Espresso', 18000.00),
(1, 'Americano', 20000.00),
(1, 'Iced Coffee', 20000.00),
(1, 'Cappuccino', 25000.00),
(1, 'Matcha Latte', 27000.00),
(1, 'Vanilla Latte', 28000.00),
(1, 'Choco Latte', 28000.00),
(1, 'Latte', 25000.00),
(1, 'Cold Brew', 21000.00),
(2, 'Hot Chocolate', 20000.00),
(2, 'Matcha Bliss', 22000.00),
(2, 'Early Grey', 25000.00),
(2, 'Lemon Pop', 23000.00),
(2, 'Berry Boom', 28000.00),
(2, 'TropiCool', 22000.00),
(2, 'Milky Way', 22000.00),
(2, 'Minty Fresh', 15000.00),
(2, 'Peach Tea Twist', 22000.00),
(2, 'Blue Ocean', 28000.00),
(3, 'Choco Lava', 30000.00),
(3, 'Berry Pancake', 28000.00),
(3, 'Velvet Slice', 27000.00),
(3, 'Banana Bread', 25000.00),
(3, 'Muffin', 14000.00),
(3, 'Donuts', 32000.00);

drop table categories;

drop table menu;

SELECT * FROM menu;

SHOW TABLES;

DROP TABLE IF EXISTS menu;
DROP TABLE IF EXISTS categories;