<?php
include 'db.php';

// Get search, category, and sort parameters from the GET request
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

// Build the query to fetch products
$query = "SELECT p.*, (p.mrp - p.price) AS discount FROM products p WHERE p.title LIKE '%$search%'";

if ($category) {
    $query .= " AND p.category_id = $category";
}

if ($sort == 'price_asc') {
    $query .= " ORDER BY p.price ASC";
} elseif ($sort == 'price_desc') {
    $query .= " ORDER BY p.price DESC";
} elseif ($sort == 'latest') {
    $query .= " ORDER BY p.product_id DESC";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My E-commerce Site</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header>
        <div class="banner">
            <form method="GET" action="index.php">
            <a href="index.php" class="home-button">Home</a>
                <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                <select name="category">
                    <option value="">All Categories</option>
                    <?php
                    // Fetch categories from the database
                    $cat_query = "SELECT * FROM category";
                    $cat_result = $conn->query($cat_query);
                    while ($row = $cat_result->fetch_assoc()) {
                        echo '<option value="' . $row['id'] . '"' . ($category == $row['id'] ? ' selected' : '') . '>' . $row['name'] . '</option>';
                    }
                    ?>
                </select>
                <select name="sort">
                    <option value="">Sort By</option>
                    <option value="price_asc" <?php echo $sort == 'price_asc' ? 'selected' : ''; ?>>Price Low to High</option>
                    <option value="price_desc" <?php echo $sort == 'price_desc' ? 'selected' : ''; ?>>Price High to Low</option>
                    <option value="latest" <?php echo $sort == 'latest' ? 'selected' : ''; ?>>Latest Products</option>
                </select>
                <button type="submit">Apply</button>
            </form>
            
            <a href="cart.php"class="cart-button" >Cart</a>
        </div>
    </header>
    <main>
        <div class="products">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="product">';
                    echo '<a href="product_detail.php?id=' . $row['product_id'] . '">';
                    echo '<img src="images/' . $row['image'] . '" alt="' . $row['title'] . '">';
                    echo '<h2>' . $row['title'] . '</h2>';
                    echo '</a>';
                    echo '<p>MRP: ' . $row['mrp'] . '</p>';
                    echo '<p>Price: ' . $row['price'] . '</p>';
                    echo '<p>Discount: ' . $row['discount'] . '</p>';
                    echo '<form method="POST" action="add_to_cart.php">';
                    echo '<input type="hidden" name="product_id" value="' . $row['product_id'] . '">';
                    echo '<button type="submit">Add to Cart</button>';
                    echo '</form>';
                    echo '</div>';
                }
            } else {
                echo 'No products found';
            }
            ?>
        </div>
    </main>
</body>
</html>
