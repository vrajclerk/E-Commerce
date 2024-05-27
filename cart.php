<?php
session_start();
include 'db.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $product_id = $_POST['product_id'];
    
    switch ($action) {
        case 'update':
            $quantity = $_POST['quantity'];
            if ($quantity > 0) {
                $_SESSION['cart'][$product_id] = $quantity;
            } else {
                unset($_SESSION['cart'][$product_id]);
            }
            break;
        case 'delete':
            unset($_SESSION['cart'][$product_id]);
            break;
    }
    header("Location: cart.php");
    exit();
}

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
    $products = array();
    
    foreach ($cart as $product_id => $quantity) {
        $result = $conn->query("SELECT * FROM tbl_products WHERE product_id = $product_id");
        $product = $result->fetch_assoc();
        $product['quantity'] = $quantity;
        $products[] = $product;
    }
    
    foreach ($products as $product) {
        echo "<div>";
        echo "<img src='images/{$product['image']}' />";
        echo "<p>{$product['title']}</p>";
        echo "<p>MRP: {$product['mrp']}</p>";
        echo "<p>Price: {$product['price']}</p>";
        echo "<p>Quantity: {$product['quantity']}</p>";
        echo "<p>Total: " . ($product['price'] * $product['quantity']) . "</p>";
        echo "<form method='post' action='cart.php'>";
        echo "<input type='hidden' name='product_id' value='{$product['product_id']}' />";
        echo "<input type='number' name='quantity' value='{$product['quantity']}' />";
        echo "<button type='submit' name='action' value='update'>Update</button>";
        echo "<button type='submit' name='action' value='delete'>Delete</button>";
        echo "</form>";
        echo "</div>";
    }
    
    $total = array_sum(array_map(function($product) {
        return $product['price'] * $product['quantity'];
    }, $products));
    
    echo "<p>Total Amount: $total</p>";
    echo "<a href='checkout.php'>Proceed to Checkout</a>";
} else {
    echo "Your cart is empty.";
}
?>
