<?php
include 'db.php';
session_start();
// Fetch the cart items from the session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
// Initialize total amount
$total_amount = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
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
            <a href="index.php" class="home-button">Home</a>

        </div>
    </header>
    <main>
  
        <div class="cart">
            <h1>Shopping Cart</h1>
            <?php if (!empty($cart)): ?>
                <?php foreach ($cart as $product_id => $quantity): ?>
                    <?php
                    $query = "SELECT * FROM products WHERE product_id = $product_id";
                    $result = $conn->query($query);
                    $product = $result->fetch_assoc();
                    if ($product) {
                        $total_amount += $product['price'] * $quantity;
                    ?>
                        <div class="cart-item">
                            <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                            <h2><?php echo htmlspecialchars($product['title']); ?></h2>
                            <p>Price: <?php echo htmlspecialchars($product['price']); ?></p>
                            <p>
                                Quantity:
                                <input type="number" class="update-quantity" data-product-id="<?php echo $product_id; ?>" value="<?php echo htmlspecialchars($quantity); ?>" min="1">
                            </p>
                            <p>Total: <?php echo htmlspecialchars($product['price'] * $quantity); ?></p>
                            <button type="button" class="remove-from-cart" data-product-id="<?php echo $product_id; ?>">Remove from Cart</button>
                        </div>
                    <?php
                    } else {
                        echo '<p>Product not found</p>';
                    }
                    ?>
                <?php endforeach; ?>
                <div class="cart-total">
                    <h2>Total Amount: <?php echo $total_amount; ?></h2>
                </div>
            <?php else: ?>
                <p>Your cart is empty</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>