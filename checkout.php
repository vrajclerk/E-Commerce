<?php
include 'db.php';
session_start();

// Fetch the cart items from the session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Initialize total amount
$total_amount = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process the form submission
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];

    // Save order details in the database (implement the saving logic as needed)
    // Clear the cart after successful order placement
    $_SESSION['cart'] = [];
    header("Location: success.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
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
        <div class="checkout">
            <h1>Checkout</h1>
            <?php if (!empty($cart)): ?>
                <div class="cart-summary">
                    <h2>Order Summary</h2>
                    <?php foreach ($cart as $product_id => $quantity): ?>
                        <?php
                        $query = "SELECT * FROM products WHERE product_id = $product_id";
                        $result = $conn->query($query);
                        $product = $result->fetch_assoc();
                        $total_amount += $product['price'] * $quantity;
                        ?>
                        <div class="cart-item">
                            <img src="project_folder/images/<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>">
                            <h2><?php echo $product['title']; ?></h2>
                            <p>Price: <?php echo $product['price']; ?></p>
                            <p>Quantity: <?php echo $quantity; ?></p>
                            <p>Total: <?php echo $product['price'] * $quantity; ?></p>
                        </div>
                    <?php endforeach; ?>
                    <div class="cart-total">
                        <h2>Total Amount: <?php echo $total_amount; ?></h2>
                    </div>
                </div>
                <div class="billing-info">
                    <h2>Billing Information</h2>
                    <form method="POST" action="checkout.php">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" required>
                        <label for="city">City:</label>
                        <input type="text" id="city" name="city" required>
                        <label for="state">State:</label>
                        <input type="text" id="state" name="state" required>
                        <label for="zip">Zip Code:</label>
                        <input type="text" id="zip" name="zip" required>
                        <button type="submit">Place Order</button>
                    </form>
                </div>
            <?php else: ?>
                <p>Your cart is empty</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
