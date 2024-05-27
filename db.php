<?php
$conn = new mysqli('localhost', 'root', '', 'e-commerce');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql= "CREATE TABLE IF NOT EXISTS PRODUCTS(
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(30) NOT NULL,
    price FLOAT(10,2) NOT NULL,
    image VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    FOREIGN KEY (category_id) REFERENCES CATEGORY(id),
    FOREIGN KEY  (parent_category_id) REFERENCES CATEGORY(parent_category_id)
)";
$sql1="CREATE TABLE IF NOT EXISTS PRODUCT_IMAGES(
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    image_path VARCHAR(100) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES PRODUCTS(id)
)";
$sql2="CREATE TABLE IF NOT EXISTS CATEGORY(
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    parent_category_id INT(6) NOT NULL
)";

?>
