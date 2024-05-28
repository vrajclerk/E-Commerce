<?php
session_start();
include 'db.php';

// Function to update quantity in the session cart
function update_quantity($product_id, $quantity) {
    $_SESSION['cart'][$product_id] = $quantity;
}

// Function to remove a product from the session cart
function remove_product($product_id) {
    unset($_SESSION['cart'][$product_id]);
}

// Update quantity if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $product_id = $_POST['product_id'];

        if ($action === 'increase') {
            update_quantity($product_id, $_SESSION['cart'][$product_id] + 1);
        } elseif ($action === 'decrease') {
            update_quantity($product_id, max(1, $_SESSION['cart'][$product_id] - 1));
        } elseif ($action === 'remove') {
            remove_product($product_id);
        }
    }
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
        $('.quantity').change(function() {
            var productId = $(this).data('product-id');
            var quantity = $(this).val();
            
            $.ajax({
                url: 'cart.php',
                type: 'POST',
                data: { action: 'update', product_id: productId, quantity: quantity },
                success: function(response) {
                    location.reload(); // Reload the page after updating quantity
                },
                error: function() {
                    alert('Error updating quantity.');
                }
            });
        });

        $('.increase-quantity').click(function(e) {
            e.preventDefault();
            var productId = $(this).data('product-id');

            $.ajax({
                url: 'cart.php',
                type: 'POST',
                data: { action: 'increase', product_id: productId },
                success: function(response) {
                    location.reload(); // Reload the page after increasing quantity
                },
                error: function() {
                    alert('Error increasing quantity.');
                }
            });
        });

        $('.decrease-quantity').click(function(e) {
            e.preventDefault();
            var productId = $(this).data('product-id');

            $.ajax({
                url: 'cart.php',
                type: 'POST',
                data: { action: 'decrease', product_id: productId },
                success: function(response) {
                    location.reload(); // Reload the page after decreasing quantity
                },
                error: function() {
                    alert('Error decreasing quantity.');
                }
            });
        });

        $('.remove-from-cart').click(function(e) {
            e.preventDefault();
            var productId = $(this).data('product-id');

            $.ajax({
                url: 'cart.php',
                type: 'POST',
                data: { action: 'remove', product_id: productId },
                success: function(response) {
                    location.reload(); // Reload the page after removing product
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
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
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
                        <td>
                        <button class="increase-quantity" data-product-id="<?php echo $product_id; ?>">+</button>
                            <input type="number" class="quantity" data-product-id="<?php echo $product_id; ?>" value="<?php echo $quantity; ?>" min="1">
                            
                            <button class="decrease-quantity" data-product-id="<?php echo $product_id; ?>">-</button>
                        </td>
                        <td><?php echo $total_price; ?></td>
                        <td><a href="#" class="remove-from-cart" data-product-id="<?php echo $product_id; ?>">Remove</a></td>
                    </tr>
                    <?php
                }
            } else {
                echo '<tr><td colspan=""6">Your cart is empty.</td></tr>';
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

