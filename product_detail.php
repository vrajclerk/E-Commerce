<?php
include 'db.php';

$product_id = $_GET['id'];

$query = "SELECT * FROM products WHERE product_id = $product_id";
$result = $conn->query($query);
$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['title']; ?></title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    
</head>
<body>
    <header>
        <div class="banner">
            <form method="GET" action="index.php">
                <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : ''); ?>">
                <select name="category">
                    <option value="">All Categories</option>
                    <?php
                    // Fetch categories from the database
                    $cat_query = "SELECT * FROM category";
                    $cat_result = $conn->query($cat_query);
                    while ($row = $cat_result->fetch_assoc()) {
                        echo '<option value="' . $row['id'] . '"' . (isset($_GET['category']) && $_GET['category'] == $row['id'] ? ' selected' : '') . '>' . $row['name'] . '</option>';
                    }
                    ?>
                </select>
                <select name="sort">
                    <option value="">Sort By</option>
                    <option value="price_asc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price_asc' ? 'selected' : ''; ?>>Price Low to High</option>
                    <option value="price_desc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price_desc' ? 'selected' : ''; ?>>Price High to Low</option>
                    <option value="latest" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'latest' ? 'selected' : ''; ?>>Latest Products</option>
                </select>
                <button type="submit">Apply</button>
            </form>
        </div>
    </header>
    <main>
        <div class="product-detail">
            <img src="/images<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>">
            <h1><?php echo $product['title']; ?></h1>
            <p>MRP: <?php echo $product['mrp']; ?></p>
            <p>Price: <?php echo $product['price']; ?></p>
            <p>Description: <?php echo $product['description']; ?></p>
            <form method="POST" action="add_to_cart.php">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
    </main>
</body>
</html>
