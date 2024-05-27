session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['checkout'])) {
    $user_id = $_SESSION['user_id'];
    $cart = $_SESSION['cart'];
    
    $conn->begin_transaction();
    
    try {
        $conn->query("INSERT INTO tbl_orders (user_id, order_date) VALUES ($user_id, NOW())");
        $order_id = $conn->insert_id;
        
        foreach ($cart as $product_id => $quantity) {
            $result = $conn->query("SELECT price FROM tbl_products WHERE product_id = $product_id");
            $product = $result->fetch_assoc();
            $price = $product['price'];
            
            $conn->query("INSERT INTO tbl_order_details (order_id, product_id, quantity, price) VALUES ($order_id, $product_id, $quantity, $price)");
        }
        
        $conn->commit();
        unset($_SESSION['cart']);
        echo "Order placed successfully!";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}
?>

<form method="post" action="checkout.php">
    <button type="submit" name="checkout">Place Order</button>
</form>
