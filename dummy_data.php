<?php
include 'db.php';

$conn->query("INSERT INTO tbl_products (product_id, title, mrp, price, image, category_id, parent_category_id, description) VALUES
(1, 'Product 1', 100.00, 80.00, '1.png', 1, NULL, 'Description for Product 1'),
(2, 'Product 2', 200.00, 150.00, '2.png', 1, NULL, 'Description for Product 2'),
(3, 'Product 3', 300.00, 250.00, '3.png', 2, NULL, 'Description for Product 3')");

$conn->query("INSERT INTO tbl_product_images (id, product_id, path) VALUES
(1, 1, '1.png'),
(2, 2, '2.png'),
(3, 3, '3.png')");

$conn->query("INSERT INTO tbl_category (id, name, parent_category_id) VALUES
(1, 'Category 1', NULL),
(2, 'Category 2', NULL)");

echo "Dummy data inserted successfully!";
?>
