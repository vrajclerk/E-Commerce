<?php
include 'db.php';
session_start();

$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

// Fetch products from the database based on search, category, and sort
$query = "SELECT * FROM products WHERE title LIKE '%$search%'";
if ($category != '') {
    $query .= " AND category_id = $category";
}
switch ($sort) {
    case 'price_asc':
        $query .= " ORDER BY price ASC";
        break;
    case 'price_desc':
        $query .= " ORDER BY price DESC";
        break;
    case 'latest':
        $query .= " ORDER BY product_id DESC";
        break;
    default:
        $query .= " ORDER BY product_id DESC";
        break;
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Function to handle adding/removing products from the cart
        $('.product').each(function() {
            var productId = $(this).data('product-id');
            var quantityElement = $(this).find('.quantity');

            // Check if the product is already in the cart
            $.ajax({
                url: 'check_cart.php',
                type: 'GET',
                data: { product_id: productId },
                success: function(response) {
                    if (response.inCart) {
                        quantityElement.val(response.quantity); // Set quantity
                        quantityElement.prop('disabled', false); // Enable quantity input
                        $(this).find('.add-to-cart').hide(); // Hide "Add to Cart" button
                        $(this).find('.remove-from-cart').show(); // Show "Remove from Cart" button
                    } else {
                        quantityElement.val(1); // Set default quantity to 1
                        quantityElement.prop('disabled', true); // Disable quantity input
                        $(this).find('.add-to-cart').show(); // Show "Add to Cart" button
                        $(this).find('.remove-from-cart').hide(); // Hide "Remove from Cart" button
                    }
                }
            });

            // Add to Cart button click event
            $(this).on('click', '.add-to-cart', function(e) {
                e.preventDefault();
                var quantity = quantityElement.val(); // Get quantity from input
                $.ajax({
                    url: 'add_to_cart.php',
                    type: 'POST',
                    data: { product_id: productId, quantity: quantity },
                    success: function(response) {
                        alert('Product added to cart!');
                        quantityElement.prop('disabled', false); // Enable quantity input
                        $('.add-to-cart').hide(); // Hide "Add to Cart" button
                        $('.remove-from-cart').show(); // Show "Remove from Cart" button
                    },
                    error: function() {
                        alert('Error adding product to cart.');
                    }
                });
            });

            // Remove from Cart button click event
            $(this).on('click', '.remove-from-cart', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'remove_from_cart.php',
                    type: 'POST',
                    data: { product_id: productId },
                    success: function(response) {
                        alert('Product removed from cart!');
                        quantityElement.prop('disabled', true); // Disable quantity input
                        $('.add-to-cart').show(); // Show "Add to Cart" button
                        $('.remove-from-cart').hide(); // Hide "Remove from Cart" button
                    },
                    error: function() {
                        alert('Error removing product from cart.');
                    }
                });
            });

            // Quantity input change event
            quantityElement.change(function() {
                var newQuantity = $(this).val();
                $.ajax({
                    url: 'update_cart.php',
                    type: 'POST',
                    data: { product_id: productId, quantity: newQuantity },
                    success: function(response) {
                        alert('Quantity updated!');
                    },
                    error: function() {
                        alert('Error updating quantity.');
                    }
                });
            });
        });
    });
    </script>
</head>
<body>
    <header>
        <div class="banner">
            <form method="GET" action="index.php">
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
            <a href="index.php" class="home-button">Home</a>
            <a href="cart.php" class="cart-button">Cart</a>
        </div>
    </header>
    <main>
        <div class="products">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product" data-product-id="<?php echo $row['product_id']; ?>">
                    <img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                    <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                    <p>MRP: <?php echo htmlspecialchars($row['mrp']); ?></p>
                    <p>Price: <?php echo htmlspecialchars($row['price']); ?></p>
                    <p>Discount: <?php echo round((($row['mrp'] - $row['price']) / $row['mrp']) * 100); ?>%</p>
                    <form>
                        <input type="number" class="quantity" value="1" min="1">
                        <button class="add-to-cart">Add to Cart</button>
                        <button class="remove-from-cart" style="display: none;">Remove from Cart</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>
