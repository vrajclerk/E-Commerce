<?php
session_start(); 
include 'db.php';

// Check if an action is set and if it is 'remove'
if (isset($_POST['action']) && $_POST['action'] == 'remove') {
    $product_id = $_POST['product_id'];
    
    // Check if the product is in the cart and remove it
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }

    echo json_encode(['status' => 'success']);
    exit();
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Handle click event for removing products from the cart
        $('.remove-from-cart').click(function(e) {
            e.preventDefault(); 
            var productId = $(this).data('product-id'); // Get the product ID from the data attribute

            // AJAX request to remove the product from the cart
            $.ajax({
                url: 'cart.php',
                type: 'POST',
                data: { product_id: productId, action: 'remove' },
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.status == 'success') {
                        // Remove the product row from the table if successful
                        $('#product-' + productId).remove();
                    } else {
                        alert('Failed to remove product from cart.');
                    }
                },
                error: function() {
                    alert('Error removing product from cart.');
                }
            });
        });
    });
    </script>
</head>
<body>
    <header>
        <h1>Shopping Cart</h1>
        <a href="index.php" class="home-button">Home</a>
    </header>
    <main>
        <table>
            <tr>
                <th>Product</th>
                <th>Title</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php
            // Check if the cart is set and not empty
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                // Loop through the cart items
                foreach ($_SESSION['cart'] as $product_id => $quantity) {
                    
                    $query = "SELECT * FROM products WHERE product_id = $product_id";
                    $result = $conn->query($query);
                    $product = $result->fetch_assoc();
                    $total_price = $product['price'] * $quantity; 
                    $total += $total_price;
                    ?>
                    <tr id="product-<?php echo $product_id; ?>">
                        <td><img src="images/<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>" width="50"></td>
                        <td><?php echo $product['title']; ?></td>
                        <td><?php echo $product['price']; ?></td>
                        <td><?php echo $quantity; ?></td>
                        <td><?php echo $total_price; ?></td>
                        <td><a href="#" class="remove-from-cart" data-product-id="<?php echo $product_id; ?>">Remove</a></td>
                    </tr>
                    <?php
                }
            } else {
                echo '<tr><td colspan="6">Your cart is empty.</td></tr>';
            }
            ?>
            <tr>
                <td colspan="4">Total</td>
                <td colspan="2"><?php echo $total; ?></td>
            </tr>
        </table>
    </main>
</body>
</html>
